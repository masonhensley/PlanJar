$(function(){
    view_plan_list();
});

function view_plan_list()
{
    
    $('.view_attending').click(function(){
        
    });
       
    $.get('/home/load_attending_list', {
        plan_id : $('.selected_plan').attr('plan_id')
    },
    function(data){
    
        });
       
}