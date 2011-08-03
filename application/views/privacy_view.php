<!DOCTYPE html>

<html>
    <head>
        <link rel=stylesheet href="/application/assets/css/privacy.css" type="text/css" />

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>

        <script type="text/javascript">
            $(function() {
                $.ajax({
                    type: "GET",
                    dataType: "jsonp",
                    cache: false,
                    url: "https://api.instagram.com/v1/media/search?lat=[LAT]&lng=[LNG]&distance=[DST]
?client_id=[ClientID]&access_token=[CODE]",
                    success: function(data) {
                        for (var i = 0; i < 6; i++) {
                            $(".instagram").append("<div class='instagram-placeholder'><a target='_blank' href='" +
                                data.data[i].link +"'><img class='instagram-image' src='" +
                                data.data[i].images.thumbnail.url +
                                "' /></a></div>");
                        }
                    }
                });
            });
        </script>
    </head>

    <body>

        <div class="instagram"></div>

    </body>
</html>
