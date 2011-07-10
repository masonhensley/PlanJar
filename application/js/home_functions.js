$(function() {
    $('.container').click(function(){
        $('.tab_selected').removeClass('tab_selected');
        $(this).removeClass('tab');
        $(this).addClass('tab_selected');
    });
});