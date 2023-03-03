jQuery(document).ready(function($) {
    $('#cookie-form').on('submit', function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        
        $.ajax({
            type: 'POST',
            url: iteket_cookie_script_vars.ajax_url,
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#cookie-notice').fadeOut();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.log('Error:', errorThrown);
            }
        });
    });
});
