jQuery(document).ready(function ($) {
    "use strict";
    // Runs when the image button is clicked.
    $('#repeatable-fieldset-one').on('click', '.image-upload', function (e) {
        var meta_image_frame, meta_image;
        // Prevents the default action from occuring.
        e.preventDefault();
        meta_image = $(this).parent().children('.meta-image');
        // If the frame already exists, re-open it.
        if (meta_image_frame) {
            meta_image_frame = undefined;
            meta_image_frame.open();
            return;
        }
        // Sets up the media library frame
        meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
            title: meta_image.title,
            button: {
                text: meta_image.button
            }
        });
        // Runs when an image is selected.
        meta_image_frame.on('select', function () {
            // Grabs the attachment selection and creates a JSON representation of the model.
            var media_attachment = meta_image_frame.state().get('selection').first().toJSON();
            // Sends the attachment URL to our custom image input field.
            meta_image.val(media_attachment.url);
            meta_image = undefined; // unset meta_image variable
            meta_image = $(this).parent().children('.meta-image');
        });
        // Opens the media library frame.
        meta_image_frame.open();
    });
});