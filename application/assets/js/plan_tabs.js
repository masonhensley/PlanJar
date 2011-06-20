$(function() {
    $( "#plans" ).tabs({
        select: function(event, ui) {  
            $("#map_data_tabs").tabs("select","#data_tab");
        }
    });
});