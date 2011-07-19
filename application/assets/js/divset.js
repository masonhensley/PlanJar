//(function ($) {
$.fn.divSet = function() {
    this.children().addClass('divset');
    
    // Click event
    this.children().click(function() {
        if (!$(this).hasClass('divset_selected')) {
            // Remove all selected classes
            $(this).siblings().removeClass('divset_selected');
            $(this).addClass('divset_selected');
        }
    });
}
//})(jQuery);



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