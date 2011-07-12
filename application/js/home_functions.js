$(function() {
    // run when loads
    $('.tab').disableSelection();
    $('.content').hide();
    $('.about_me').show("slow");
    $('.left_tab').hide();
    $('.youtube_videos').show("slow");
    $('.resume_tab').show("slow");
    $('.resume').hide();
   
    $('.resume_tab').click(function(){
        if($(this).hasClass('left_active'))
        {
            $('.resume').hide();
            $('.left_active').removeClass('left_active');
            $('.about_me_top').show("slow");
        }else{
            $('.resume').show("slow");
            $('.about_me_top').hide();
            $(this).addClass('left_active');
        }
    });
   
    $('.tab').click(function(){
        $('.left_active').removeClass('left_active');
        $('.left_tab').hide();
        $('.tab_selected').removeClass('tab_selected');
        $(this).addClass('tab_selected');
        
        $('.content').hide();
       
        if($(this).hasClass('mymusic'))
        {
            $('.music_content').hide();
            $('.my_music').show("slow");
            $('.music_tab').show("slow");
            $('.produced_music').hide();
            $('.in_concert').hide();
            $('.youtube_videos').show("slow");
            $('.youtube_videos_tab').addClass("left_active");
        }
        else if($(this).hasClass('aboutme'))
        {
            $('.resume').hide();
            $('.about_me').show("slow");
            $('.about_me_top').show("slow");
            $ ('.resume_tab').show("slow");
        }
        else if($(this).hasClass('otherstuff'))
        {
            $('.other_stuff').show("slow");
        }
       
    });
    
    $('.music_tab').click(function(){
        $('.music_content').hide();
        $('.left_active').removeClass('left_active');
        
        $(this).addClass('left_active');
        
        if($(this).hasClass('youtube_videos_tab'))
        {
            $('.youtube_videos').show("slow");
        }else if($(this).hasClass('music_produced_tab')){
            $('.produced_music').show("slow");
        }else if($(this).hasClass('in_concert_tab')){
            $('.in_concert').show("slow");
        }else if($(this).hasClass('.resume_tab'))
        {
                 
    }
    });
    
});