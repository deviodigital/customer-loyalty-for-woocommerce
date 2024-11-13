jQuery(document).ready(function ($) {
    $('#clwc-redeem-points').on('click', function (e) {
        e.preventDefault();
        console.log('Redeem button clicked'); // Debugging line

        $.ajax({
            url: clwc_ajax.ajax_url,
            method: 'POST',
            data: {
                action: 'clwc_redeem_points',
                nonce: clwc_ajax.nonce,
                user_id: clwc_ajax.user_id
            },
            success: function (response) {
                console.log(response); // Debugging line
                if (response.success) {
                    // Prepend new coupon to the table
                    $('#clwc-coupons-table tbody').prepend(response.data.html);

                    // Display success message
                    $('#clwc-redeem-points').after('<p class="clwc-redeem-success" style="color: green;">Coupon redeemed successfully!</p>');
                    setTimeout(function () {
                        $('.clwc-redeem-success').fadeOut();
                    }, 3000);

                    // Update the loyalty points display
                    $('.clwc-loyalty-points-total').text(response.data.updated_points);
                } else {
                    alert(response.data.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX error:', status, error); // Debugging line
                alert('An error occurred. Please try again.');
            }
        });
    });
});
