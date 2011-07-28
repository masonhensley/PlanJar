$(function() {
    initialize_create_group_modal();
});

// Initializes the create group modal
function initialize_create_group_modal()
{
    // Opening click handler
    $('.create_group').click(function(){
        // Initial selects
        $('#group_privacy_wrapper :first').click();
        $('#create_group_content input[type="radio"]:first').click();
        
        // Show the modal
        $('#create_group_content').show("fast", function () {
            $('#group_name').focus();
        });
    });
    
    // Make it draggable (with a handler).
    $('#create_group_content').draggable({
        handle: '.title_bar'
    });
    
    // Closing click handler
    $('#cancel_group_creation').click(function(){
        // Clear the fields.
        $('#group_name, #group_description').val('').blur();
        
        $('#create_group_content').hide("fast");
    });
    
    $('#create_group_content .in-field_block label').inFieldLabels();   
    
    // divSet
    $('#group_privacy_wrapper').divSet();
    
    // --------- validator ----------
    $('#create_group').validate({
        rules: {
            group_name: {
                required: true,
                rangelength: [2, 45]
            }
        },
        submitHandler: function(form) {
            $.get('/dashboard/create_group?' + $(form).serialize(), {
                privacy: $('#group_privacy_wrapper .divset_selected').attr('priv_type')
            }, function (data) {
                if (data == 'success') {
                    // Repopulate the following groups.
                    populate_edit_groups_list();
                    
                    $('#create_group_content').hide("fast");
                } else {
                    alert(data);
                }
            });
        },
        errorPlacement: function () {
            // Don't display errors
            return true;
        }
    });
}