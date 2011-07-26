<html>
    <head>

        <script src="http://maps.googleapis.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript">
            $(function () {
                location_data();
            });
            
            function location_data() {
    
                if (navigator.geolocation) 
                {
                    navigator.geolocation.getCurrentPosition
                    ( 
                    function (position) 
                    {  
                        myLatitude=position.coords.latitude;
                        myLongitude=position.coords.longitude;
                
                        //                        // Update the user's profile with the new information.
                        //                        $.get('/home/update_user_location', {
                        //                            latitude: myLatitude,
                        //                            longitude: myLongitude,
                        //                            auto: true
                        //                        }, function (data) {
                        //                            if (data != 'success') {
                        //                                alert(data);
                        //                                map_user_position();
                        //                                show_data_container('#map_data');
                        //                            }
                        //                        });
                
                        //                        // Update the city name.
                        //                        update_current_city_name();
                
                        mapThisGoogle(position.coords.latitude, position.coords.longitude);
                    }, 
                    // next function is the error callback
                    function (error)
                    {
                        switch(error) {
                            case error.TIMEOUT:
                                alert ('Timeout');
                                break;
                            case error.POSITION_UNAVAILABLE:
                                alert ('Position unavailable');
                                break;
                            case error.PERMISSION_DENIED:
                                alert ('Permission denied');
                                break;
                            case error.UNKNOWN_ERROR:
                                alert ('Unknown error');
                                break;
                        }
                    });
                }
            }
            
            
            // places the google map
            function mapThisGoogle(latitude,longitude)
            {
                var myLatlng = new google.maps.LatLng(latitude,longitude);
        
                var myOptions = {
                    zoom: 14,
                    center: myLatlng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                };
        
                map = new google.maps.Map(document.getElementById("map"), myOptions);
            }
        </script>
    </head>

    <body>
        <div id="map" style="width: 200px; height: 200px;"></div>
    </body>
</html>