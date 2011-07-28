$(function() {
    initialize_create_group_modal();
});

// Initializes the create group modal
function initialize_create_group_modal()
{
    // Initial select
    reset_group_modal();
    
    // Opening click handler
    $('#create_group').click(function(){
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
        reset_group_modal();
        
        // Hide the modal
        $('#create_group_content').hide("fast");
    });
    
    // In-field labels
    $('#create_group_content .in-field_block label').inFieldLabels();   
    
    // divSet
    $('#group_privacy_wrapper').divSet();
    
    // Submit handler
    $('#submit_create_group').click(function() {
        // Make sure there is a name
        if ($('#group_name').val() != '') {
            $.get('/dashboard/create_group?' + $('#create_group').serialize(), {
                privacy: $('#group_privacy_wrapper .divset_selected').attr('priv_type')
            }, function (data) {
                
                // Hide and reset the modal and then open the invite modal
                $('#create_group_content').hide('fast', function () {
                    // Clear the group modal
                    reset_group_modal();
                    
                    // Open the invite modal
                    open_invite_modal('group', data, privacy, true);
                });
                
                // Repopulate the following groups.
                populate_edit_groups_list();
            });
        }
    });
}

// Resets the group modal
function reset_group_modal() {
    // Clear the fields.
    $('#group_name, #group_description').val('').blur();
        
    // Select the first item among both the divSet and the radio buttons
    console.log($('#group_privacy_wrapper:first'));
    console.log($('#group_privacy_wrapper :first'));
    console.log($('#group_privacy_wrapper div:first'));
    console.log($('#group_privacy_wrapper div :first'));
    
    $('#group_privacy_wrapper div:first').click();
    $('#create_group_content input[type="radio"]:first').click();
}