// Custom jQuery divSet function
// Call this function on a div containing the divs you want to make into buttons. Everything else is done for you.
$.fn.divSet = function() {
    this.children().addClass('divset');
    
    // Click event
    this.children('div').click(function() {
        if (!$(this).hasClass('divset_selected')) {
            // Remove all selected classes
            $(this).siblings('div').removeClass('divset_selected');
            $(this).addClass('divset_selected');
        }
    });
}