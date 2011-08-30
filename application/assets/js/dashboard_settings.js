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
                return $('#image').val() != ''
            },
            success: function(data) {
                data = $.parseJSON(data);
                
                if (data.status == 'success') {
                    // Hide/show the upload form/alt text
                    $('#image_upload').hide('fast', function() {
                        $('#image_upload_alt').show('fast');
                    });
                    
                    // Add the image and show the div
                    $('#preview_image').attr('src', unescape(data.img));
                    $('#settings_content .right').show('fast');
                    
                    // Image area select
                    $('#preview_image').imgAreaSelect({
                        aspectRatio: '1:1',
                        imageHeight: data.height / 3,
                        imageWIdth: data.width / 3,
                        x1: 0,
                        y1: 0,
                        x2: 80,
                        y2: 80,
                        handles: 'corners',
                        onSelectEnd: function(img, selection) {
                            // Update the inputs
                            $('#x1').val(selection.x1);
                            $('#y1').val(selection.y1);
                            $('#x2').val(selection.x2);
                            $('#y2').val(selection.y2);
                            
                            // Show the submit button
                            $('#upload_crop').show('fast');
                        }
                    });
                } else {
                    // Error
                    alert(data.message);
                }
            },
            url: '/dashboard/upload_picture',
            type: 'post',
            dataType: 'html'
        });
            
        return false;
    });
    
    // Crop submit handler
    $('#crop_image').submit(function() {
        $.get('/dashboard/crop_temp_image?' + $(this).serialize(), function(data) {
            console.log(data); 
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