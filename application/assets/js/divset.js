$.fn.divSet = function() {
    $(this + ' > div').addClass('divset');
    
    // Click event
    $(this + ' > div').click(function() {
        if (!this.hasClass('divset_selected')) {
            // Remove all selected classes
            $(this + ' > div').removeClass('divset_selected');
            this.addClass('divset_selected');
        }
    });
}



function divset(container) {
    $(container + ' > div').addClass('divset');
    
    // Click event
    $(container + ' > div').click(function() {
        if (!$(this).hasClass('divset_selected')) {
            // Remove all selected classes
            $(container + ' > div').removeClass('divset_selected');
            $(this).addClass('divset_selected');
        }
    });
}