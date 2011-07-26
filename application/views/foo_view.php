<html>
    <head>
        <script src="http://maps.googleapis.com/maps/api/js?sensor=false" type="text/javascript"></script>
        <script type="text/javascript">
            var myLatlng = new google.maps.LatLng(30, -90);

            var myOptions = {
                zoom: 14,
                center: myLatlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };

            map = new google.maps.Map(document.getElementById("map"), myOptions);
        </script>
    </head>

    <body>
        <div id="map" style="width: 200px; height: 200px;"></div>
    </body>
</html>