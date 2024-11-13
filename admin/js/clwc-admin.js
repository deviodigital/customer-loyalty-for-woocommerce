jQuery(document).ready(function($) {
    $('.clwc-loyalty-points').on('change', function() {
        var $input = $(this);
        var userID = $input.data('user-id');
        var points = $input.val();

        // Disable the input while processing.
        $input.prop('disabled', true);

        $.ajax({
            url: clwc_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'clwc_update_loyalty_points',
                user_id: userID,
                points: points,
                security: clwc_ajax.nonce,
            },
            success: function(response) {
                if (response.success) {
                    // Optionally, provide visual feedback.
                    $input.css('border-color', 'green');
                } else {
                    $input.css('border-color', 'red');
                    alert(response.data || 'An error occurred');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                $input.css('border-color', 'red');
                alert('An AJAX error occurred: ' + textStatus);
            },
            complete: function() {
                // Re-enable the input.
                $input.prop('disabled', false);
            }
        });
    });

    var file_frame;

    // Handle the Upload Image button click.
    $(document).on('click', '.clwc-upload-image-button', function(e) {
        e.preventDefault();

        var $button = $(this);

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create a new media frame.
        file_frame = wp.media.frames.file_frame = wp.media({
            title: 'Select or Upload an Image',
            button: {
                text: 'Use this image',
            },
            multiple: false
        });

        // When an image is selected, run a callback
        file_frame.on('select', function() {
            var attachment = file_frame.state().get('selection').first().toJSON();
            $button.siblings('.clwc-image-id').val(attachment.id);
            $button.siblings('.clwc-image-preview').attr('src', attachment.url).show();
            $button.siblings('.clwc-remove-image-button').show();
        });

        // Open the modal.
        file_frame.open();
    });

    // Handle the Remove Image button click.
    $(document).on('click', '.clwc-remove-image-button', function(e) {
        e.preventDefault();
        var $button = $(this);

        // Clear the hidden field and hide the preview image.
        $button.siblings('.clwc-image-id').val('');
        $button.siblings('.clwc-image-preview').attr('src', '').hide();
        $button.hide();
    });
});
