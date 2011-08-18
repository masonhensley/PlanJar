<html lang="en">  
    <head>  
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <script type="text/javascript">var _sf_startpt=(new Date()).getTime()</script>

        <!-- CSS -->
        <link rel=stylesheet href="/application/assets/css/photos.css" type="text/css" />
        <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.2.min.js"></script>

        <script type="text/javascript">
            $(function() {
                $.ajax({
                    type: "GET",
                    dataType: "jsonp",
                    cache: false,
                    url: "https://api.instagram.com/v1/media/search",
                    data: {
                        lat: 36.14934,
                        lng: -86.80554,
                        distance: 5000,
                        client_id: '93ccf3a9f7924a6b8e33cc5234cebc50'
                    },
                    success: function(data) {
                        for (var i = 0; i < 30; i++) {
                            $(".instagram").append("<div class='instagram-placeholder'><a target='_blank' href='" +
                                data.data[i].link +"'><img class='instagram-image' src='" +
                                data.data[i].images.thumbnail.url +
                                "' /></a></div>");
                        }
                    }
                });
            });
        </script>


        <title>Photos</title>  

        <!--[if IE]>  
            <style>  
                .arrow { top: 100%; }  
            </style>  
        <![endif]-->  
    
    <!-- Mixpanel --><script type="text/javascript">var mpq=[];mpq.push(["init","ccd5fd6c9626dca4f5a3b019fc6c7ff4"]);(function(){var a=document.createElement("script");a.type="text/javascript";a.async=true;a.src=(document.location.protocol==="https:"?"https:":"http:")+"//api.mixpanel.com/site_media/js/api/mixpanel.js";var b=document.getElementsByTagName("script")[0];b.parentNode.insertBefore(a,b)})();</script><!-- End Mixpanel -->

    <!-- Google Analytics -->
        <script type="text/javascript">

              var _gaq = _gaq || [];
              _gaq.push(['_setAccount', 'UA-23115103-4']);
              _gaq.push(['_setDomainName', '.planjar.com']);
              _gaq.push(['_trackPageview']);
            
              (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
              })();
            
        </script>
    
    </head>  
    <body>

        <div class ="top_panel">
            <div class = "inside_top_panel">
                <img src='/application/assets/images/pj_logo_white_text.png' style="float: left; margin-left:30px; height:80%; position:relative; top:5px;"/>
                <div class="top_links">
                    <a href="/dashboard/profile" id="profile_link"><div class ="top_right_link_outer"><div class="top_right_link_inner">Profile</div></div></a>
                </div>  
                <div id="container"> 

                    <h2> Photos Around You <span class="arrow"></span> </h2>

                    <div id="main">  

                        https://api.instagram.com/v1/media/search?lat=36.14934&lng=-86.80554&distance=2500

                        <br>

                        https://api.instagram.com/v1/media/popular?client_id=93ccf3a9f7924a6b8e33cc5234cebc50

                        <div class="instagram"></div>



                    </div>

                </div> 
            </div>
        
        <script type="text/javascript">
            var _sf_async_config={uid:27655,domain:"testing.pagodabox.com"};
            (function(){
              function loadChartbeat() {
                window._sf_endpt=(new Date()).getTime();
                var e = document.createElement('script');
                e.setAttribute('language', 'javascript');
                e.setAttribute('type', 'text/javascript');
                e.setAttribute('src',
                   (("https:" == document.location.protocol) ? "https://a248.e.akamai.net/chartbeat.download.akamai.com/102508/" : "http://static.chartbeat.com/") +
                   "js/chartbeat.js");
                document.body.appendChild(e);
              }
              var oldonload = window.onload;
              window.onload = (typeof window.onload != 'function') ?
                 loadChartbeat : function() { oldonload(); loadChartbeat(); };
            })();

        </script>

    </body>  
</html>  




