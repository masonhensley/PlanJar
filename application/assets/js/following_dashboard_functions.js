$(function() {
    populate_following_list();
    initialize_follow_search();
    initialize_suggested_friends();
});

function populate_following_list() {
    $.get('/dashboard/get_following', function (data) {
        $('#following_list').html(data);
        
        // Click handler.
        $('#following_list .remove_following').click(function () {
            $(this).text('You sure?');
            $(this).unbind('click');
            $(this).click(function () {
                $.get('/dashboard/remove_following', {
                    following_id: $(this).parent().attr('user_id')
                }, function (data) {
                    populate_following_list();
                    populate_followers_list();
                });
            });
        });
    });
}

function initialize_follow_search() {
    // In-field labels;
    $('.in-field_block label').inFieldLabels();
    
    $('#friend_search').keyup(function () {
        $('.suggested_active').removeClass('suggested_active'); // this unselects the "suggested friends" tab first
        $.get('/dashboard/follow_search', {
            needle: $(this).val()
        }, function (data) {
            $('#follow_search').html(data);
            
            // Click handler.
            $('#follow_search .add_following').click(function () {
                $(this).text('+ You sure?');
                $(this).unbind('click');
                $(this).click(function () {
                    $.get('/dashboard/add_user_following', {
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
    });
}

// This sets up the suggested friends list
function initialize_suggested_friends()
{
    $('.suggested_friends').click(function(){
        if($(this).hasClass('suggested_active'))
        {
            $(this).removeClass('suggested_active');
            $('#friend_search').val('');
            $('#follow_search').html('');
            $('#friend_search').blur();
            $('#friend_search').focus();
            
            initialize_follow_search();
        }else{
            $(this).addClass('suggested_active');
            
            // Clear the search box
            $('#friend_search').val('');
            $('#friend_search').blur();
            
            get_suggested_friends();
            
        }
    });
}

function get_suggested_friends()
{
    $.get('/dashboard/get_suggested_friends',
        function (data) {
            $('#follow_search').html(data);
                    
            $('.add_following').click(function () {
                if ($(this).text() == 'Follow') {
                    $(this).text('You sure?');
                } else {
                    $.get('/dashboard/add_user_following', {
                        following_id: $(this).parent().attr('user_id')
                    }, function (data) {
                        populate_following_list();
                        get_suggested_friends();
                    });
                }
            });
        });
}