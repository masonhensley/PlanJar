$(function() {
    populate_followers_list();
    initialize_followers_list();
});
    
function initialize_followers_list() {
    $.get('/dashboard/get_followers', function (data) {
        $('#followers_list').html(data);
    });
}

function populate_followers_list() {
    $.get('/dashboard/get_followers', function (data) {
        $('#followers_list').html(data);
        
        // Click handler.
        $('#followers_list .add_following').click(function () {
            alert('clicked');
            if ($(this).text() == '+ Follow') {
                $(this).text('+ You sure?');
            } else {
                $.get('/dashboard/add_following', {
                    following_id: $(this).parent().attr('user_id')
                }, function (data) {
                    populate_followers_list();
                });
            }
        });
        
        // Make followers selectable
        $('#followers_list .user_follow_entry').click(function() {
            $('.user_follow_entry.selected_follower').removeClass('selected_follower');
            $(this).addClass('selected_follower');
            $.get('/dashboard/get_follower_details', {
                follower_id: $(this).attr('user_id')
            }, function (data) {
                $('#followers_content .right').html(data);
            });
        });
    });
}