$(function() {
    $( "#plans" ).tabs({
        select: function(event, ui) {  
            $("#weekdays").tabs("select","#tabs-2");
        }
    });
    
});