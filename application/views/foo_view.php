<!DOCTYPE html> 
<html>
    <head>
        <title>MaseBook</title>

        <script type="text/javascript" src="/application/assets/js/jquery-1.6.1.min.js"></script>
        <script type="text/javascript" src="/application/assets/js/jquery-ui-1.8.13.min.js"></script>

        <style type="text/css">
            #map_data_tabs {
                float: left;
                list-style: none;
            }
            div.map_data_content {
                display: none;
                width: 500px;
                height: 400px;
                border: 2px solid;
                border-color: black;
            }
        </style>

        <script type="text/javascript"> 
            $(function () {
                initialize_map_data_tabs();
            });
            
            function initialize_map_data_tabs() {
                // Hide all content.
                $('#')
                
                // Initial select
                $('#map_data_tabs li:first').addClass('tab_selected');
                
                // Click handler.
                $('#map_data_tabs li').click(function (event_object) {
                    if ($(this).hasClass('tab_selected')) {
                        $(this).removeClass('tab_selected');
                        $('div.map_data_content').hide('fast');
                    } else {
                        $(this).addClass('tab_selected');
                        $($(this).attr('assoc_div')).show('fast');
                    }
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

        <div id="map_tab" class="map_data_content">stuff</div>

        <div id="group_data_tab" class="map_data_content">other stuff</div>

        <div id="plan_data_tab" class="map_data_content">even more stuff</div>
    </body>
</html>
