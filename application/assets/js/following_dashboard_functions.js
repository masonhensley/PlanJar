$(function() {
    populate_following_list();
    initialize_follow_search();
});

function populate_following_list() {
    $.get('/dashboard/get_following', function (data) {
        $('#following_list').html(data);
        
        // Click handler.
        $('#following_list .remove_following').click(function () {
            if ($(this).text() == '- Unfollow') {
                $(this).text('- You sure?');
            } else {
                $.get('/dashboard/remove_following', {
                    following_id: $(this).parent().attr('user_id')
                }, function (data) {
                    populate_following_list();
                });
            }
        });
    });
}

function initialize_follow_search() {
    // In-field labels;
    $('.in-field_block label').inFieldLabels();
    
    $('#friend_search').keyup(function () {
        $.get('/dashboard/follow_search', {
            needle: $(this).val()
        }, function (data) {
            $('#follow_search').html(data);
            
            // Click handler.
            $('#follow_search .add_following').click(function () {
                $.get('/dashboard/add_following', {
                    following_id: $(this).parent().attr('user_id')
                }, function () {
                    $('#follow_search').html('');
                    $('#friend_search').val('');
                    populate_following_list();
                    $('#friend_search').blur();
                });
            });
        });
    });
}