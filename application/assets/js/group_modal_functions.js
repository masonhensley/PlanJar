$(function() {
    $('.create_group_content').hide();
    initialize_create_group_modal();
});

function initialize_create_group_modal()
{
    $('.create_group').click(function(){
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
    divset('#group_privacy_wrapper');
    
    // Initial select
    $('.create_group_content input[type="radio"]:first').click();
    
    // --------- validator ----------
    $('#create_group').validate({
        rules: {
            group_name: {
                required: true,
                minLength: 4
            }
        },
        submitHandler: function(form) {
            console.log($(form).serialize());
        },
        errorPlacement: function () {
            // Don't display errors
            return true;
        }
    });
    
    // token-input
    $('#group_invite_user').tokenInput('/home/get_followers_invite', {
        hintText: 'Search followers...',
        preventDuplicates: true,
        queryParam: 'needle'
    });
    
    $('#group_invite_group').tokenInput('/home/get_groups_invite', {
        hintText: 'Search joined groups...',
        preventDuplicates: true,
        queryParam: 'needle'
    });
}