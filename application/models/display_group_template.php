<?php

class Display_group_template extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function _display_group_info($selected_groups, $day, $school, $filter)  // being in this function ensures that $selected_groups is not NULL
    {
        if (!$day)
        {
            $day = 0; // reformat the day if it is null from the "today" tab being selected
        }
        $date = new DateTime();
        $sql_date = $date->add(new DateInterval('P' . $day . 'D')); // date to be used in sql queries
        $sql_date = $sql_date->format('Y-m-d'); // this date is sql friendly
        $format_type = ""; // this is used to distinguish between the different types of display formats
        // determine format type and retrieve data for selected group(s) and store it in $data_array
        if ($selected_groups[0] == 'current_location')
        {
            $data_array = $this->get_current_location_data($sql_date, $filter); // get information for current location
            $format_type .= "current_location";
        } else if ($selected_groups[0] == 'friends')
        {
            $data_array = $this->get_friend_data($sql_date, $filter); // get information for friends
            $format_type .= "friends";
        } else if ($selected_groups[0] == 'school')
        {
            $data_array = $this->get_school_data($school, $sql_date, $filter);  // get information for school
            $format_type .= "school";
        } else // when groups are selected
        {
            $data_array = $this->get_selected_group_data($selected_groups, $sql_date, $filter);  // get information for groups
            $format_type .= "groups";
        }
        // return an array(2) that will be json encoded and sent to the browser for graph animation

        return array('html' => $this->get_group_template($format_type, $selected_groups, $day, $data_array),
            'data' => $data_array);
    }

    function get_friend_data($sql_date, $filter)
    {
        $this->load->model('load_locations');
        $user_ids = $this->load_locations->get_friend_ids(true); // get all the ids of your friends

        $return_array = array(); // data to be returned
        $friend_user_ids = array(); // this is needed to get the correct user ids for the filter
        $number_males = 0;
        $number_females = 0;
        $males_going_out = 0;
        $females_going_out = 0;
        $total_people = 0;

        if (count($user_ids) > 0)
        {
            // handle any filter that is put on the search results
            // right now this handles freshmen, sophomores, juniors, seniors, and alumni
            $filter_grad_year = $this->get_correct_grad_year($filter);
            if ($filter == 'alumni')
            {
                $query_filter = " AND user_meta.grad_year<$filter_grad_year";
            } else if ($filter_grad_year != 0)
            {
                $query_filter = " AND user_meta.grad_year='$filter_grad_year'
            ";
            } else
            {
                $query_filter = "";
            }

            $query = "SELECT sex, user_id FROM user_meta WHERE (";
            foreach ($user_ids as $friend_id)
            {
                $query .= "user_id=$friend_id OR ";
            }
            $query = substr($query, 0, -4);
            $query .= ")$query_filter"; // apply the filter
            $result = $this->db->query($query);

            $total_people = $result->num_rows();


            foreach ($result->result() as $person)
            {
                $friend_user_ids[] = $person->user_id;
                if ($person->sex == 'male')
                {
                    $number_males++;
                } else
                {
                    $number_females++;
                }
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        // query for number of girls and boys going out on the date selected
        $return_array = $this->get_percentages($return_array, $sql_date, $friend_user_ids, $total_people, $number_males, $number_females);
        // query for all the plans that people in the groups have made for the surrounding week
        $return_array = $this->get_surrounding_day_info($return_array, $friend_user_ids, $sql_date);

        return $return_array;
    }

    function get_school_data($school, $sql_date, $filter)
    {
        $user = $this->ion_auth->get_user();

        // handle any filter that is put on the search results
        // right now this handles freshmen, sophomores, juniors, seniors, and alumni
        $filter_grad_year = $this->get_correct_grad_year($filter);
        if ($filter == 'alumni')
        {
            $query_filter = " AND user_meta.grad_year<$filter_grad_year";
        } else if ($filter_grad_year != 0)
        {
            $query_filter = " AND user_meta.grad_year='$filter_grad_year'
            ";
        } else
        {
            $query_filter = "";
        }

        $query = "SELECT DISTINCT user_meta.user_id, user_meta.sex FROM user_meta 
                JOIN school_data ON school_data.id=user_meta.school_id
                JOIN plans ON user_meta.user_id = plans.user_id
                JOIN events ON plans.event_id = events.id AND events.date >= CURDATE()
                WHERE user_meta.school_id=$user->school_id$query_filter";

        $result = $this->db->query($query);

        // Data to be returned
        $return_array = array();
        $number_males = 0;
        $number_females = 0;
        $males_going_out = 0;
        $females_going_out = 0;
        $total_people = $result->num_rows();
        $user_ids = array();

        foreach ($result->result() as $person)
        {
            $user_ids[] = $person->user_id;
            if ($person->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        // query for number of girls and boys going out on the date selected 
        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females);
        // query for all the plans that people in the groups have made for the surrounding week
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids, $sql_date);

        return $return_array;
    }

    function get_selected_group_data($selected_groups, $sql_date, $filter)
    {
        // generate the appropriate query filter for class 
        $filter_grad_year = $this->get_correct_grad_year($filter);
        if ($filter == 'alumni')
        {
            $query_filter = " AND user_meta.grad_year<$filter_grad_year";
        } else if ($filter_grad_year != 0)
        {
            $query_filter = " AND user_meta.grad_year='$filter_grad_year'
            ";
        } else
        {
            $query_filter = "";
        }

        // first get all the ids of people in the groups
        $query = "SELECT DISTINCT user_meta.user_id, user_meta.sex FROM group_relationships
                    JOIN user_meta ON user_meta.user_id=group_relationships.user_joined_id$query_filter
                    JOIN plans ON user_meta.user_id = plans.user_id
                    JOIN events ON plans.event_id = events.id AND events.date >= CURDATE()
                    WHERE ";
        foreach ($selected_groups as $group_id)
        {
            $query .= "group_relationships.group_id=$group_id OR ";
        }
        $query = substr($query, 0, -4); // contains information for all the users in the selected groups
        $result = $this->db->query($query);

        $this->get_correct_grad_year($filter);

        // Data to be returned
        $return_array = array();
        $number_males = 0;
        $number_females = 0;
        $males_going_out = 0;
        $females_going_out = 0;
        $total_people = $result->num_rows();
        $user_ids = array();

        foreach ($result->result() as $person)
        {
            $user_ids[] = $person->user_id;
            if ($person->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        // query for number of girls and boys going out on the date selected
        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females);
        // query for all the plans that people in the groups have made for the surrounding week
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids, $sql_date);

        return $return_array;
    }

    function get_current_location_data($sql_date, $filter)
    {
        $user = $this->ion_auth->get_user();

        $filter_grad_year = $this->get_correct_grad_year($filter);
        if ($filter == 'alumni')
        {
            $query_filter = " WHERE user_meta.grad_year<$filter_grad_year";
        } else if ($filter_grad_year != 0)
        {
            $query_filter = " WHERE user_meta.grad_year='$filter_grad_year'
            ";
        } else
        {
            $query_filter = "";
        }

        $query = "SELECT DISTINCT user_meta.user_id, user_meta.sex,
                ((ACOS(SIN($user->latitude * PI() / 180) * SIN(user_meta.latitude * PI() / 180) 
                + COS($user->latitude * PI() / 180) * COS(user_meta.latitude * PI() / 180) * COS(($user->longitude - user_meta.longitude) 
                * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance 
                FROM user_meta
                JOIN plans ON user_meta.user_id = plans.user_id
                JOIN events ON plans.event_id = events.id AND events.date >= CURDATE()
                $query_filter
                HAVING distance<15";
        $result = $this->db->query($query);

        $this->get_correct_grad_year($filter);

        // data to be returned
        $return_array = array();
        $total_people = $result->num_rows();
        $number_males = 0;
        $number_females = 0;
        $user_ids = array();
        foreach ($result->result() as $person)
        {
            $user_ids[] = $person->user_id;
            if ($person->sex == 'male')
            {
                $number_males++;
            } else
            {
                $number_females++;
            }
        }

        $return_array['total_males'] = $number_males;
        $return_array['total_females'] = $number_females;

        $return_array = $this->get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females); // fill return array with percentage information
        $return_array = $this->get_surrounding_day_info($return_array, $user_ids, $sql_date);

        return $return_array;
    }

    function get_percentages($return_array, $sql_date, $user_ids, $total_people, $number_males, $number_females)
    {
        // query for number of girls and boys going out on the date selected
        $result = "";

        $males_going_out = 0;
        $females_going_out = 0;
        $id_tracker_array = array();

        if (count($user_ids) > 0)
        {
            $girl_boy_query = "SELECT user_meta.sex, user_meta.user_id FROM plans 
                            JOIN user_meta ON plans.user_id=user_meta.user_id
                            JOIN events ON events.id=plans.event_id AND events.date='$sql_date'
                            WHERE ";
            foreach ($user_ids as $id)
            {
                $girl_boy_query .= "plans.user_id=$id OR ";
            }
            $girl_boy_query = substr($girl_boy_query, 0, -4);
            $result = $this->db->query($girl_boy_query);

            foreach ($result->result() as $person)
            {
                if (!in_array($person->user_id, $id_tracker_array))
                {
                    if ($person->sex == 'male')
                    {
                        $males_going_out++;
                    } else
                    {
                        $females_going_out++;
                    }
                    $id_tracker_array[] = $person->user_id;
                }
            }
        }

        $return_array['males_going_out'] = $males_going_out;
        $return_array['females_going_out'] = $females_going_out;

        $percent_total_goingout = 0;
        $percent_males_goingout = 0;
        $percent_females_goingout = 0;

        if ($total_people != 0)
        {
            $percent_total_goingout = ($males_going_out + $females_going_out) / $total_people;
        }
        if ($number_males != 0)
        {
            $percent_males_goingout = $males_going_out / $number_males;
        }
        if ($number_females != 0)
        {
            $percent_females_goingout = $females_going_out / $number_females;
        }

        $return_array['percent_total_going_out'] = $percent_total_goingout * 100;
        $return_array['percent_males_going_out'] = $percent_males_goingout * 100;
        $return_array['percent_females_going_out'] = $percent_females_goingout * 100;
        $return_array['selected_date'] = $sql_date;

        return $return_array;
    }

    function get_surrounding_day_info($return_array, $user_ids, $sql_date)
    {

        // initialize the bar graph array to 0
        $plan_dates = array();

        $date_tracker = new DateTime($sql_date);
        $date_tracker->modify('-2 day');

        for ($i = 0; $i < 7; $i++)
        {
            $plan_dates[$date_tracker->format('Y-m-d')] = 0;
            $date_tracker->modify('+1 day');
        }

        if (count($user_ids) > 0)
        {
            // query for all the plans that people in the groups have made for the surrounding week
            $recent_plans_query = "SELECT events.date FROM plans 
                            JOIN user_meta ON plans.user_id=user_meta.user_id
                            JOIN events ON events.id=plans.event_id 
                            AND events.date>=DATE_ADD('$sql_date', INTERVAL -2 DAY) AND events.date<DATE_ADD('$sql_date', INTERVAL 4 DAY)
                            JOIN places ON places.id=events.place_id
                            WHERE ";
            foreach ($user_ids as $id)
            {
                $recent_plans_query .= "plans.user_id=$id OR ";
            }
            $recent_plans_query = substr($recent_plans_query, 0, -4);
            $recent_plans_query .= " ORDER BY date ASC";
            $result = $this->db->query($recent_plans_query);

            // construct the array for the bar graph
            foreach ($result->result() as $plan)
            {
                $date = new DateTime($plan->date);
                $date = $date->format('Y-m-d');
                $plan_dates[$date]++;
            }
        }

        // Convert the plan dates array entries from <'Y-m-D': count> to <'date': 'Y-m-D', 'count': count>
        $keys = array_keys($plan_dates);
        $conversion_array = array();
        foreach ($keys as $key)
        {
            $conversion_array[] = array('date' => $key, 'count' => $plan_dates[$key]);
        }

        $return_array['plan_dates'] = $conversion_array;
        return $return_array;
    }

    function get_correct_grad_year($filter)
    {
        $currentMonth = date("m", time());
        $currentYear = date("Y", time());
        $graduationYear = 0;

        if (7 < $currentMonth && $currentMonth < 12)
        {
            if ($filter == 'freshmen')
            {
                $graduationYear = $currentYear + 4;
            } else if ($filter == 'sophomores')
            {
                $graduationYear = $currentYear + 3;
            } else if ($filter == 'juniors')
            {
                $graduationYear = $currentYear + 2;
            } else if ($filter == 'seniors' || $filter == 'alumni') // use alumni here so you can check for dates after it
            {
                $graduationYear = $currentYear + 1;
            }
        } else
        {
            if ($filter == 'freshmen')
            {
                $graduationYear = $currentYear + 3;
            } else if ($filter == 'sophomores')
            {
                $graduationYear = $currentYear + 2;
            } else if ($filter == 'juniors')
            {
                $graduationYear = $currentYear + 1;
            } else if ($filter == 'seniors' || $filter == 'alumni') // use alumni here so you can check for dates after it
            {
                $graduationYear = $currentYear + 0;
            }
        }
        return $graduationYear;
    }

    function get_group_template($format_type, $selected_groups, $day, $data_array)
    {
        $top_display = ""; // this contains the text for the header
        $s = ""; // this will make anything plural that needs to be for groups selected
        $font_style = ""; // used to determine font based on groups selected

        if ($format_type == 'friends')
        {
            $top_display = "Friends"; // you can use data_array to find total number of friends
        } else if ($format_type == 'current_location')
        {
            $top_display .= "Current Location <font style=\"color:lightgray;\">(15 mile radius)</font>";
        } else if ($format_type == 'school')
        {
            $query_string = "SELECT school FROM school_data WHERE id = ?";
            $query = $this->db->query($query_string, array($this->ion_auth->get_user()->school_id));
            $top_display .= $query->row()->school;
        } else if ($format_type == 'groups')
        {
            $font_style = "groups";
            if (count($selected_groups) > 1)
            {
                $s = "s";
            }
            $this->load->model('load_locations');
            $group_names = $this->load_locations->get_group_names($selected_groups);
            foreach ($group_names as $group)
            {
                $top_display .= $group . ", ";
            }
            $top_display = substr($top_display, 0, -2);
        }

        // setup display data
        $date = new DateTime();
        $big_display_day = $date->add(new DateInterval('P' . $day . 'D'));
        $big_display_month = $big_display_day->format('j');
        $big_display_day = $big_display_day->format('D');

        // make the percentage readable
        $data_array = $this->make_percentages_readable($data_array);

        if ($font_style == 'groups')
        {
            $font_style = "<font style=\"font-weight:bold; color:orange;\">";
        } else
        {
            $font_style = "<font style=\"font-weight:bold; color:green;\">";
        }

        ob_start();
        ?>
        <div class="data_box_top_bar">
            <div style="float:left; font-size:25px;">
                <font style="color:gray;">
                <?php
                if ($format_type == 'groups')
                {
                    ?>
                    Group<?php echo $s; ?>:
                    <?php
                } else
                {
                    ?>
                    Network:
                    <?php
                }
                ?>

                </font> 
                <?php echo " $font_style" . $top_display . "</font>"; ?>
            </div>
        </div>
        <div style="position:absolute; top:48px; right:57px;color:gray;">
            # of plans group makes by day
        </div>
        <br/>
        <div class="group_graph_top_left" >
            <font style="color:gray;">Viewing</font>&nbsp;
            <select id="filter">
                <option value="everyone">Everyone</option>
                <option value="freshmen">Freshmen</option>
                <option value="sophomores">Sophomores</option>
                <option value="juniors">Juniors</option>
                <option value="seniors">Seniors</option>
                <option value="alumni">Alumni</option>
            </select>
            <div class="demographics">
                <font style="color:gray;">Active guys</font><font style="font-weight:bold;">
                <?php echo " " . $data_array['total_males']; ?></font>&nbsp;&nbsp;&nbsp;
                <br/>
                <font style="color:gray;">Active girls</font><font style="font-weight:bold;">
                <?php echo " " . $data_array['total_females']; ?></font>
            </div>
            <?php
            $total = $data_array['total_males'] + $data_array['total_females'];
            ?>
            <?php
            if ($format_type == 'groups' && count($selected_groups) == 1)
            {
                ?>
                <div id="view_group_list">See Members</div>
                <?php
            } else if ($format_type == 'friends')
            {
                ?>
                <a href="/dashboard/friends/" ><div id="friends_link">See Friends</div></a>
                <?php
            } else if ($format_type == 'school')
            {
                // get the school id and generate the correct link to group
                $query = "SELECT id FROM groups WHERE school_id=";
                $query.= $this->ion_auth->get_user()->school_id;
                $query .= " AND school_group=1";
                $result = $this->db->query($query);
                $result = $result->row();
                ?>    
                <a href="/dashboard/groups/<?php echo $result->id; ?>"><div id="groups_link">See School</div></a>
                <?php
            }
            ?>
        </div>
        <div class="group_graph_top_right">
        </div>

        <!-- <div class="group_graph_bottom_right"></div> -->
        <div class="day_display">
            <div style="font-size:100px; color: #7BC848; line-height:96px;overflow:hidden; display:inline-block;"><?php echo $big_display_day; ?></div>
            <div style="font-size:100px; color:gray; line-height: 96px;overflow:hidden; display:inline-block;"><?php echo $big_display_month; ?></div>
        </div>

        <div class="group_graph_bottom_left">

            <div class="percent_male_container">
                <div class="show_percent"style="display:inline-block;"><?php echo $data_array['percent_males_going_out'] . "% " ?></div>
                <font style="display:inline-block; color:gray;">of active guys are going out</font>
            </div>
            <div class="male_percent_container"></div>

            <div class="percent_female_container">
                <div class="show_percent" style="display:inline-block;"><?php echo $data_array['percent_females_going_out'] . "% " ?></div>
                <font style="color:gray;display:inline-block;">of active girls are going out</font>
            </div>
            <div class="female_percent_container"></div>

            <div class="show_percent_container">
                <div class="show_percent" style="display:inline-block;"><?php echo $data_array['percent_total_going_out'] . "% " ?></div>
                <font style="color:gray;display:inline-block;">of active users are going out</font>
            </div>
            <div class="total_percent_container"></div>

        </div>
        <?php
        return ob_get_clean();
    }

    function make_percentages_readable($data_array)
    {
        if (strlen($data_array['percent_total_going_out']) > 3)
        {
            $data_array['percent_total_going_out'] = substr($data_array['percent_total_going_out'], 0, 3);
            if (substr($data_array['percent_total_going_out'], -1) == ".")
            {
                $data_array['percent_total_going_out'] = substr($data_array['percent_total_going_out'], 0, -1);
            }
        }
        if (strlen($data_array['percent_males_going_out']) > 3)
        {
            $data_array['percent_males_going_out'] = substr($data_array['percent_males_going_out'], 0, 3);
            if (substr($data_array['percent_males_going_out'], -1) == ".")
            {
                $data_array['percent_males_going_out'] = substr($data_array['percent_males_going_out'], 0, -1);
            }
        }
        if (strlen($data_array['percent_females_going_out']) > 3)
        {
            $data_array['percent_females_going_out'] = substr($data_array['percent_females_going_out'], 0, 3);
            if (substr($data_array['percent_females_going_out'], -1) == ".")
            {
                $data_array['percent_females_going_out'] = substr($data_array['percent_females_going_out'], 0, -1);
            }
        }
        return $data_array;
    }

}
?>
