$(function() {
    $('.create_group_content').hide();
    initialize_create_group_modal();
});

function initialize_create_group_modal()
{
    $('.create_group').click(function(){
        $('.create_group_content').show("fast");
    });
    
    $('#cancel_group_creation').click(function(){
        $('.create_group_content').hide("fast");
    });
    
    $('.create_group_content .in-field_label label').inFieldLabels();   
}