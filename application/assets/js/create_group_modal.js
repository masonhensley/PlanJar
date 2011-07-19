$(function() {
    initialize_create_group_modal();
});

function initialize_create_group_modal()
{
    $('.create_group').click(function(){
        // Clear the fields.
        $('#group_name, #group_description').val('').blur();
        
        // Initial selects
        $('#group_privacy_wrapper :first').click();
        $('.create_group_content input[type="radio"]:first').click();
        $('#group_name').focus();
    
        // Clear the token inputs
        $('#group_invite_users, #group_invite_groups').tokenInput('clear');
        
        $('.create_group_content').show("fast");
        // Make it draggable (with a handler).
        $('.create_group_content').draggable({
            handle: '.create_group_top_bar'
        });
    });
    
    $('#cancel_group_creation').click(function(){
        $('.create_group_content').hide("fast");
    });
    
    $('.create_group_content .in-field_block label').inFieldLabels();   
    
    // divset
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
                    
                    $('.create_group_content').hide("fast");
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
    
    // token-input
    $('#group_invite_users').tokenInput('/home/get_followers_invite', {
        hintText: 'Search followers...',
        preventDuplicates: true,
        queryParam: 'needle'
    });
    
    $('#group_invite_groups').tokenInput('/home/get_groups_invite', {
        hintText: 'Search joined groups...',
        preventDuplicates: true,
        queryParam: 'needle'
    });
}