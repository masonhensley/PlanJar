$(function() {
    $( "#plans" ).tabs({
        select: function(event, ui) {  
            $("#tabs").tabs("select","#tabs-2");
        }
    });
});