$(function() {
    $( "#myplans" ).tabs();
    $('#tabMenu > li').click(function(){
         
        //perform the actions when it's not selected
        if (!$(this).hasClass('selected')) {    
 
            //remove the selected class from all LI    
            $('#tabMenu > li').removeClass('selected');
     
            //After cleared all the LI, reassign the class to the selected tab
            $(this).addClass('selected');
     
            //Hide all the DIV in .boxBody
            $('.boxBody div').slideUp('1500');
     
            //Look for the right DIV index based on the Navigation UL index
            $('.boxBody div:eq(' + $('#tabMenu > li').index(this) + ')').slideDown('1500');
     
        }
 
    }).mouseover(function() {
 
        //Add and remove class, Personally I dont think this is the right way to do it, 
        //if you have better ideas to toggle it, please comment    
        $(this).addClass('mouseover');
        $(this).removeClass('mouseout');   
     
    }).mouseout(function() { 
     
        //Add and remove class
        $(this).addClass('mouseout');
        $(this).removeClass('mouseover');    
     
    });
 
   
    //Mouseover with animate Effect for Category menu list  :)
    $('.boxBody #category li').mouseover(function() {
 
        //Change background color and animate the padding
        $(this).css('backgroundColor','#888');
        $(this).children().animate({
            paddingLeft:"20px"
        }, {
            queue:false, 
            duration:300
        });
    }).mouseout(function() {
     
        //Change background color and animate the padding
        $(this).css('backgroundColor','');
        $(this).children().animate({
            paddingLeft:"0"
        }, {
            queue:false, 
            duration:300
        });
    });  
     
    //Mouseover effect for Posts, Comments, Famous Posts and Random Posts menu list.
    $('.boxBody li').click(function(){
        window.location = $(this).find("a").attr("href");
    }).mouseover(function() {
        $(this).css('backgroundColor','#888');
    }).mouseout(function() {
        $(this).css('backgroundColor','');
    });   
});