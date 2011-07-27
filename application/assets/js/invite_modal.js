$(function () {
    initialize_invite_modal();
})

function initialize_invite_modal() {
    // Close click handler
    $('#close_invite_modal').click(function () {
        reset_invite_modal();
        
        $('#invite_modal').hide('fast');
    });
    
    // Draggable
    $('#invite_modal').draggable({
        handle: '.title_bar'
    });
    
    // TokenInput
    $('#search_in_school').tokenInput('/home/search_school_users', {
        hintText: '',
        preventDuplicates: true,
        queryParam: 'needle',
        theme: 'facebook'
    });
    
    // Submit handler
    $('#send_invites').click(function () {
        
        });
}

function open_invite_modal(priv_type, invite_type) {
    populate_invite_followers_list();
    populate_invite_groups_list();
    
    $('#invite_modal').show('fast');
}

function reset_invite_modal() {
    
}

// Populates and initializes the followers list
function populate_invite_followers_list() {
    $.get('/home/get_followers_divset', function (data) {
        $('#invite_followers_list').html(data);
        $('#invite_followers_list').divSet(true);
        
        // Click handler
        $('#invite_followers_list').find('div').click(function() {
            
            });
    });
}

// Populates and initializes the groups list
function populate_invite_groups_list() {
    $.get('/home/get_joined_groups_divset', function (data) {
        $('#invite_groups_list').html(data);
        $('#invite_groups_list').divSet(true);
        
        // Click handler
        $('#invite_groups_list').find('div').click(function() {
            
            });
    });
}