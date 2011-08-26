<?php

class Load_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function display_profile($user, $format, $force_accept_button)
    {
        $school_query = "SELECT school FROM school_data WHERE id=$user->school_id";
        $result = $this->db->query($school_query);
        $row = $result->row();
        $groups_joined = $this->get_groups_joined($user); // array containing group information
        $groups_following = $this->get_groups_following($user); // array containing group information
        $locations_data = $this->get_location_stats($user); // string containing the location statistics
        $birthday = $user->birthday;
        $user_age = $this->calculate_age($birthday);
        $number_following = $this->get_number_following($user->id);
        $number_followers = $this->get_number_followers($user->id);

        if ($force_accept_button)
        {
            ?>
            <div class="add_following">follow</div>
            <?php
        }
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
                <?php $this->insert_profile_picture(80);
                ?>
            </div>
            <div class="profile_user_information">
                <br/><font style="font-size:20px;"><font style="font-weight:bold;"><?php echo $user->first_name . " " . $user->last_name; ?></font><br/>
                <font style="color:darkgray;"><?php echo $row->school . " "; ?>('<?php echo substr($user->grad_year, -2); ?>)</font></font><br/>
            </div>
        </div>
        <hr/>
        <div class="profile_body">
            <div class="profile_body_text"><?php ?><font style="color:darkgray;">sex</font><font style="font-weight:bold;"><?php echo " " . $user->sex; ?></font>&nbsp;&nbsp;&nbsp;
                <font style="color:darkgray;">age</font><font style="font-weight:bold;"><?php echo " " . $user_age; ?></font>&nbsp;&nbsp;&nbsp;
                <font style="color:darkgray;">followers</font><font style="font-weight:bold;"><?php echo " " . $number_followers; ?></font>&nbsp;&nbsp;&nbsp;
                <font style="color:darkgray;">following</font><font style="font-weight:bold;"><?php echo " " . $number_following; ?></font>&nbsp;&nbsp;&nbsp;
                <br/>
                <br/>
                <font style="color:darkgray; float:left;">box</font><?php
        echo " ";
                ?><div id="my_box_spinner"></div><div class="my_box" id="my_box"><?php
        if (isset($user->box) && trim($user->box) != "")
        {
                    ?><font style="color:gray;"><?php echo $user->box; ?></font><?php
        } else
        {
                    ?><font style="font-style: italic;">Nothing to show</font><?php
        }
                ?>
                </div>
                <br/>
                <?php
                if ($format == 'profile_edit')
                {
                    ?>
                    <font style="color:darkgray;">
                    <textarea id="box_text_area" name="comments" cols="30" rows="4" maxlength="96"><?php echo $user->box ?></textarea>
                    </font>
                    <div class="edit_box">
                        edit box
                    </div>
                    <div class="update_box">
                        update
                    </div>
                    <?php
                }
                ?>
                <hr/><br/><font style="font-size:23px; margin-left:195px;">Groups</font><br/><font style="font-size:20px;">Joined</font><br/><?php
        $group_count = count($groups_joined);
        if ($group_count > 0)
        {
            $index = 0;

            foreach ($groups_joined as $group)
            {
                        ?><font style="color:green; font-size: 16px;"><?php echo $group; ?></font><?php
                if ($index + 1 != $group_count)
                {
                            ?><font style="color:black;"><?php echo ", "; ?></font><?php
                }
                $index++;
            }
        } else
        {
                    ?><font style="font-style:italic;">Nothing to show</font><?php
        }

        // Code to display groups following
                ?><br/><br/><font style="font-size:20px;">Following</font><br/><?php
        $index = 0;
        $following_count = count($groups_following);
        if ($following_count > 0)
        {
            $index = 0;
            foreach ($groups_following as $group)
            {
                        ?><font style="color:purple; font-size:16px;"><?php echo $group; ?></font><?php
                if ($index + 1 != $following_count)
                {
                            ?><font style="color:black;"><?php echo ", "; ?></font><?php
                }
                $index++;
            }
        } else
        {
                    ?><font style="font-style:italic;">Nothing to show</font><?php
        }
                ?><br/><hr/><br/><?php
        echo $locations_data;
                ?>
            </div>
        </div>
        <?php
    }

    function get_number_following($user_id)
    {
        $query = "SELECT follow_id FROM friend_relationships WHERE user_id=$user_id";
        $result = $this->db->query($query);
        $number_following = $result->num_rows();
        return $number_following;
    }

    function get_number_followers($user_id)
    {
        $query = "SELECT user_id FROM friend_relationships WHERE follow_id=$user_id";
        $result = $this->db->query($query);
        $number_followers = $result->num_rows();
        return $number_followers;
    }

    function get_groups_joined($user)
    {
        $query = "SELECT groups.name, group_relationships.id 
        FROM group_relationships LEFT JOIN groups ON groups.id=group_relationships.group_id 
        WHERE group_relationships.user_joined_id=$user->id";
        $result = $this->db->query($query);
        $groups_joined = array();
        foreach ($result->result() as $group)
        {
            $groups_joined[] = $group->name;
        }
        return $groups_joined;
    }

    function get_groups_following($user)
    {
        $query = "SELECT groups.name, group_relationships.id 
        FROM group_relationships JOIN groups ON groups.id=group_relationships.group_id 
        WHERE group_relationships.user_following_id=$user->id";
        $result = $this->db->query($query);
        $groups_following = array();
        foreach ($result->result() as $group)
        {
            $groups_following[] = $group->name;
        }
        return $groups_following;
    }

    function get_location_stats($user)
    {
        $query =
                "SELECT places.name, events.place_id, events.date
       FROM plans
       LEFT JOIN events ON plans.event_id=events.id
       LEFT JOIN places ON places.id=events.place_id
       WHERE plans.user_id=$user->id AND events.date<NOW()
       ORDER BY events.date DESC LIMIT 0, 20"; // this query pulls all plans a user has made before the current timestamp

        $result = $this->db->query($query);

        $recent_locations = array(); // variables to keep track of locations
        $most_visited_locations = array();

        // make trackers!
        $recent_tracker = 0;
        foreach ($result->result() as $place)
        {
            // recent locations limited to 10
            // checks if it's already there, less than 5, and not null
            if (!in_array($place->name, $recent_locations) && $recent_tracker < 5 && $place->name && count($recent_locations) < 11)
            {
                $recent_tracker++;
                $recent_locations[] = $place->name;
            }
            if ($place->name && count($most_visited_locations < 11)) // most visited locations is limited to 10
            {
                $most_visited_locations[] = $place->name;
            }
        }
        if (count($most_visited_locations) > 0)
        {
            $most_visited_locations = array_count_values($most_visited_locations);
            asort($most_visited_locations);
            $most_visited_locations = array_reverse($most_visited_locations, TRUE);
        }

        ob_start();
        ?><font style="font-size:23px; margin-left:200px;">Places</font><br/>
        <font style="font-size:18px;">Recently visited</font><br/><?php
        $recent_locations_count = count($recent_locations);
        if ($recent_locations_count > 0)
        {
            $index = 0;
            foreach ($recent_locations as $location)
            {
                ?><font style="color:navy;"><?php echo $location; ?></font><?php
                if ($index + 1 != $recent_locations_count)
                {
                    ?><font style="color:black;"><?php echo ", "; ?></font><?php
                }
                $index++;
            }
        } else
        {
            ?><font style="font-style:italic;">Nothing to show</font><?php
        }
        ?><br/><br/><font style="font-size:18px;">Most visited</font><br/><?php
        $most_visited_count = count($most_visited_locations);
        if ($most_visited_count > 0)
        {
            $index = 0;
            foreach ($most_visited_locations as $location => $count)
            {
                ?><font style="color:navy;"><?php echo $location; ?></font><?php
                if ($index + 1 != $most_visited_count)
                {
                    ?><font style="color:black;"><?php echo ", "; ?></font><?php
                }
                $index++;
            }
        } else
        {
            ?><font style="font-style:italic;">Nothing to show</font><?php
        }
        return ob_get_clean();
    }

    function insert_profile_picture($dim)
    {
        $logo_text = "logo_" . rand(1, 25) . ".png";
        ?>
        <img src="/application/assets/images/logos/<?php echo $logo_text; ?>" style="width:<?php echo $dim; ?>px;height:<?php echo $dim; ?>px;"/>
        <?php
    }

    function calculate_age($birthday)
    {
        list($year, $month, $day) = explode("-", $birthday);
        $year_diff = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff = date("d") - $day;
        if ($month_diff < 0)
            $year_diff--;
        elseif (($month_diff == 0) && ($day_diff < 0))
            $year_diff--;
        return $year_diff;
    }

}