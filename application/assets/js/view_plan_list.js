$(function(){
    view_plan_list();
});

function view_plan_list()
{
    
    $('#view_attendees').click(function(){
        alert('hey');
        $.get('/home/attending_list', {
            plan_id : $('.selected_plan').attr('plan_id')
        });    
    });
       
}