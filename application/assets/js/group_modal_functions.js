$(function() {
    initialize_create_group_modal();
});

function initialize_create_group_modal()
{
    // hide the create_group icon unless the groups tab is selected
    if($('.tab_selected').attr('assoc_div') == '#groups_content')
    {
        $('.create_group').hide();
    }else{
        $('.create_group').show();
    }
    
}