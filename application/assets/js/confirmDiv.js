// Custom jQuery confirmDiv function
// Call this function on a div button that you want to make into a confirmation button
// (asks for confirmation before continuing)
// The parameter is the function to call on success.
$.fn.confirmDiv = function(callback) {
    this.click({
        'callback': callback
    }, outer_confirm_handler);
}

function outer_confirm_handler(event) {
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
    console.log(event.currentTarget);
    console.log(event.target);
    console.log(this);
    
    $(event.currentTarget).click(function(inner_event){
        // Stop propagation (to allow for clicking anywhere BUT the element)
        inner_event.stopPropagation();
        
        console.log(event.currentTarget);
        console.log(event.target);
        console.log(this);
        
        // Success
        event.data.callback(); 
    });
        
    $('html').click(function() {
        // Replace the original text and re-assign the click event
        $('.delete_plan').html(orig_text);
        $('.delete_plan').unbind('click');
        $('.delete_plan').click({
            'callback': event.data.callback
        }, outer_confirm_handler);
    });
}