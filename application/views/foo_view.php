<!DOCTYPE html> 
<html>
    <head>
        <title>MaseBook</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <style type="text/css">

        </style>

        <script type="text/javascript"> 
            $(function () {
                initialize_map_data_tabs();
            });
            
            function initialize_map_data_tabs() {
                // Initial select
                $('#map_data_tabs li:first').addClass('tab_selected');
                
                // Click handler.
                $('#map_data_tabs li').click(function (event_object) {
                    console.log(event_object);
                });
            }
        </script> 
    </head>

    <body>
        <ul id="map_data_tabs">
            <li assoc_div="#map_tab">Map</li>
            <li assoc_div="#group_data_tab">Group Data</li>
            <li assoc_div="#plan_data_tab">Plan Data</li>
        </ul>
    </body>
</html>
