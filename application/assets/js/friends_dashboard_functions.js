$(function() {
    initialize_friends_list();
});

function initialize_friends_list() {
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
    
}