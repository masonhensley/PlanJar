$(function() {
    initialize_group_list_panel();
});

function initialize_group_list_panel(){
    // Make it draggable (with a handle).
    $('#group_member_panel').draggable({
        handle: '.title_bar'
    });
    
    // Closing click handler
    $('#cancel_group_member_panel').click(function () {
        $('#group_member_panel').hide('fast');
    });
}

function populate_group_member_panel(){
    $.get('/home/populate_group_member_panel', {
        group_id : $('.selectable_group .selected_group').attr('group_id')
    }, function(data){
        $('.member_list').html(data);
        $('#group_member_panel').show('fast');
        
        alert('data');
    });
}