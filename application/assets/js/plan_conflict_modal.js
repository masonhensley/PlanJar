$(function () {
    initialize_conflict_modal();
});

// Initialize the modal
function initialize_conflict_modal() {
    // Draggable
    $('#plan_conflict_modal').draggable({
        handle: '.title_bar'
    });
}

// Open the modal
function open_conflict_modal(data, callback) {
    $('#plan_conflict_modal').show('fast');
    
    // Add the two choices
    $('#plan_conflict_select').html(data.html);
    
    // Add the title text
    $('#plan_conflict_modal .header').html(data.title_text);
    
    // Assign the click handler
    $('#plan_conflict_select .selectable_event').click(function() {
        // Capture the privacy and originator values
        var privacy = $(this).attr('priv_type');
        var originator = $(this).attr('originator');
        
        // Remove the click handlers
        $('#plan_conflict_select .selectable_event').unbind('click');
        
        // Resolve the conflict
        var keep_event = $(this).attr('event_id');
        $.get('/home/resolve_plan_conflict', {
            'keep_event': keep_event,
            discard_event: $(this).siblings(':first').attr('event_id')
        }, function (data) {
            // Hide the modal
            $('#plan_conflict_modal').hide('fast', function () {
                // Call the callback with the privacy type of the selected event, the originator bool value,
                // and the event id
                callback(privacy, originator, keep_event);
            });
        });
    });
}