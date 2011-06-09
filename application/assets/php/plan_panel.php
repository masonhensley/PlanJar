<html>
    <head>
        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/home_functions.js"></script>
        <script src="http://maps.google.com/maps/api/js?libraries=places&sensor=false" type="text/javascript"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.infieldlabel.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery.simplemodal.1.4.1.min.js"></script>


        <link rel=stylesheet href="/application/assets/css/home.css" type="text/css" />
        <link type="text/css" rel=stylesheet href="/application/assets/css/eggplant/theme.css"/>

        <script type="text/javascript">
            $(function() {
                // Initialize the in-field labels.
                $('label').inFieldLabels();
                
                // Set up the Google autocomplete.
                var defaultBounds = new google.maps.LatLngBounds(
                new google.maps.LatLng(-33.8902, 151.1759),
                new google.maps.LatLng(-33.8474, 151.2631));

                $('#foo').change(function() {
                    
                });
            });
        </script>
    </head>

    <body>
        <form id="make_plan">
            <div class="in-field_block">
                <label for="plan_location">Where are you going?</label>
                <input type="text" id="plan_location" name="plan_location" class="textbox"/>
                <br/>
                <!--        <p>Start typing, and we'll try to guess what you're looking for.</p>
        <p>Can't find it? Just type in the name and keep going.</p>-->
            </div>
        </form>
    </div>
</body>
</html>