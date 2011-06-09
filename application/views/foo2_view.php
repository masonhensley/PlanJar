<!DOCTYPE html> 
<html>
    <head>
        <title>MaseBook</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/peity/jquery.peity.js"></script>
        <script type="text/javascript" src="/application/assets/peity/code_highlighter.js"></script>
        <script type="text/javascript" src="/application/assets/peity/syntax.js"></script>

        <link type="text/css" rel="stylesheet" href="/application/assets/peity/style.css"/>

        <script>
$(function() {
                // Just the defaults.
                $("span.pie").peity("pie");
                $(".line").peity("line");
                $(".bar").peity("bar");
 
                // Set a custom colour and/or radius.
                $(".radius span").each(function() {
                    var elem = $(this);
                    var radius = elem.attr("class").match(/\d+/) * 4;
 
                    elem.peity("pie", { colours: ["#C6D9FD", "#4D89F9"], radius: radius });
                });
 
                // Simple evented example.
 
                $("select").change(function(){
                    $(this)
                    .siblings("span.graph")
                    .text($(this).val() + "/" + 5).change();
                }).change();
 
                var chartUpdate = function(event, value) {
                    $("#notice").text(
                    "Chart updated: " + value
                );
                };
 
                $("span.graph").peity("pie").bind("chart:changed", chartUpdate);
            });
        </script> 
    </head> 
    <body> 




<div id="container"> 
            
            <p>
                mini pie <span class="pie">2/5</span> 
                line <span class="line">5,3,9,6,5,9,7,3,5,2</span> 
                or bar chart <span class="bar">5,3,9,6,5,9,7,3,5,2</span>.</p> 

          

            

            <h2>H2</h2> 


            <p> 
                <span class="pie">1/5</span> 
                <span class="pie">226/360</span> 
                <span class="pie">0.52/1.561</span> 
            </p> 

            
            <h3>Custom Colours and Radius</h3>  

            <p class="radius"> 
                <span class="r10">1/10</span> 
                <span class="r9">2/10</span> 
                <span class="r8">3/10</span> 
                <span class="r7">4/10</span> 
                <span class="r6">5/10</span> 
                <span class="r5">6/10</span> 
                <span class="r4">7/10</span> 
                <span class="r3">8/10</span> 
                <span class="r2">9/10</span> 
                <span class="r1">10/10</span> 
            </p> 

            

            <h2>Line Charts</h2> 

            <p><span class="line">5,3,9,6,5,9,7,3,5,2</span></p> 

            <h2>Bar Charts</h2>

            <p><span class="bar">5,3,9,6,5,9,7,3,5,2</span></p>

            <h2> Playing around</h2> 
                <p class="radius"> 
                <span class="r10">1/10</span>
                mini pie <span class="pie">2/5</span> 
                line <span class="line">5,3,9,6,5,9,7,3,5,2</span> 
                or bar chart <span class="bar">5,3,9,6,5,9,7,3,5,2</span>.</p>

            <h2>Events</h2>

            <ul> 
                <li> 
                    <span class="graph"></span> 
                    <select> 
                        <option value="0">0</option> 
                        <option value="1">1</option> 
                        <option value="2">2</option> 
                        <option value="3">3</option> 
                        <option value="4" selected>4</option> 
                        <option value="5">5</option> 
                    </select> 
                </li> 
                <li> 
                    <span class="graph"></span> 
                    <select> 
                        <option value="0">0</option> 
                        <option value="1" selected>1</option> 
                        <option value="2">2</option> 
                        <option value="3">3</option> 
                        <option value="4">4</option> 
                        <option value="5">5</option> 
                    </select> 
                </li> 
                <li> 
                    <span class="graph"></span> 
                    <select> 
                        <option value="0">0</option> 
                        <option value="1">1</option> 
                        <option value="2">2</option> 
                        <option value="3" selected>3</option> 
                        <option value="4">4</option> 
                        <option value="5">5</option> 
                    </select> 
                </li> 
            </ul> 

            <p id="notice">Nothing's happened yet.</p> 



           
  
           
</div>

        </script> 
    </head> 

    <body> 

    </body>
</html>
