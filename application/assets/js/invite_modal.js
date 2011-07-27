$(function () {
    initialize_invite_modal();
})

function initialize_invite_modal() {
    // Close click handler
    $('#close_invite_modal').click(function () {
      
        $('#invite_modal').hide('fast', function () {
            reset_invite_modal();
        });
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

// Opens the modal and hides the groups invite pane if necessary
function open_invite_modal(priv_type, subject_type) {
    // Create the invite title
    var title_text = 'This ' + subject_type + ' has <b>' + priv_type + '</b> privacy settings.<hr/>';
    $('#invite_modal .title').html(title_text);
    
    // Determine whether to hide the groups
    var hide_groups = true;
    if (subject_type == 'event' && priv_type == 'open') {
        // Only show your joined groups for an open event
        hide_groups = false;
    }
    
    // Populate the followers
    populate_invite_followers_list();
    
    // Hide the groups pane or populate it
    if (hide_groups) {
        $('#invite_groups_list_wrapper').css('display', 'none');
        $('#invite_modal').css('width', '300px');
    } else {
        populate_invite_groups_list();
    }
    
    // Show the modal
    $('#invite_modal').show('fast');
}

// Resets the modal
function reset_invite_modal() {
    // Clear the invite boxes
    $('#invite_followers_list, #invite_groups_list').html('');
    
    // Show the group invite box
    $('#invite_groups_list_wrapper').css('display', '');
    $('#invite_modal').css('width', '500px');
    
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
            console.log($(this));
            if ($(this).hasClass('divset_selected')) {
                console.log($(this));
                console.log($(this).attr('user_id'));
                // Add the recently selected user to the tokenInput
                $('#search_in_school').tokenInput('add', {
                    id: $(this).attr('user_id'), 
                    name: $(this).html()
                });
            } else {
                // Remove the just unselected user from the tokenInput
                $('#search_in_school').tokenInput('remove', {
                    id: $(this).attr('user_id')
                });
            }
        });
    });
}

// Populates and initializes the groups list
function populate_invite_groups_list() {
    $.get('/home/get_joined_groups_divset', function (data) {
        $('#invite_groups_list').html(data);
        $('#invite_groups_list').divSet(true);
        
        
    });
}