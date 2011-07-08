$(function() {
    populate_following_list();
    initialize_follow_search();
});

function populate_following_list() {
    $.get('/dashboard/get_following', function (data) {
        $('#following_list').html(data);
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
            $('.follow_search_entry').click(function () {
                $.get('/dashboard/add_following', {
                    following_id: $(this).attr('user_id')
                }, function (data) {
                    $('#friend_search').val('');
                    $('#friend_search').blur();
                    console.log(data);
                });
            });
        });
    });
}


//function initialize_friends_list() {
//    // Button click events
//    $('#friends_following').click(function() {
//        $('#friends_content .right').html('');
//        $('#find_friends').css('display', 'block');
//        populate_following();
//    });
//    $('#friends_followers').click(function() {
//        $('#find_friends').css('display', 'none');
//        populate_followers();
//    });
//    
//    $('.in-field_block label').inFieldLabels();
//}
//
//function populate_following() {
//    $.get('/dashboard/get_following', function (data) {
//        $('.friends_list').html(data);
//    });
//}
//
//function populate_followers() {
//    $.get('/dashboard/get_followers', function (data) {
//        $('.friends_list').html(data);
//        make_followers_selectable();
//    });
//}
//
//function make_followers_selectable() {
//    $('.follower_entry').click(function() {
//        $('.follower_entry.selected_follower').removeClass('selected_follower');
//        $(this).addClass('selected_follower');
//        $.get('/dashboard/get_follower_details', {
//            follower_id: $(this).attr('follower_id')
//        }, function (data) {
//            $('#friends_content .right').html(data);
//        });
//    });
//    
//    $('.follower_entry:first').click();
//}