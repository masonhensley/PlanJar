$(function() {
    show_selected_location();
});

function show_selected_location() {
    
    $('.plan_shown').click(function() {
        alert(success);
       $('.selected_location').removeClass('selected_loactoin');
       $(this).addClass('selected_location');
       hide_data_containers();
       show_data_container('#plan_data');
       
    });
}
