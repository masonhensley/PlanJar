$(function() {
    initialize_settings();
});

// Called when the Settings tab is clicked
function settings_setup() {
    // Cascade the showing of the password fields
    $('#old_password').keyup(function() {
        $(this).parents('tr').next().show('fast');
    });
    $('#new_password').keyup(function() {
        $(this).parents('tr').next().show('fast');
    });
}

// Initializer
function initialize_settings() {
    $('#settings_content label').inFieldLabels();
}