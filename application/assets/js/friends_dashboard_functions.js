$(function() {
    initialize_friends_list();
});

function initialize_friends_list() {
    // Initial select.
    $('#friends_following').select();
    populate_following();
    
    // Buttonset
    $('.radio').buttonset();
    
    // Button click events
    $('#friends_following').click(function() {
        populate_following();
    });
    $('#friends_followers').click(function() {
        populate_followers();
    });
    
    make_followers_selectable();
}

function populate_following() {
    $.get('/dashboard/get_following', function (data) {
        $('.friends_list').html(data);
    });
}

function populate_followers() {
    $.get('/dashboard/get_followers', function (data) {
        $('.friends_list').html(data);
    });
}

function make_followers_selectable() {
    $('.following_entry').click(function() {
        $('.following_entry.selected_follower').removeClass('selected_follower');
        $(this).addClass('selected_follower');
        console.log($(this).attr('follower_id'));
    });
}