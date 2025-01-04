<?php
/**
 * Register meta boxes.
 */
function factotum_register_meta_boxes() {
	add_meta_box( 'factotum-real-state-product-metadata', __( 'Real State Details', 'factotum' ), 'factotum_display_callback', 'real-state-product' ); // metabox for post type real-state-product
}

add_action( 'add_meta_boxes', 'factotum_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function factotum_display_callback( $post ) {
	?>
    <div class="hcf_box">
        <style scoped>
            .hcf_box {
                display: grid;
                grid-template-columns: max-content 1fr;
                grid-row-gap: 10px;
                grid-column-gap: 20px;
            }

            .hcf_field {
                display: contents;
            }
        </style>
		<?php
		// Add a nonce field so we can check for it later.
		wp_nonce_field( 'factotum_realstate_save', 'factotum_realstate' );
		$all_meta_fields = array(
			//agent details
			array( "name" => "idagence", "type" => "text", "placeholder" => __( "idAgence", "factotum" ) ),
			array( "name" => "prenomnegociateur", "type" => "text", "placeholder" => __( "prenomNegociateur", "factotum" ) ),
			array( "name" => "nomnegociateur", "type" => "text", "placeholder" => __( "nomNegociateur", "factotum" ) ),
			array( "name" => "emailnegociateur", "type" => "text", "placeholder" => __( "emailNegociateur", "factotum" ) ),

			array( "name" => "identifiant", "type" => "text", "placeholder" => __( "Identifiant", "factotum" ) ),
//			array( "name" => "categorieoffre", "type" => "text", "placeholder" => __( "Categorieoffre", "factotum" ) ),
//			array( "name" => "typebien", "type" => "text", "placeholder" => __( "typeBien", "factotum" ) ),
			array( "name" => "pieces", "type" => "text", "placeholder" => __( "pieces", "factotum" ) ),
//			array( "name" => "villepublique", "type" => "text", "placeholder" => __( "Villepublique", "factotum" ) ),
			array( "name" => "adresse", "type" => "text", "placeholder" => __( "Adresse", "factotum" ) ),
//			array(
//				"name"        => "codepostalpublic",
//				"type"        => "text",
//				"placeholder" => __( "Codepostalpublic", "factotum" )
//			),
			array( "name" => "surface", "type" => "text", "placeholder" => __( "Surface", "factotum" ) ),
			array( "name" => "montant", "type" => "text", "placeholder" => __( "Montant", "factotum" ) ),

			array( "name" => "chambres", "type" => "text", "placeholder" => __( "Chambers", "factotum" ) ),
			array( "name" => "sdb", "type" => "text", "placeholder" => __( "Sdb", "factotum" ) ),
			array(
				"name"        => "cave",
				"type"        => "select",
				"placeholder" => __( "Cave", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "gardien",
				"type"        => "select",
				"placeholder" => __( "Gardien", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array( "name" => "nbparking", "type" => "text", "placeholder" => __( "nbparking", "factotum" ) ),

			array(
				"name"        => "ascenseur",
				"type"        => "select",
				"placeholder" => __( "ascenseur", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "acceshandicape",
				"type"        => "select",
				"placeholder" => __( "acceshandicape", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "alarme",
				"type"        => "select",
				"placeholder" => __( "alarme", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "balcon",
				"type"        => "select",
				"placeholder" => __( "balcon", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "bureau",
				"type"        => "select",
				"placeholder" => __( "bureau", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "cave",
				"type"        => "select",
				"placeholder" => __( "cave", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "cellier",
				"type"        => "select",
				"placeholder" => __( "cellier", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),

			array( "name" => "dependances", "type" => "text", "placeholder" => __( "dependances", "factotum" ) ),

			array(
				"name"        => "dressing",
				"type"        => "select",
				"placeholder" => __( "dressing", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "gardien",
				"type"        => "select",
				"placeholder" => __( "gardien", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "interphone",
				"type"        => "select",
				"placeholder" => __( "interphone", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "lotissement",
				"type"        => "select",
				"placeholder" => __( "lotissement", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "meuble",
				"type"        => "select",
				"placeholder" => __( "meuble", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "mitoyenne",
				"type"        => "select",
				"placeholder" => __( "mitoyenne", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "piscine",
				"type"        => "select",
				"placeholder" => __( "piscine", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array(
				"name"        => "terrasse",
				"type"        => "select",
				"placeholder" => __( "terrasse", "factotum" ),
				"options"     => array(
					array( "label" => "Nos", "value" => "nos" ),
					array( "label" => "Oui", "value" => "oui" )
				)
			),
			array( "name" => "avancement", "type" => "text", "placeholder" => __( "avancement", "factotum" ) ),
		);

		foreach ( $all_meta_fields as $field ) {
			$field["value"] = esc_attr( get_post_meta( get_the_ID(), $field["name"], true ) );
			echo factotum_get_custom_field_html( $field );
		}

		$images = get_post_meta( get_the_ID(), 'photo', true );
		?>
        <p class="meta-options hcf_field"><label for="image">Images</label>
        <div id="realstate-images-html">
			<?php
			if ( ! empty( $images ) ) { ?>
				<?php
				foreach ( $images as $image ) {
					?>
                    <img style="width: 200px;height: 200px"
                         src="<?php echo wp_get_original_image_url( $image ); ?>">
                    <input type="hidden" name="real_state_images[]" value="<?php echo $image; ?>"/>
					<?php
				}
			}
			?>
        </div>
        <input id="real_state_image_select_btn" type="button" class="button-primary" value="Change"/>
        <script>
            jQuery(document).ready(function ($) {
                var mediaUploader;
                $('#real_state_image_select_btn').click(function (e) {
                    e.preventDefault();
                    if (mediaUploader) {
                        mediaUploader.open();
                        return;
                    }
                    mediaUploader = wp.media.frames.file_frame = wp.media({
                        title: 'Choose Image',
                        button: {
                            text: 'Choose Image'
                        }, multiple: true
                    });
                    mediaUploader.on('select', function () {
                        var attachment = mediaUploader.state().get('selection').toJSON();
                        if (attachment && attachment.length !== 0) {
                            console.log(attachment);
                            $("#realstate-images-html").html("");
                            var image_html = '';
                            $.each(attachment, function (propName, propVal) {
                                if (propVal && propVal.id && propVal.url) {
                                    image_html += '<img style="width: 200px;height: 200px" src="' + propVal.url + '" />';
                                    image_html += '<input type="hidden" name="real_state_images[]" value="' + propVal.id + '" />';
                                }
                            });
                            $("#realstate-images-html").html(image_html);
                        }
                    });
                    mediaUploader.open();
                });
            });
        </script>
        </p>
		<?php

		?>
    </div>
	<?php
}

function factotum_get_custom_field_html(
	$field = array(
		"type"        => "text",
		"name"        => "",
		"placeholder" => "",
		"value"       => ""
	)
) {
	$return_data = "";
	switch ( $field["type"] ) {
		case "text" :
			$return_data = '<p class="meta-options hcf_field"><label for="' . $field["placeholder"] . '">' . $field["placeholder"] . '</label><input id="' . $field["name"] . '" type="text" name="' . $field["name"] . '"
                  placeholder"' . $field["placeholder"] . '" value="' . $field["value"] . '"></p>';
			break;
		case "select" :
			$return_data = '<p class="meta-options hcf_field"><label for="' . $field["placeholder"] . '">' . $field["placeholder"] . '</label>
<select name="' . $field["name"] . '">';
			foreach ( $field["options"] as $option ) {
				if ( $field["value"] == $option["value"] ) {
					$return_data .= '<option selected value="' . $option["value"] . '">' . $option["label"] . '</option>';
				} else {
					$return_data .= '<option value="' . $option["value"] . '">' . $option["label"] . '</option>';
				}

			}
			$return_data .= '</select>';
			break;
	}

	return $return_data;
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id
 */
function factotum_save_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['factotum_realstate'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['factotum_realstate'], 'factotum_realstate_save' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	} else {

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	$fields = [
		'identifiant',
		'categorieoffre',
		'typebien',
		'pieces',
		'villepublique',
		'adresse',
		'codepostalpublic',
		'surface',
		'montant',
		'chambres',
		'sdb',
		'cave',
		'gardien',
		'nbparking',
		'ascenseur',
		'acceshandicape',
		'alarme',
		'balcon',
		'bureau',
		'cave',
		'cellier',
		'dependances',
		'dressing',
		'gardien',
		'interphone',
		'lotissement',
		'meuble',
		'mitoyenne',
		'piscine',
		'terrasse',
		'avancement'
	];
	foreach ( $fields as $field ) {
		if ( array_key_exists( $field, $_POST ) ) {
			update_post_meta( $post_id, $field, sanitize_text_field( $_POST[ $field ] ) );
		}
	}

	if ( isset( $_POST["real_state_images"] ) ) {
		if ( ! empty( $_POST["real_state_images"] ) ) {
			$att_ids = array();
			foreach ( $_POST["real_state_images"] as $attachment_id ) {
				if ( $attachment_id > 0 && $attachment_id != "" ) {
					$att_ids[] = $attachment_id;
				}
			}
			update_post_meta( $post_id, 'photo', $att_ids );
		}
	}

}

add_action( 'save_post', 'factotum_save_meta_box_data' );