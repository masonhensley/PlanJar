$(function() {
    $('.tab').disableSelection();
    $('.content').hide();
    $('.about_me').show("slow");
    
    $('.tab').click(function(){
        
        $('.tab_selected').removeClass('tab_selected');
        $(this).addClass('tab_selected');
       $('.content').hide();
       
        if($(this).hasClass('mymusic'))
        {
            $('.my_music').show("slow");
        }
        else if($(this).hasClass('aboutme'))
        {
            $('.about_me').show("slow");
        }
        else if($(this).hasClass('otherstuff'))
        {
            $('.other_stuff').show("slow");
        }
       
    });
    
});