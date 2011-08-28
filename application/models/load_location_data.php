<?php

class Load_location_data extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function _display_location_info($place_id, $day, $selected_groups, $back_to_plan, $back_to_groups, $back_to_search)
    {
        if (!$day)  // set the day correctly if null
        {
            $day = 0;
        }
        $date = new DateTime();
        $sql_date = $date->add(new DateInterval('P' . $day . 'D')); // date to be used in sql queries
        $sql_date = $sql_date->format('Y-m-d');

// setup display data
        $new_date = new DateTime();
        $big_display_day = $new_date->add(new DateInterval('P' . $day . 'D'));
        $day_display['big_day'] = $big_display_day->format('D');
        $day_display['big_day_date'] = $big_display_day->format('j');

        $place_info = $this->get_place_info($place_id); // selects the name, lat, lon, category, and distance of the location
        $place_data_array = $this->get_place_data($place_id, $sql_date, $place_info); // this will be returned to populate graphs
        $place_data_array['friends_attending'] = $this->get_number_friends_attending($place_id, $sql_date);
        $surrounding_day_array = $this->get_surrounding_day_info($place_id, $sql_date); // this array has info for the bar graph that shows # people going out by day

        $graph_return_data = array(
            'percent_female' => $place_data_array['percent_female'] * 100,
            'percent_male' => $place_data_array['percent_male'] * 100,
            'plan_dates' => $surrounding_day_array,
            'selected_date' => $sql_date,
            'place_id' => $place_id,
            'place_name' => $place_data_array['place_name']
        );

        $return_html = $this->get_place_html($place_info, $place_data_array, $sql_date, $day_display, $back_to_plan, $back_to_groups, $back_to_search);

        return array(
            'html' => $return_html,
            'graph_data' => $graph_return_data,
            'map_data' => array(array($place_info['name'], $place_info['latitude'], $place_info['longitude'], '')));
    }

    function get_place_info($place_id)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT places.name, places.latitude, places.longitude, places.id AS place_id,
                        ((ACOS(SIN($user->latitude * PI() / 180) * SIN(places.latitude * PI() / 180) 
                        + COS($user->latitude * PI() / 180) * COS(places.latitude * PI() / 180) * COS(($user->longitude - places.longitude) 
                        * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance 
                        FROM places
                        WHERE id=$place_id";
        $result = $this->db->query($query);
        $place_array = $result->row_array();

        return $place_array;
    }

    function get_number_friends_attending($place_id, $date)
    {
        $this->load->model('load_locations');
        $friend_ids = $this->load_locations->get_friend_ids();
// first find the number of friends attending
        $number_friends_query = "SELECT plans.user_id FROM plans 
            JOIN events ON plans.event_id=events.id AND events.date='$date' AND events.place_id=$place_id
            WHERE ";
        foreach ($friend_ids as $id)
        {
            $number_friends_query .= "plans.user_id=$id OR ";
        }
        $number_friends_query = substr($number_friends_query, 0, -4);

        $result = $this->db->query($number_friends_query);
        $number_of_friends = $result->num_rows();
        return $number_of_friends;
    }

    function get_place_data($place_id, $sql_date, $place_info)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT school_data.id, user_meta.sex FROM events 
            JOIN plans ON plans.event_id=events.id 
            JOIN user_meta ON user_meta.user_id=plans.user_id
            LEFT JOIN school_data ON school_data.id=user_meta.school_id AND school_data.id=$user->school_id
            WHERE events.place_id=$place_id AND events.date='$sql_date'";
        $result = $this->db->query($query); // pull school id, and gender from people attending
// data to be passed back
        $total_attending = $result->num_rows();
        $school_ids = array();
        $number_males = 0;
        $number_females = 0;
        $schoolmates_attending = 0;

        foreach ($result->result() as $result)
        {
            if ($result->sex == 'male')
            {
                $number_males++;
            } else if ($result->sex == 'female')
            {
                $number_females++;
            }
            $school_ids[] = $result->id;
        }
        $schoolmates_attending = count($school_ids);
        if ($total_attending != 0)
        {
            $percent_male = $number_males / $total_attending;
            $percent_female = $number_females / $total_attending;
        } else
        {
            $percent_male = 0;
            $percent_female = 0;
        }

        return array(
            'total_attending' => $total_attending,
            'schoolmates_attending' => $schoolmates_attending,
            'number_males' => $number_males,
            'number_females' => $number_females,
            'percent_male' => $percent_male,
            'percent_female' => $percent_female,
            'id' => $place_id,
            'place_name' => $place_info['name']
        );
    }

    function get_surrounding_day_info($place_id, $sql_date)
    {
// select all the plans to the location for the surrounding week (based off day selected)
        $query = "
        SELECT plans.user_id, events.id, events.date
        FROM events
        JOIN plans ON plans.event_id=events.id
        JOIN places ON places.id=$place_id
        WHERE events.date>=DATE_ADD('$sql_date', INTERVAL -2 DAY) 
        AND events.date<DATE_ADD('$sql_date', INTERVAL 4 DAY)
        AND events.place_id=$place_id
        ";

        $result = $this->db->query($query);

        $date_tracker = new DateTime($sql_date);
        $date_tracker->modify('-2 day');
        $plan_dates = array();

        for ($i = 0; $i < 7; $i++)
        {
            $plan_dates[$date_tracker->format('Y-m-d')] = 0;
            $date_tracker->modify('+1 day');
        }

        foreach ($result->result() as $plan)
        {
            $date = new DateTime($plan->date);
            $date = $date->format('Y-m-d');
            $plan_dates[$date]++;
        }

// Convert the plan dates array entries from <'Y-m-D': count> to <'date': 'Y-m-D', 'count': count>
        $keys = array_keys($plan_dates);
        $conversion_array = array();
        foreach ($keys as $key)
        {
            $conversion_array[] = array('date' => $key, 'count' => $plan_dates[$key]);
        }

        return $conversion_array;
    }

    function get_place_html($place_info, $place_data_array, $sql_date, $display_day, $back_to_plan, $back_to_groups, $back_to_search)
    {
        if (strlen($place_info['distance']) > 3)
        {
            $place_info['distance'] = substr($place_info['distance'], 0, 3);
        }

// get the percentages ready for display
        $place_data_array['percent_male'] = $place_data_array['percent_male'] * 100;
        $place_data_array['percent_female'] = $place_data_array['percent_female'] * 100;



// trim the percentages
        if (strlen($place_data_array['percent_male']) > 3)
        {
            $place_data_array['percent_male'] = substr($place_data_array['percent_male'], 0, 3);
            if (substr($place_data_array['percent_male'], -1) == ".")
            {
                $place_data_array['percent_male'] = substr($place_data_array['percent_male'], 0, -1);
            }
        }
        if (strlen($place_data_array['percent_female']) > 3)
        {
            $place_data_array['percent_female'] = substr($place_data_array['percent_female'], 0, 3);
            if (substr($place_data_array['percent_female'], -1) == ".")
            {
                $place_data_array['percent_female'] = substr($place_data_array['percent_female'], 0, -1);
            }
        }

// start output buffering
        ob_start();
        ?>
        <div class="data_box_top_bar">
            <div style="float:left; font-size:20px;">
                <font style="color:gray;font-size:25px;">Location:</font> <font style="color:#2D4853;font-size:25px;"><b><?php echo $place_info['name'] . "</font>"; ?></b></font>
            </div>
        </div>
        <div class="place_display_info">
            <font style="font-size:19px; font-weight:bold;">People Going</font><br/>
            <div class="attending_info">
                <font style="color:gray;">People</font><font style="font-weight:bold;"><?php echo " " . $place_data_array['total_attending']; ?></font>
                <font style="color:gray;">Males</font><font style="font-weight:bold;"><?php echo " " . $place_data_array['number_males']; ?></font>
                <font style="color:gray;">Females</font><font style="font-weight:bold;"><?php echo " " . $place_data_array['number_females']; ?></font>
                <br/>
                <font style="color:gray;">School</font><font style="font-weight:bold;"><?php echo " " . $place_data_array['schoolmates_attending']; ?></font>
            </div>
            <br/>
        </div>


        <div class="gender_breakdown">
            <font style="color:gray;position:absolute;left:31px;top:-10px;">location gender breakdown</font>
            <!-- boxes that show the color for males/females--> 
            <div class="girl_pink_square"></div>
            <div class="female_percent_display"><font style="font-size:11px;">
                <?php echo $place_data_array['percent_female'] . "% "; ?></font>female</div>

            <div class="boy_blue_square"></div>
            <div class="male_percent_display"><font style="font-size:11px;">
                <?php echo $place_data_array['percent_male'] . "% "; ?></font>male</div>
        </div>

        <div class="two_percent_wrapper"></div>

        <div style="position:absolute; color:gray;top:48px;right:88px;"># of plans made here by day</div>
        <div class="day_plan_graph"></div>

        <div style="position:absolute; width:300px; height:150px; bottom:0px; left:0px;"></div>

        <div class="display_date">
            <div style="font-size:100px; color: #7BC848; line-height:80px;overflow:hidden; display:inline-block;"><?php echo $display_day['big_day']; ?></div>
            <div style="font-size:100px; color:gray; line-height: 80px;overflow:hidden; display:inline-block;"><?php echo $display_day['big_day_date']; ?></div>
        </div>
        <div class="make_plan_here" >make a plan at this location</div>
        <div class="plans_made_here" place_id="<?php echo $place_info['place_id'];?>">view plans at this location</div>
        <div class="view_map" >View Map</div>

        <?php
        if ($back_to_plan)
        {
            ?>
            <div class="back_to_plan" style="position: absolute; top: 0px; right: 0px;">Back to plan</div>
            <?php
        } else if ($back_to_groups)
        {
            ?>
            <div class="back_to_groups" style="position: absolute; top: 0px; right: 0px;">Back to groups/networks</div>
            <?php
        } else if ($back_to_search)
        {
            ?>
            <div class="back_to_search" style="position: absolute; top: 0px; right: 0px;">Back to search</div>
            <?php
        }
        return ob_get_clean();
    }

}
?>
