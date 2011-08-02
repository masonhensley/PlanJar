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
        // Remove the click handlers
        $('#plan_conflict_select .selectable_event').unbind('click');
        
        // Resolve the conflict
        $.get('/home/resolve_plan_conflict', {
            keep_event: $(this).attr('event_id'),
            discard_event: $(this).siblings(':first').attr('event_id')
        }, function (data) {
            // Hide the modal
            $('#plan_conflict_modal').hide('fast', function () {
                callback();
            });
        });
    });
}