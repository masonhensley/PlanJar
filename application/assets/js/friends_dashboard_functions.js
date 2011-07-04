$(function() {
    initialize_friends_list();
});

function initialize_friends_list() {
    // Initial select.
    $('#friends_following').click();
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
}

function populate_following() {
    $.get('/dashboard/get_following', function (data) {
        $('.friends_list').html(data);
    });
}

function populate_followers() {
    $.get('/dashboard/get_followers', function (data) {
        $('.friends_list').html(data);
        make_followers_selectable();
    });
}

function make_followers_selectable() {
    $('.follower_entry').click(function() {
        alert('clicked');
        $('.follower_entry.selected_follower').removeClass('selected_follower');
        $(this).addClass('selected_follower');
        console.log($(this).attr('follower_id'));
    });
}