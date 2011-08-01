// Custom jQuery confirmDiv function
// Call this function on a div button that you want to make into a confirmation button
// (asks for confirmation before continuing)
// The parameter is the function to call on success.
$.fn.confirmDiv = function(callback) {
    console.log(callback);
    this.click({
        'callback': callback
    }, outer_confirm_handler);
}

function outer_confirm_handler(event) {
    console.log(event.data.callback);
    // Stop propagation (to allow for clicking anywhere BUT the element)
    event.stopPropagation();

    // Clear previous handlers
    $(this).unbind('click');
    $('html').unbind('click');
    
    // Get the original text
    var orig_text = $(this).html();
        
    // Replacement text
    $(this).html('Sure?');
        
    // Assign the secondary (final) click event
    $(this).click(function(event){
        // Stop propagation (to allow for clicking anywhere BUT the element)
        event.stopPropagation();
        
        // Success
        callback();
    });
        
    $('html').click(function() {
        // Replace the original text and re-assign the click event
        $('.delete_plan').html(orig_text);
        $('.delete_plan').unbind('click');
        $('.delete_plan').click(outer_confirm_handler);
    });
}