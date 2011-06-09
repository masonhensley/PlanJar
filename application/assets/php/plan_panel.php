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
        $('#plan_content label').inFieldLabels();
                
        // Set up the Google autocomplete.
        var defaultBounds = new google.maps.LatLngBounds(
        new google.maps.LatLng(-33.8902, 151.1759),
        new google.maps.LatLng(-33.8474, 151.2631)
    );

        var input = document.getElementById('plan_location');
        var options = {
            bounds: defaultBounds,
            types: ['establishment']
        };

        autocomplete = new google.maps.places.Autocomplete(input, options);
    }
</script>

<div id="plan_content">
    <form id="make_plan">
        Make a plan:
        <br/>
        <div class="in-field_block">
            <label for="plan_location">Where are you going?</label>
            <input type="text" id="plan_location" name="plan_location" class="textbox"/>
            <br/>
            <!--        <p>Start typing, and we'll try to guess what you're looking for.</p>
    <p>Can't find it? Just type in the name and keep going.</p>-->
        </div>

        <div style="width:50px; height:10px; float:left"></div>

        <div class="in-field_block">
            <label for="plan_description">What are you doing?</label>
            <input type="text" id="plan_description" class="textbox"/>
            <br/>
            <p>Start typing what you plan to do.</p>
        </div>

        <select name="day" style="float:right">
            <option value="" selected="selected">What day?</option>
            <option value="0">Today - <?php echo(date('j')); ?></option>
            <option value="1">Tom - <?php echo(date('j') + 1); ?></option>
            <?php
            $days = array('Sun', 'Mon', 'Tues', 'Weds', 'Thurs', 'Fri', 'Sat');
            for ($i = 2; $i < 7; ++$i)
            {
                ?>
                <option value="<?php echo($i); ?>">
                    <?php
                    // Format the displayed day name (e.g. Tue - 9).
                    $day_name = $days[(date('w') + $i) % 7];
                    $day_name .= ' - ' . (date('j') + $i);
                    echo($day_name);
                    ?>
                </option>
                <?php
            }
            ?>
        </select>

        <input type="submit" value="Make a plan"/>
    </form>
</div>