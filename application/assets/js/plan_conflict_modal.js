$(function () {
    initialize_conflict_modal();
});

// Initialize the modal
function initialize_conflict_modal() {
    // Draggable
    $('#plan_content_modal').draggable({
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
        
        
        
        // Hide the modal
        $('#plan_content_modal').hide('fast');
        
        callback();
    });
}