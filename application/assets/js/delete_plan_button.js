$(function() {
    delete_user_plan();
});

function delete_user_plan() {
    $('div.delete_plan').click(function() {
        
        $('.delete_plan_container').html('<div class=\"delete_plan\">Sure?</div></div>');
 
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