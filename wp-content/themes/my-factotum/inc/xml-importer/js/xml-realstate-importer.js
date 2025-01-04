jQuery(document).ready(function ($) {
    var mediaUploader;
    $('#upload_realstate_xml_file').click(function (e) {
        e.preventDefault();
        if (mediaUploader) {
            mediaUploader.open();
            return;
        }
        mediaUploader = wp.media.frames.file_frame = wp.media({
            title: 'Choose Image',
            button: {
                text: 'Choose Image'
            },
            multiple: false
        });
        mediaUploader.on('select', function () {
            var attachment = mediaUploader.state().get('selection').first().toJSON();
            $('#realstate_xml_file').val(attachment.url);
            console.log(attachment);
        });
        mediaUploader.open();
    });


    $("#xml-real-state-import-btn").on("click", function (e) {
        e.preventDefault();
        if ($("#realstate_xml_file").val() == "") { //if file is not selected alert to select file first
            alert("PLease select the file first");
            return false;
        }
        $("#xml-real-state-import-btn").html("<i class='fa fa-spinner fa-spin'></i>Importing");
        jQuery.ajax({
            type: "post",
            dataType: "json",
            url: myAjax.ajaxurl,
            data: {
                action: "xml_import_get_file_details",
                nonce: myAjax.import_realstate_nonce,
                xml_file: $("#realstate_xml_file").val()
            },
            success: function (response) {
                if (response.status == 0) {
                    $("#respone-message-log").html(response.message);
                    $("#xml-real-state-import-btn").html("Import");
                } else {
                    if (response.data && response.data.annonce && response.data.annonce.length > 0) {
                        let total_data_count = response.data.annonce.length;

                        let updated_count = 0;
                        let insert_count = 0;
                        let failed_count = 0;
                        let completed_count = 0;


                        for (let i = 0; i < total_data_count; i++) {
                            jQuery.ajax({
                                type: "post",
                                dataType: "json",
                                url: myAjax.ajaxurl,
                                data: {
                                    action: "import_realstate_xml",
                                    nonce: myAjax.import_realstate_nonce,
                                    xml_file: $("#realstate_xml_file").val(),
                                    array_index: i
                                },
                                success: function (response) {
                                    completed_count++;
                                    if (response.status && response.status > 0) {
                                        if (response.status == 2) {
                                            updated_count++;
                                        } else {
                                            insert_count++;
                                        }
                                    } else {
                                        failed_count++;
                                    }
                                    $("#respone-message-log").html("<h2>Status</h2>" + completed_count + " of " + total_data_count + " Completed <br>" + "Inserted: " + insert_count + "<br>Updated: " + updated_count + "<br>Failed:" + failed_count);
                                    if (i == total_data_count - 1) {
                                        $("#xml-real-state-import-btn").html("Import");
                                    }
                                    console.log(updated_count);
                                }
                            })
                        }
                    } else {
                        $("#respone-message-log").html("The file is invalid or empty");
                        $("#xml-real-state-import-btn").html("Import");
                    }
                }
            }
        });

    });
});