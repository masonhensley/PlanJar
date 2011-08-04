$(function () {
    initialize_add_location_modal();
});

// Initializes the add location modal
function initialize_add_location_modal() {
    // Close click handler
    $('#close_add_location').click(function () {
        $('#add_location_modal').hide('fast');
    });
    
    // Draggable
    $('#close_add_location').draggable({
        handle: '.title_bar'
    });
}

// Opens the add location modal
function show_add_location_modal() {
    $('#add_location_modal').show('fast');
}