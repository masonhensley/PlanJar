// Custom jQuery confirmDiv function
// Call this function on a div button that you want to make into a confirmation button
// (asks for confirmation before continuing)
// The parameter is the function to call on success.
$.fn.confirmDiv = function(callback) {
    $('html').click();
    this.click({
        'callback': callback
    }, outer_confirm_handler);
}

function outer_confirm_handler(main_event) {
    // Stop propagation (to allow for clicking anywhere BUT the element)
    event.stopPropagation();

    // Clear previous handlers
    $(this).unbind('click');
    
    // Get the original text
    var orig_text = $(this).html();
        
    // Replacement text
    $(this).html('You sure?');
        
    // Assign the secondary (final) click event
    $(main_event.target).click(function(inner_event){
        // Stop propagation (to allow for clicking anywhere BUT the element)
        inner_event.stopPropagation();
        
        // Success
        main_event.data.callback($(this)); 
    });
    
    $('html').one('click', function() {
        // Replace the original text and re-assign the click event
        $(main_event.target).html(orig_text);
        $(main_event.target).unbind('click');
        $(main_event.target).click({
            'callback': main_event.data.callback
        }, outer_confirm_handler);
    });
}