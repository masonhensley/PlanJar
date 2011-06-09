<html>
    <head>
        <title>MaseBook</title>
        
        <script type="text/javascript" src="/application/assets/js/jquery.peity.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript">
            // Run when the DOM loads.
            $(function(
) {$(".radius span").each(function() {
  var elem = $(this);
  var radius = elem.attr("class").match(/\d+/) * 4;

  elem.peity("pie", {
    colours: ["#C6D9FD", "#4D89F9"],
    radius: radius
  });
});

$(".line").peity("line");
                
            });
        </script>
    </head>

    <body>


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


<span class="line">5,3,9,6,5,9,7,3,5,2</span>



    </body>
</html>