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
    
    $('#select_me').addClass('divset_selected');
    $('.divset').click(function(){
        $('.divset_selected').removeClass('divset_selected');
        $(this).addClass('divset_selected');
    });
}