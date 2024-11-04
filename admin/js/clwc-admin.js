jQuery(document).ready(function($) {
    console.log("Loyalty Points script loaded."); // Debugging line
    $('.clwc-loyalty-points').on('change', function() {
        var $input = $(this);
        var userID = $input.data('user-id');
        var points = $input.val();

        console.log("User ID:", userID, "Points:", points); // Debugging line

        // Disable the input while processing
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
                console.log(response); // Debugging line
                if (response.success) {
                    // Optionally, provide visual feedback
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
                // Re-enable the input
                $input.prop('disabled', false);
            }
        });
    });
});
