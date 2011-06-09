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
            <h1>Peity</h1> 

            <p>Peity (sounds like deity) is a simple <a href="http://jquery.com/">jQuery</a> 
                plugin that converts an element's content into a simple <code>&lt;canvas&gt;</code> 
                mini pie <span class="pie">2/5</span> 
                line <span class="line">5,3,9,6,5,9,7,3,5,2</span> 
                or bar chart <span class="bar">5,3,9,6,5,9,7,3,5,2</span>.</p> 

            <dl> 
                <dt>Download</dt> 
                <dd><a href="jquery.peity.js">jquery.peity.js</a> (3.9Kb)</dd> 
                <dd><a href="jquery.peity.min.js">jquery.peity.min.js</a> (2.1Kb)</dd> 

                <dt>Source</dt> 
                <dd><a href="http://github.com/benpickles/peity">github.com/benpickles/peity</a></dd> 

                <dt>Compatibility</dt> 
                <dd>Any browser that supports <code>&lt;canvas&gt;</code>:
                    Chrome, Firefox, Opera, Safari</dd> 
            </dl> 

            <div id="fork-me"><a href="http://github.com/benpickles/peity">Fork me on GitHub</a></div> 

            <h2>Pie Charts</h2> 

            <p>Simply call <code>peity("pie")</code> on a jQuery selection. You can also
                pass <code>colours</code>, <code>radius</code> and <code>delimeter</code> 
                options.</p> 

            <p> 
                <span class="pie">1/5</span> 
                <span class="pie">226/360</span> 
                <span class="pie">0.52/1.561</span> 
            </p> 

            <div class="example"> 
                <div class="html"> 
                    <h4>HTML</h4> 

                    <pre><code class="html">&lt;span class="pie"&gt;1/5&lt;/span&gt;
&lt;span class="pie"&gt;226/360&lt;/span&gt;
&lt;span class="pie"&gt;0.52/1.561&lt;/span&gt;</code></pre> 
                </div> 

                <div class="javascript"> 
                    <h4>Javascript</h4> 

                    <pre><code class="javascript">$("span.pie").peity("pie");</code></pre> 
                </div> 
            </div> 

            <h3>Custom Colours and Radius</h3> 

            <p>You can pass custom colours and radius to <code>peity("pie", { radius: 42 })</code>.</p> 

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

            <div class="example"> 
                <div class="html"> 
                    <h4>HTML</h4> 

                    <pre><code class="html">&lt;p class="radius"&gt;
  &lt;span class="r10"&gt;1/10&lt;/span&gt;
  &lt;span class="r9"&gt;2/10&lt;/span&gt;
  &lt;span class="r8"&gt;3/10&lt;/span&gt;
  &lt;span class="r7"&gt;4/10&lt;/span&gt;
  &lt;span class="r6"&gt;5/10&lt;/span&gt;
  &lt;span class="r5"&gt;6/10&lt;/span&gt;
  &lt;span class="r4"&gt;7/10&lt;/span&gt;
  &lt;span class="r3"&gt;8/10&lt;/span&gt;
  &lt;span class="r2"&gt;9/10&lt;/span&gt;
  &lt;span class="r1"&gt;10/10&lt;/span&gt;
&lt;/p&gt;</code></pre> 
                </div> 

                <div class="javascript"> 
                    <h4>Javascript</h4> 

                    <pre><code class="javascript">$(".radius span").each(function() {
  var elem = $(this);
  var radius = elem.attr("class").match(/\d+/) * 4;
 
  elem.peity("pie", {
    colours: ["#C6D9FD", "#4D89F9"],
    radius: radius
  });
});</code></pre> 
                </div> 
            </div> 

            <h2>Line Charts</h2> 

            <p>Line charts work on a comma-separated list of digits. Line charts can
                take the following options: <code>colour</code>, <code>strokeColour</code>,
                <code>strokeWidth</code>, <code>delimeter</code>, <code>width</code> and
                <code>height</code>.</p> 

            <p><span class="line">5,3,9,6,5,9,7,3,5,2</span></p> 

            <div class="example line-chart"> 
                <div class="html"> 
                    <h4>HTML</h4> 
                    <pre><code class="html">&lt;span class="line"&gt;5,3,9,6,5,9,7,3,5,2&lt;/span&gt;</code></pre> 
                </div> 

                <div class="javascript"> 
                    <h4>Javascript</h4> 
                    <pre><code class="javascript">$(".line").peity("line");</code></pre> 
                </div> 
            </div> 

            <h2>Bar Charts</h2> 

            <p>Bar charts work in the same way as line charts and take the following
                options: <code>colour</code>, <code>delimeter</code>, <code>width</code> 
                and <code>height</code>.</p> 

            <p><span class="bar">5,3,9,6,5,9,7,3,5,2</span></p> 

            <div class="example bar-chart"> 
                <div class="html"> 
                    <h4>HTML</h4> 
                    <pre><code class="html">&lt;span class="bar"&gt;5,3,9,6,5,9,7,3,5,2&lt;/span&gt;</code></pre> 
                </div> 

                <div class="javascript"> 
                    <h4>Javascript</h4> 
                    <pre><code class="javascript">$(".bar").peity("bar");</code></pre> 
                </div> 
            </div> 

            <h2>Events</h2> 

            <p>Peity adds a "change" event trigger to your graph elements, so if you
                update their data your can regenerate one or more charts by triggering
                <code>change()</code> on them.</p> 

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

            <div class="example pie-events"> 
                <div class="html"> 
                    <h4>HTML</h4> 

                    <pre><code class="html">&lt;ul&gt;
  &lt;li&gt;
    &lt;span class="graph"&gt;&lt;/span&gt;
    &lt;select&gt;
      &lt;option value="0"&gt;0&lt;/option&gt;
      &lt;option value="1"&gt;1&lt;/option&gt;
      &lt;option value="2"&gt;2&lt;/option&gt;
      &lt;option value="3"&gt;3&lt;/option&gt;
      &lt;option value="4" selected&gt;4&lt;/option&gt;
      &lt;option value="5"&gt;5&lt;/option&gt;
    &lt;/select&gt;
  &lt;/li&gt;
  &lt;li&gt;
    &lt;span class="graph"&gt;&lt;/span&gt;
    &lt;select&gt;
      &lt;option value="0"&gt;0&lt;/option&gt;
      &lt;option value="1" selected&gt;1&lt;/option&gt;
      &lt;option value="2"&gt;2&lt;/option&gt;
      &lt;option value="3"&gt;3&lt;/option&gt;
      &lt;option value="4"&gt;4&lt;/option&gt;
      &lt;option value="5"&gt;5&lt;/option&gt;
    &lt;/select&gt;
  &lt;/li&gt;
  &lt;li&gt;
    &lt;span class="graph"&gt;&lt;/span&gt;
    &lt;select&gt;
      &lt;option value="0"&gt;0&lt;/option&gt;
      &lt;option value="1"&gt;1&lt;/option&gt;
      &lt;option value="2"&gt;2&lt;/option&gt;
      &lt;option value="3" selected&gt;3&lt;/option&gt;
      &lt;option value="4"&gt;4&lt;/option&gt;
      &lt;option value="5"&gt;5&lt;/option&gt;
    &lt;/select&gt;
  &lt;/li&gt;
&lt;/ul&gt;
 
&lt;p id="notice"&gt;Nothing's happened yet.&lt;/p&gt;</code></pre> 
                </div> 

                <div class="javascript"> 
                    <h4>Javascript</h4> 

                    <pre><code class="javascript">$("select").change(function(){
  $(this)
    .siblings("span.graph")
    .text($(this).val() + "/" + 5).change();
}).change();
 
var chartUpdate = function(event, value, max) {
  $("#notice").text(
    "Chart updated: " + value + "/" + max
  );
};
 
$("span.graph")
  .peity("pie")
  .bind("chart:changed", chartUpdate);</code></pre> 
                </div> 
            </div> 

            <h2>Custom Chart Types</h2> 

            <p>You can easily add your own custom chart type by registering it with
                Peity with name, defaults and draw function.</p> 

            <pre><code class="javascript">$.fn.peity.add("custom", {
    colour: "#FFCC00"
  }, function() {
    ...
  }
)</code></pre> 

            <h2>Defaults</h2> 

            <p>Defaults can be overridden globally like so:</p> 

            <pre><code class="javascript">$.fn.peity.defaults.pie = {
  colours: ["#FFF4DD", "#FF9900"],
  delimeter: "/",
  radius: 16
};
 
$.fn.peity.defaults.line = {
  colour: "#c6d9fd",
  strokeColour: "#4d89f9",
  strokeWidth: 1,
  delimeter: ",",
  height: 16,
  max: null,
  width: 32
};
 
$.fn.peity.defaults.bar = {
  colour: "#4D89F9",
  delimeter: ",",
  height: 16,
  max: null,
  width: 32
};</code></pre> 
        </div> 
        <script type="text/javascript"> 
            var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
            document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
        </script> 
        <script type="text/javascript"> 
            try {
                var pageTracker = _gat._getTracker("UA-117680-14");
                pageTracker._trackPageview();
            } catch(err) {}</script> 

    </body>
</html>
