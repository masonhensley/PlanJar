$(function () {
    initialize_invite_modal();
});

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
        theme: 'facebook',
        onAdd: function (item) {
            // Select the appropriate follower if necessary
            if (!$('#invite_followers_list').find('div[user_id="' + item.id + '"]').hasClass('divset_selected')) {
                $('#invite_followers_list').find('div[user_id="' + item.id + '"]').click();
            }
        },
        onDelete: function (item) {
            // Unselect the appropriate follower if necessary
            if ($('#invite_followers_list').find('div[user_id="' + item.id + '"]').hasClass('divset_selected')) {
                $('#invite_followers_list').find('div[user_id="' + item.id + '"]').click();
            }
        }
    });
    
    // Submit handler
    $('#send_invites').click(function () {
        // Make sure at least something is selected
        if ($('#search_in_school').val() != '' || $('#invite_groups_list .divset_selected').length > 0) {    
            // Populate the selected group list
            var group_list = [];
            $('#invite_groups_list .divset_selected').each(function (index, element) {
                group_list.push($(element).attr('group_id'));
            });
            
            // Calculate data to send
            var user_ids = $('#search_in_school').val().split(',');
            if (user_ids[0] == '') {
                user_ids = [];
            }
            
            var data = {
                'user_ids': user_ids,
                'group_ids': group_list,
                'subject_id': $('#invite_subject_id').val(),
                'subject_type': $('#invite_subject_type').val(),
                'privacy': $('#invite_priv_type').val()
            };
            
            // Send to the server
            $.get('/home/invite_people', data, function(data) {
                if (data == 'success') {
                    // Call the close click handler
                    $('#close_invite_modal').click();
                }
            });
        }
    });
}

// Opens the modal and hides the groups invite pane if necessary
// priv_type is '' for groups
function open_invite_modal(subject_type, subject_id, priv_type, plan_originator) {
    // Hide the modal
    $('#invite_modal').hide('fast', function () {
        reset_invite_modal();
            
        // Store the information in hidden inputs
        $('#invite_subject_type').val(subject_type);
        $('#invite_subject_id').val(subject_id);
        $('#invite_priv_type').val(priv_type);
            
        // Create the invite title
        var title_text = 'This ' + subject_type + ' has <b>' + priv_type + '</b> privacy settings.<hr/>';
        $('#invite_modal .title').html(title_text);
    
        // Determine whether to hide the groups
        var hide_groups = true;
        if (subject_type == 'event' && (priv_type == 'open' || plan_originator == true)) {
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
    });
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
        
    // Clear the hidden fields
    $('#invite_modal input[type="hidden"]').val('');
}

// Populates and initializes the followers list
function populate_invite_followers_list() {
    console.log('shit got called');
    $.get('/home/get_followers_divset', function (data) {
        $('#invite_followers_list').html(data);
        $('#invite_followers_list').divSet(true);
        
        // Click handler
        $('#invite_followers_list').find('div').click(function() {
            if ($(this).hasClass('divset_selected')) {
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