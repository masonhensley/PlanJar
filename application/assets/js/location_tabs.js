$(function() {
    show_selected_location();
});

function show_selected_location() {
    $('div.plan_shown').click(function() {
        alert('success');
       $('.selected_location').removeClass('selected_location');
       $(this).addClass('selected_location');
       hide_data_containers();
       show_data_container('#plan_data');   
    });
}
