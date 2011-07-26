<?php

class Load_coming_events extends CI_Model
{

    function load_events()
    {
        $query = "SELECT places.id, places.name, 
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
                    FROM events
                    LEFT JOIN event_invitees ON event_invittes.event_id=events.id
                    JOIN plans ON (plans.event_id=events.id AND events.privacy='open') OR (plans.user_id=event_invitees.user_id)
                    JOIN places ON places.id=events.place_id
                    WHERE events.date>NOW()";
        
        var_dump($query);
    }

    function display_upcoming_event_tabs($place_array, $place_id_array)
    {
        if (count($place_id_array) > 0)
        {
            $place_id_array = array_count_values($place_id_array);
            asort($place_id_array);
            $place_id_array = array_reverse($place_id_array, TRUE);
            $number_tracker = 1;
            foreach ($place_id_array as $place_id => $count)
            {
                ?>
                <div class="location_tab" place_id="<?php echo $place_id; ?>">
                    <div class="number">
                        <?php echo $number_tracker; ?>
                    </div>
                    <font style="font-weight:bold;"> <?php echo $place_array[$place_id]; ?></font><br/>
                    <font style="font-weight:bold;color:lightgray; font-size:13px;"><?php echo $count; ?> people in selected tab(s) are attending</font><br/>
                    <?php echo "id: " . $place_id; ?>
                </div>
                <?php
            }
        } else
        {
            ?>
            <div class ="no_places_to_show">
                <br/>Nothing to show
            </div>
            <?php
        }
    }

}
?>
