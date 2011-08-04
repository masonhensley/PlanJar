<html lang="en">  
<head>  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <!-- CSS -->
    <link rel=stylesheet href="/application/assets/css/photos.css" type="text/css" />
    <link href='http://fonts.googleapis.com/css?family=Ubuntu' rel='stylesheet' type='text/css'>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>

        <script type="text/javascript">
            $(function() {
                $.ajax({
                    type: "GET",
                    dataType: "jsonp",
                    cache: false,
                    url: "https://api.instagram.com/v1/media/popular?client_id=93ccf3a9f7924a6b8e33cc5234cebc50",
                    success: function(data) {
                        for (var i = 0; i < 42; i++) {
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

  
</body>  
</html>  




