$(function() {
    initialize_followers_list();
});
    
function initialize_followers_list() {
    $.get('/dashboard/get_followers', function (data) {
        $('#followers_list').html(data);
        make_followers_selectable();
    });
}

function make_followers_selectable() {
    $('.user_follow_entry').click(function() {
        $('.user_follow_entry.selected_follower').removeClass('selected_follower');
        $(this).addClass('selected_follower');
        $.get('/dashboard/get_follower_details', {
            follower_id: $(this).attr('user_id')
        }, function (data) {
            $('#followers_content .right').html(data);
        });
    });
}