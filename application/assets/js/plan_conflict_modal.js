$(function () {
    initialize_conflict_modal();
});

// Initialize the modal
function initialize_conflict_modal() {
    
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
        console.log('done');
        
        // Remove the click handlers
        $('#plan_conflict_select .selectable_event').unbind('click');
    });
}