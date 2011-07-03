$(function() {
    delete_user_plan();
});

function delete_user_plan() {
    $('div.delete_plan').click(function() {
        $('.delete_plan').html('<div id=\"container\" style=\"width:50px; height:50px; position:relative; top:40px; margin-right:auto; margin-left:auto;\">Sure?</div>');
        
        $('div.delete_plan').click(function(){
            $.get('/home/delete_plan', {
                'plan_selected': $('.selected_plan').attr('plan_id')
            }, function (data) {
                // Replace the data and show the data tab.
                $('#plan_data').html(data);
                populate_plan_panel();
            }); 
        });
    });
}