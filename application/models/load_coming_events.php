<?php

class Load_coming_events extends CI_Model
{

    function load_events($selected_groups)
    {
        if (!$selected_groups[0])
        {
            $this->on_nothing_selected();
        } else if ($selected_groups[0] == 'current_location')
        {
            $this->on_current_location_selected();
        } else if ($selected_groups[0] == 'friends')
        {
            $this->on_friends_selected();
        } else if ($selected_groups[0] == 'school')
        {
            $this->on_school_selected();
        } else
        {
            $this->on_groups_selected($selected_groups);
        }
    }

    function on_nothing_selected()
    {
        echo "<hr/>This panel will populate with <font style=\"font-weight:bold;color:navy;\">upcoming events</font> ";
        echo "based on the <font style=\"color:navy; font-weight:bold;\">groups</font> selected<br/><hr/>";
    }

    function on_current_location_selected()
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT places.name, places.id,
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
                    FROM events 
                    JOIN event_invitees ON event_invitees.event_id=events.id
                    JOIN plans ON (plans.event_id=events.id AND events.privacy='open') OR (event_invitees.user_id=plans.user_id AND plans.event_id=event_invitees.event_id)
                    JOIN places ON events.place_id=places.id
                    WHERE events.date>NOW() AND distance<15
                    ORDER BY distance ASC";

        $result = $this->db->query($query);

        $place_array = array();
        $place_id_array = array();
        foreach ($result->result() as $place)
        {
            if (!isset($place_array[$place->id]))
            {
                $place_array[$place->id] = $place->name;
            }
            $place_id_array[] = $place->id;
        }

        $this->display_upcoming_event_tabs($place_array, $place_id_array);
    }

    function on_friends_selected()
    {
        $this->load->model('load_locations');
        $friend_ids = $this->load_locations->get_friend_ids(); // get an array of friend ids
        
        $query = "SELECT places.name, places.id 
                  FROM events
                  JOIN event_invitees ON event_invitees.event_id=events.id
                  JOIN plans ON (events.id=plans.event_id AND events.privacy='open') OR (event_invitees.user_id=plans.user_id AND plans.event_id=event_invitees.event_id)
                  JOIN places ON events.place_id=places.id
                  WHERE events.date>NOW()";
        
    }

    function on_school_selected()
    {
        
    }

    function on_groups_selected($selected_groups)
    {
        
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
