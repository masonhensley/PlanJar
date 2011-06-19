$(function() {
    $( "#plans" ).tabs();
    $( ".selector" ).tabs({
        select: function(event, ui) {  
      $("#tabs").tabs("select","#tabs-2");
      alert("this is working");
    }
    });
    
});