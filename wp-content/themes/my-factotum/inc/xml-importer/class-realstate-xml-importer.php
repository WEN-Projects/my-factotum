<?php

if (!class_exists("MY_Factotum_XML_Importer")) {
    class MY_Factotum_XML_Importer
    {
        public function __construct()
        {
            add_action('admin_menu', array(
                $this,
                'myFactotumXmlImporterPage'
            )); //create xml import page in admin menu

            add_action('wp', array($this, 'extractAndImport')); // xml import testing
            $this->rsImporterBackendSetup();
        }

        public function rsImporterBackendSetup()
        {
            // Add the custom columns to the book real-state-product post type:
            add_filter('manage_real-state-product_posts_columns', function ($columns) {
                unset($columns["date"]);
                $columns['identifiant'] = __('Identifiant', 'my-factotum');
                $columns['date'] = __('Date', 'my-factotum');
                return $columns;
            });
            // Add the data to the custom columns for the book post type:
            add_action('manage_real-state-product_posts_custom_column', function ($column, $post_id) {
                switch ($column) {

                    case 'identifiant' :
                        $identifiant = get_post_meta($post_id, 'identifiant', true);
                        echo $identifiant;
                        break;

                }
            }, 10, 2);


            // Add sortable column to Column Array
            add_filter('manage_edit-real-state-product_sortable_columns', function ($columns) {
                $columns["identifiant"] = "identifiant";
                return $columns;
            });

            // 4. here is the sorting brain
            add_filter('request', function ($vars) {
                if (isset($vars['orderby']) && 'identifiant' == $vars['orderby']) {
                    $vars = array_merge($vars, array(
                        'meta_key' => 'identifiant',
                        'orderby' => 'meta_value_num',
                        'order' => 'asc'

                    ));
                }
                return $vars;
            });

        }

        public function extractAndImport()
        {
            $verification = filter_input(INPUT_GET, 'update_real_states');
            if ($verification == "osS-dDd568") { //update the realstates only if the request is valid from cron
                $this->extractTheZipFile();
                $this->importUpdateRealStatesFromXML();
                $this->sendEmailAlerte(); // after the realstate import is completed, search for the products with alerte criteria of users, if matched send an email to the user
//				die();
            }
        }

        public function extractTheZipFile()
        {
            $path = WP_CONTENT_DIR . "/real-state-zip";
            $files = array_diff(scandir($path), array('.', '..'));
            $status = false;
            if (!empty($files)) {
                foreach ($files as $file_name) {
                    $zip = new ZipArchive;
                    if ($zip->open(WP_CONTENT_DIR . "/real-state-zip/" . $file_name) === true) {
                        $zip->extractTo(WP_CONTENT_DIR . "/uploads/real-states/");
                        $zip->close();
                        $status = true;
                    }
                }
            }

            return $status;
        }

        public function importUpdateRealStatesFromXML()
        {
//            if ( empty( get_option( 'realstate_xml_file_url' ) ) ) {
//                return;
//            }
            $files = array_diff(scandir(WP_CONTENT_DIR . "/uploads/real-states/"), array('.', '..'));
            if (!empty($files)) {
                foreach ($files as $file_name) {
                    $ext = pathinfo($file_name, PATHINFO_EXTENSION);
                    if ($ext == "xml") {
                        $file_path = esc_url(WP_CONTENT_DIR . "/uploads/real-states/" . $file_name);

//                        if ( filter_var( $file_path, FILTER_VALIDATE_URL ) === false ) {
//                            return;
//                        }
                        $element = @simplexml_load_file($file_path);
                        if ($element === false) { //error reading file or invalid file url
                            // error!
                            echo "error reading file or invalid file url";
                        } else {
                            $xml = simplexml_load_file($file_path); // if file is not valid, send failed response
                            if ($xml->count() > 0) {
                                $log_message = "";
                                $insert_count = 0;
                                $update_count = 0;
                                $delete_count = 0;
                                $xml = (array)$xml;
                                foreach ($xml as $single_xml) { //import all the lists from xml
                                    if (!empty($single_xml)) {
                                        foreach ($single_xml as $single_record) {
                                            if (isset($single_record->identifiant)) { //if identifiant(sku) exist in the record
                                                $args = array(
                                                    'post_type' => 'real-state-product',
                                                    'meta_query' => array(
                                                        array(
                                                            'key' => 'identifiant',
                                                            'value' => (string)$single_record[0]->identifiant,
                                                            'compare' => '=',
                                                        )
                                                    )
                                                );
                                                $post_already_exists = get_posts($args);
                                                if (empty($post_already_exists)) { // if the realstate (post) doesnt exist in our database, insert new one
                                                    $post_id = wp_insert_post(array(   // insert the new post(realstate) into our database
                                                        'post_type' => 'real-state-product',
                                                        'post_title' => isset($single_record->villePublique) ? (string)$single_record[0]->villePublique : "",
                                                        'post_content' => isset($single_record->texte) ? (string)$single_record[0]->texte : "",
                                                        'post_status' => 'publish',
                                                        'comment_status' => 'closed',   // if you prefer
                                                        'ping_status' => 'closed',      // if you prefer
                                                    ));

                                                    if ($post_id) {
                                                        $insert_count++;
                                                        // insert all post meta
                                                        $this->_updateMetaFields($post_id, $single_record); // add all meta fields

                                                        if (isset($single_record->photos)) {
                                                            foreach (json_decode(json_encode($single_record->photos), true) as $photo) {
                                                                $att_ids = array();
                                                                if (is_array($photo)) { //if multiple images
                                                                    foreach ($photo as $single) {
                                                                        $result = $this->_uploadRemoteImageAndAttach($single, $post_id);
                                                                        if ($result) {
                                                                            $att_ids[] = $result;
                                                                        }
                                                                    }
                                                                } else { //if single image
                                                                    $result = $this->_uploadRemoteImageAndAttach($photo, $post_id);
                                                                    $att_ids[] = $result;
                                                                }
                                                                add_post_meta($post_id, 'photo', $att_ids);
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    $update_count++;
                                                    $post = reset($post_already_exists); //if in case multiple posts are found, we will edit only one i.e first element of array.
                                                    $id = $post->ID;
                                                    $my_post = array(
                                                        'ID' => $id,
                                                        'post_title' => isset($single_record->villePublique) ? (string)$single_record[0]->villePublique : "",
                                                        'post_content' => isset($single_record->texte) ? (string)$single_record[0]->texte : ""
                                                    );
                                                    $post_updated_id = wp_update_post($my_post);
                                                    if ($post_updated_id) {
                                                        $this->_updateMetaFields($post_updated_id, $single_record); //insert update all meta fields


                                                        //update the photo
                                                        if (isset($single_record->photos)) {
                                                            $photos_array_xml = json_decode(json_encode($single_record->photos), true);
                                                            foreach ($photos_array_xml as $photo_xml) {
                                                                $att_ids = array(); //attachments ids to be saved in our database

                                                                $photo_xml_array = array();
                                                                if (is_array($photo_xml)) { //if multiple images
                                                                    $photo_xml_array = $photo_xml;
                                                                } else { //if single image,convert it into array
                                                                    $photo_xml_array[] = $photo_xml;
                                                                }

                                                                foreach ($photo_xml_array as $single) { // loop through the images in xml file
                                                                    if (post_exists(basename($single))) { // if image file already exists in our database
                                                                        $page = get_page_by_title(basename($single), OBJECT, 'attachment'); // get image detail if file already exists
                                                                        if (isset($page->ID)) {
                                                                            $att_ids[] = $page->ID;
                                                                        }
                                                                    } else { // if xml image is not present in our database then upload the new image in our database and get it's attachment
                                                                        $result = $this->_uploadRemoteImageAndAttach($single, $post_updated_id);
                                                                        if ($result) {
                                                                            $att_ids[] = $result;
                                                                        }
                                                                    }
                                                                }

                                                                update_post_meta($post_updated_id, 'photo', $att_ids);
                                                            }
                                                        } else {
                                                            update_post_meta($post_updated_id, 'photo', array());
                                                        }
                                                    } else {
                                                    }
                                                }
                                                wp_reset_postdata(); //reset the post query

                                            }
                                        }

                                    }
                                }
                                $failed_count = count($xml) - $insert_count - $update_count;

                                $log_message = "Total Added : " . $insert_count . " Total Updated : " . $update_count;
                                $postarr = array(
                                    'post_title' => "xml import log",
                                    'post_type' => "xml-import-log",
                                    'post_status' => "publish",
                                    'post_content' => $log_message
                                );
                                wp_insert_post($postarr);
                            }
                        }
                    }
                }
            }

        }

        private function sendEmailAlerte()
        {
            global $wpdb;
            $results = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key = \"_alerte_real_state\"", ARRAY_A);
            if (empty($results)) {
                return false;
            }
            foreach ($results as $meta) {
                if (isset($meta["meta_value"], $meta["user_id"])) {
                    $criteria = unserialize($meta["meta_value"]);
//					echo "<pre>";
//					print_r($criteria);
                    if (is_array($criteria)) { // build a wp query from the criteria if the post(realstate) exists
                        if (isset($criteria["get_email_status"], $criteria["alerte_criteria"])) {
                            $criteria_array = $criteria["alerte_criteria"];
                            if ($criteria["get_email_status"] == "true" || $criteria["get_email_status"] == true) { // only if the criteria has the send email enabled
                                $args = $this->buildQuery($criteria_array);
                                $query = new WP_Query($args);
                                if ($query->have_posts()) { //match found, send email to the user
                                    $this->sendEmail($meta["user_id"], $criteria_array);
                                }
                                wp_reset_query();
                            }
                        }
                    }
                }
            }
            //after alerte mail has been sent, mark all the realstates product as "sent_alerte", so that mail wont be sent again for same realstate item in next xml import
            $args = array(
                "post_type" => "real-state-product",
                "post_status" => "publish",
                "posts_per_page" => -1
            );
            $query = new WP_Query($args);
            if ($query->have_posts()) {
                while ($query->have_posts()) {
                    $query->the_post();
                    update_post_meta(get_the_ID(), "_is_alerte_mail_sent", 1);
                }
            }
            wp_reset_query();
        }

        private function sendEmail($user_id, $criteria)
        {
            $user = get_user_by('ID', $user_id);
            if (isset($user->user_email)) {
                $subject = __("Alerte match has been found", "factotum");
                $message = __("Alerte match has been found", "factotum");
                $search_url = get_field("real_states_listing_page", "option");
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $criteria_url = "";
                if ($search_url) {
                    $search_url_new = add_query_arg($criteria, $search_url);
                    $criteria_url = esc_url($search_url_new);
                }

                if (get_option("alerte_mail_subject", "option")) {
                    $subject = get_field("alerte_mail_subject", "option");
                }
                if (get_option("alerte_mail_template", "option")) {
                    $message = str_replace('{$alerte_url}', $criteria_url, get_field("alerte_mail_template", "option"));
                }
                wp_mail($user->user_email, $subject, $message, $headers);
            }
        }

        private function buildQuery($criteria_array)
        {
            $meta_query[] = array(
                'key' => '_is_alerte_mail_sent',
                'compare' => 'NOT EXISTS'
            );
            if (isset($criteria_array["pieces"])) {
                if (!empty($criteria_array["pieces"])) {
                    $meta_query[] = array(
                        'key' => 'pieces',
                        'value' => (int)$criteria_array["pieces"],
                        'compare' => '>=',
                    );
                }
            }
            if (isset($criteria_array["max_price"])) {
                if (!empty($criteria_array["max_price"])) {
                    $meta_query[] = array(
                        'key' => 'montant',
                        'value' => (int)$criteria_array["max_price"],
                        'compare' => '<=',
                        'type' => 'UNSIGNED'
                    );
                }
            }
            if (isset($criteria_array["min_surface"])) {
                if (!empty($criteria_array["min_surface"])) {
                    $meta_query[] = array(
                        'key' => 'surface',
                        'value' => $criteria_array["min_surface"],
                        'compare' => '>=',
                        'type' => 'UNSIGNED'
                    );
                }
            }

            $args = array(
                "post_type" => "real-state-product",
                "post_status" => "publish",
                "posts_per_page" => -1
            );
            $tax_query = array();
            if (isset($criteria_array["type"])) {
                if (!empty($criteria_array["type"])) {
                    $tax_query[] =
                        array(
                            'taxonomy' => 'categorie-offre',
                            'field' => 'slug',
                            'terms' => $criteria_array["type"],
                            'include_children' => false
                        );
                }
            }
            if (isset($criteria_array["location"])) {
                if (!empty($criteria_array["location"])) {
                    $tax_query[] =
                        array(
                            'taxonomy' => 'postal-ville',
                            'field' => 'slug',
                            'terms' => $criteria_array["location"],
                            'include_children' => false
                        );
                }
            }
            if (isset($criteria_array["category"])) {
                if (!empty($criteria_array["category"])) {
                    $tax_query[] = array(
                        'taxonomy' => 'type-bien',
                        'field' => 'slug',
                        'terms' => $criteria_array["category"],
                        'include_children' => false
                    );
                }
            }
            if (!empty($tax_query)) {
                $args["tax_query"] = $tax_query;
            }
            if (!empty($meta_query)) {
                $args["relation"] = 'AND';
                $args["meta_query"] = $meta_query;
            }

            return $args;
        }

        public function myFactotumXmlImporterPage()
        { //create admin menu page
            add_submenu_page(
                'edit.php?post_type=real-state-product',
                __('Real State Importer', 'factotum'),
                'Realstate Importer',
                'manage_options',
                "realstate-xml-importer",
                array($this, "renderXmlImporterAdminPage"),
                null,
                6
            );
        }

        public function renderXmlImporterAdminPage()
        {
            if (isset($_POST["realstate_xml_file_url"]) && isset($_POST["same_file_url"])) {
                update_option("realstate_xml_file_url", $_POST["realstate_xml_file_url"]);
            }
            ?>
            <div class="wrap">
                <h2>File URL</h2>
                <form method="post">
                    <input style="width: 50%" id="realstate_xml_file" type="text" name="realstate_xml_file_url"
                           value="<?php echo get_option('realstate_xml_file_url'); ?>" required/>
                    <br>
                    <br>
                    <input id="upload_realstate_xml_file" type="submit" name="same_file_url" class="button-primary"
                           value="Update"/>
                    <br>
                </form>

            </div>
            <?php
        }

        private function _updateMetaFields($post_id = 0, $single_record)
        {
            if (isset($single_record->codePostalPublic) && isset($single_record->villePublique)) { // update the realstate post category(taxonomy terms)
                $term = $this->_getTermIdOfLocation($single_record->codePostalPublic, $single_record->villePublique);
                if (!empty($term)) {
                    wp_set_post_terms($post_id, $term, "postal-ville");
                }

            }

            if (isset($single_record->typeBien)) { // update the realstate post category(type bien terms)
                $term_id = $this->_getTermIdOfTypeBien($single_record->typeBien);
                if ($term_id > 0) {
                    wp_set_post_terms($post_id, array($term_id), "type-bien");
                }
            }

            if (isset($single_record->categorieOffre)) { // update the realstate post category(categorie offre terms)
                $term_id = $this->_getTermIdOfCategorieOffre($single_record->categorieOffre);
                if ($term_id > 0) {
                    wp_set_post_terms($post_id, array($term_id), "categorie-offre");
                }
            }

            //function to update all post meta fields
            update_post_meta($post_id, 'idagence', isset($single_record->idAgence) ? (string)$single_record[0]->idAgence : ""); //cast simpleXML Object to a string // Id of agency
            update_post_meta($post_id, 'identifiant', isset($single_record->identifiant) ? (string)$single_record[0]->identifiant : ""); //cast simpleXML Object to a string //sku for each realstate
            update_post_meta($post_id, 'categorieoffre', isset($single_record->categorieOffre) ? (string)$single_record[0]->categorieOffre : ""); // type
            update_post_meta($post_id, 'typebien', isset($single_record->typeBien) ? (string)$single_record[0]->typeBien : ""); // realstate category
            update_post_meta($post_id, 'pieces', isset($single_record->pieces) ? (string)$single_record[0]->pieces : ""); // realstate category

            update_post_meta($post_id, 'villepublique', isset($single_record->villePublique) ? (string)$single_record[0]->villePublique : ""); //cast simpleXML Object to a string
            update_post_meta($post_id, 'adresse', isset($single_record->adresse) ? (string)$single_record[0]->adresse : ""); //cast simpleXML Object to a string
            update_post_meta($post_id, 'codepostalpublic', isset($single_record->codePostalPublic) ? (string)$single_record[0]->codePostalPublic : ""); //cast simpleXML Object to a string
            update_post_meta($post_id, 'surface', isset($single_record->surface) ? (string)$single_record[0]->surface : ""); //cast simpleXML Object to a string
            update_post_meta($post_id, 'montant', isset($single_record->montant) ? (string)$single_record[0]->montant : ""); //cast simpleXML Object to a string


            update_post_meta($post_id, 'chambres', isset($single_record->chambres) ? (int)$single_record[0]->chambres : 0); //cast simpleXML Object to a integer
            update_post_meta($post_id, 'sdb', isset($single_record->sdb) ? (int)$single_record[0]->sdb : 0);
            update_post_meta($post_id, 'cave', isset($single_record->cave) ? (int)$single_record[0]->cave : "non");
            update_post_meta($post_id, 'gardien', isset($single_record->gardien) ? (int)$single_record[0]->gardien : "non");
            update_post_meta($post_id, 'nbparking', isset($single_record->nbParking) ? (int)$single_record[0]->nbParking : 0);
            update_post_meta($post_id, 'ascenseur', isset($single_record->ascenseur) ? (int)$single_record[0]->ascenseur : "non");

            update_post_meta($post_id, 'acceshandicape', isset($single_record->accesHandicape) ? (string)$single_record[0]->accesHandicape : "non");
            update_post_meta($post_id, 'alarme', isset($single_record->alarme) ? (string)$single_record[0]->alarme : "non");
            update_post_meta($post_id, 'ascenseur', isset($single_record->ascenseur) ? (string)$single_record[0]->ascenseur : "non");
            update_post_meta($post_id, 'balcon', isset($single_record->balcon) ? (string)$single_record[0]->balcon : "non");
            update_post_meta($post_id, 'bureau', isset($single_record->bureau) ? (string)$single_record[0]->bureau : "non");
            update_post_meta($post_id, 'cave', isset($single_record->cave) ? (string)$single_record[0]->cave : "non");
            update_post_meta($post_id, 'cellier', isset($single_record->cellier) ? (string)$single_record[0]->cellier : "non");
            if (isset($single_record->dependances)) {
                update_post_meta($post_id, 'dependances', 1);
            }
            update_post_meta($post_id, 'dressing', isset($single_record->dressing) ? (string)$single_record[0]->dressing : "non");
            update_post_meta($post_id, 'gardien', isset($single_record->gardien) ? (string)$single_record[0]->gardien : "non");
            update_post_meta($post_id, 'interphone', isset($single_record->interphone) ? (string)$single_record[0]->interphone : "non");
            update_post_meta($post_id, 'lotissement', isset($single_record->lotissement) ? (string)$single_record[0]->lotissement : "non");
            update_post_meta($post_id, 'meuble', isset($single_record->meuble) ? (string)$single_record[0]->meuble : "non");
            update_post_meta($post_id, 'mitoyenne', isset($single_record->mitoyenne) ? (string)$single_record[0]->mitoyenne : "non");
            update_post_meta($post_id, 'piscine', isset($single_record->piscine) ? (string)$single_record[0]->piscine : "non");
            update_post_meta($post_id, 'terrasse', isset($single_record->terrasse) ? (string)$single_record[0]->terrasse : "non");

            //agent details
            update_post_meta($post_id, 'prenomnegociateur', isset($single_record->prenomNegociateur) ? (string)$single_record[0]->prenomNegociateur : "");
            update_post_meta($post_id, 'nomnegociateur', isset($single_record->nomNegociateur) ? (string)$single_record[0]->nomNegociateur : "");
            update_post_meta($post_id, 'emailnegociateur', isset($single_record->emailNegociateur) ? (string)$single_record[0]->emailNegociateur : "");


            //reaslstate item status whether the realstate item is sold or Suspendu or Mandat
            update_post_meta($post_id, 'avancement', isset($single_record->avancement) ? (string)$single_record[0]->avancement : ""); // avancement - for showing in backend and use as meta
            if (isset($single_record->avancement)) { // _avancement - only for sorting/ordering option in realstate listing
                if ($single_record->avancement == "Compromis") {
                    update_post_meta($post_id, '_avancement', 0);
                } else {
                    update_post_meta($post_id, '_avancement', 1);
                }

            } else {
                update_post_meta($post_id, '_avancement', 1);
            }

        }

        private function _uploadRemoteImageAndAttach($image_url, $parent_id)
        { //function to upload image and return attachment post id

            $image = $image_url;

            $get = wp_remote_get($image);

            $type = wp_remote_retrieve_header($get, 'content-type');

            if (!$type) {
                return false;
            }

            $mirror = wp_upload_bits(basename($image), '', wp_remote_retrieve_body($get));

            $attachment = array(
                'post_title' => basename($image),
                'post_mime_type' => $type
            );

            $attach_id = wp_insert_attachment($attachment, $mirror['file'], $parent_id);

            require_once(ABSPATH . 'wp-admin/includes/image.php');

            $attach_data = wp_generate_attachment_metadata($attach_id, $mirror['file']);

            wp_update_attachment_metadata($attach_id, $attach_data);

            return $attach_id;

        }

        private function _getTermIdOfLocation($postal, $address)
        { //function to use (postal and ville) as term for postal-ville(taxonomy).
            $parent_term_id = 0;
            $child_term_id = 0;
            $terms = array();
            if (taxonomy_exists("postal-ville")) {
                $parent_term_exists = term_exists(sanitize_title("postal-" . $postal), "postal-ville");
                if ($parent_term_exists) {
                    $parent_term_id = isset($parent_term_exists["term_id"]) ? $parent_term_exists["term_id"] : 0;
                } else {
                    $insert_parent_term = wp_insert_term((string)$postal, "postal-ville", array(
                        "slug" => sanitize_title("postal-" . $postal),
                        "description" => $postal
                    ));
                    if (!is_wp_error($insert_parent_term)) {
                        $parent_term_id = isset($insert_parent_term["term_id"]) ? $insert_parent_term["term_id"] : 0;
                    }
                }
                if ($parent_term_id > 0) { // only if parent term exists
                    $child_term_exists = term_exists(sanitize_title("postal-" . $postal . "-ville-" . $address), "postal-ville", $parent_term_id);
                    if ($child_term_exists) {
                        $child_term_id = isset($child_term_exists["term_id"]) ? $child_term_exists["term_id"] : 0;
                    } else {
                        $insert_child_term = wp_insert_term((string)$address, "postal-ville", array(
                            "slug" => sanitize_title("postal-" . $postal . "-ville-" . $address),
                            "parent" => $parent_term_id
                        ));
                        if (!is_wp_error($insert_child_term)) {
                            $child_term_id = isset($insert_child_term["term_id"]) ? $insert_child_term["term_id"] : 0;
                        }
                    }
                }
            }
            if ($parent_term_id > 0) {
                $terms[] = $parent_term_id;
            }
            if ($child_term_id > 0) {
                $terms[] = $child_term_id;
            }

            return $terms; // child term id to be used to set as post's term
        }

        private function _getTermIdOfTypeBien($type_bien)
        { //function to use typeBien as terms for taxonomy "type-bien".
            $term_id = 0;
            if (taxonomy_exists("type-bien")) {
                $term_exists = term_exists(sanitize_title("type-bien-" . $type_bien), "type-bien"); // check if term aleady exist in the taxonomy type-bien
                if ($term_exists) {
                    $term_id = isset($term_exists["term_id"]) ? $term_exists["term_id"] : 0;
                } else {
                    $insert_parent_term = wp_insert_term((string)$type_bien, "type-bien", array(
                        "slug" => sanitize_title("type-bien-" . $type_bien),
                        "description" => $type_bien
                    ));
                    if (!is_wp_error($insert_parent_term)) {
                        $term_id = isset($insert_parent_term["term_id"]) ? $insert_parent_term["term_id"] : 0;
                    }
                }
            }

            return $term_id; // child term id to be used to set as post's term
        }

        private function _getTermIdOfCategorieOffre($categorie_offre)
        { //function to use typeBien as terms for taxonomy "type-bien".
            $term_id = 0;
            if (taxonomy_exists("categorie-offre")) {
                $term_exists = term_exists(sanitize_title("categorie-offre-" . $categorie_offre), "categorie-offre"); // check if term aleady exist in the taxonomy categorie-offre
                if ($term_exists) {
                    $term_id = isset($term_exists["term_id"]) ? $term_exists["term_id"] : 0;
                } else {
                    $insert_parent_term = wp_insert_term((string)$categorie_offre, "categorie-offre", array(
                        "slug" => sanitize_title("categorie-offre-" . $categorie_offre),
                        "description" => $categorie_offre
                    ));
                    if (!is_wp_error($insert_parent_term)) {
                        $term_id = isset($insert_parent_term["term_id"]) ? $insert_parent_term["term_id"] : 0;
                    }
                }
            }

            return $term_id; // child term id to be used to set as post's term
        }

        private function getFileIfMediaFileAlreadyExists($filename)
        {
            global $wpdb;
            $query = "SELECT * FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'";

            return $query;
        }

    }

    new MY_Factotum_XML_Importer();
}