// Custom jQuery divSet function
// Call this function on a div containing the divs you want to make into buttons. Everything else is done for you.
// Pass true to enable multiple selection
$.fn.divSet = function(multiple) {
    this.each(function() {
        var parent_wrapper = $(this);
        parent_wrapper.find('div').addClass('divset');
    
        // Click event
        parent_wrapper.find('div').click(function() {
            if (multiple == true) {
                // Multiple select
                if ($(this).hasClass('divset_selected')) {
                    $(this).removeClass('divset_selected');
                } else {
                    $(this).addClass('divset_selected');
                }
            } else {
                // Single select
                if (!$(this).hasClass('divset_selected')) {
                
                    // Select only this
                    parent_wrapper.find('div').removeClass('divset_selected');
                    $(this).addClass('divset_selected');
                }
            }
        });
    });
}
