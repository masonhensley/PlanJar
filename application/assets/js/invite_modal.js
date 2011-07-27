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
        // Call the close click handler
        $('#close_invite_modal').click();
    });
}

// Opens the modal and hides the groups invite pane if specified
function open_invite_modal(hide_groups) {
    if (hide_groups == undefined) {
        hide_groups = false;
    }
    
    populate_invite_followers_list();
    
    if (hide_groups) {
        $('#invite_groups_list_wrapper').css('display', 'none');
        $('#invite_modal').css('width', '300px');
    } else {
        populate_invite_groups_list();
    }
    
    $('#invite_modal').show('fast');
}

// Resets the modal
function reset_invite_modal() {
    // Clear the invite boxes
    $('#invite_followers_list, #invite_groups_list').html('');
    
    // Show the group invite box
    $('#invite_groups_list_wrapper').css('display', '');
    $('#invite_modal').css('width', '600px');
    
    // Clear and blur the search box
    $('#search_in_school').val('');
    $('#search_in_school').tokenInput('clear');
    $('#search_in_school').blur();
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