$(function() {
    initialize_create_group_modal();
});

function initialize_create_group_modal()
{
    $('.create_group').click(function(){
        $('.create_group_content').show("fast");
    });
    
}