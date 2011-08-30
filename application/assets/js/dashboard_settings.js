$(function() {
    initialize_settings();
});

// Called when the Settings tab is clicked
function settings_setup() {
}

// Initializer
function initialize_settings() {
    // In-field labels
    $('#settings_content label').inFieldLabels();
    
    // Picture uploader
    $('#image_upload').submit(function() {
        $(this).ajaxSubmit({
            beforeSubmit: function() {
                console.log('started');
            },
            success: function(data) {
                console.log(data);
                console.log(unescape(data));
                data = $.parseJSON(data);
                
                if (data.status == 'success') {
                    $('#settings_content .right').html(unescape(data.img));
                    $('#settings_content .right').show('fast');
                } else {
                    alert(data.message);
                }
            },
            url: '/dashboard/upload_picture',
            type: 'post',
            dataType: 'html'
        });
            
        return false;
    });
    
    // Cascade the showing of the password fields
    $('#new_password, #new_password_1').keyup(function() {
        $(this).parents('tr').next().show('fast');
    });
    
    // Submit the email notifications form whenever an element is changed
    $('#email_notifications input').change(function() {
        $.get('/dashboard/update_email_prefs?' + $(this).serialize());
    });
    
    // Populate the email checkboxes
    $.get('/dashboard/get_email_prefs', function(data) {
        data = $.parseJSON(data);
       
        $.map(data, function(item, key) {
            $('#' + key).prop('checked', parseInt(item));
        });
    });
    
    // Change password submit handler
    $('#change_password').submit(function() {
        $.get('/dashboard/change_password?' + $(this).serialize(), function(data) {
            if (data == 'success') {
                $('#old_password, #new_password, #new_password_1').val('');
                $('#old_password, #new_password, #new_password_1').blur();
                $('#new_password_1, #submit_new_password').parents('tr').hide('fast');
            } else {
                alert(data);
            }
        });
        
        return false;
    });
}