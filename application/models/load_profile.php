<?php

class Load_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function display_profile($user)
    {
        $school_query = "SELECT school FROM school_data WHERE id=$user->school_id";
        $result = $this->db->query($school_query);
        $row = $result->row();
        $groups_joined = $this->get_groups_joined($user); // array containing group information
        $groups_following = $this->get_groups_following($user); // array containing group information
        $locations_data = $this->get_location_stats($user); // string containing the location statistics
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
                <?php $this->insert_profile_picture(); ?>
            </div>
            <div class="profile_user_information"><?php
        $year_display = substr($user->grad_year, -2);
        echo "<br/>" .$user->first_name . " " . $user->last_name . "<br/>";
        echo $row->school . " ('" . $year_display . ")<br/>";
        ?>
            </div>
        </div>
        <div class="profile_body">
            <div class="profile_body_text"><?php
        // Code to display groups joined
        if (count($groups_following) > 0 || count($groups_joined))
        {
            $groups_joined_text = "<font style=\"font-size:20px;\">Groups</font><br/>";
            if (count($groups_joined > 0))
            {
                $groups_joined_text .= "Joined: ";
                foreach ($groups_joined as $group)
                {
                    $groups_joined_text .= "<font style=\"color:purple;\">" . $group . "</font>, ";
                }
                $groups_joined_text = substr($groups_joined_text, 0, -2);
                $groups_joined_text .= "<br/>";
            }
            echo $groups_joined_text;

            // Code to display groups following
            $groups_following_text = "";
            if (count($groups_following) > 0)
            {
                $groups_following_text .= "Following: ";
                foreach ($groups_following as $group)
                {
                    $groups_following_text .= "<font style=\"color:green;\">" . $group . "</font>, ";
                }
                $groups_following_text = substr($groups_following_text, 0, -2);
                $groups_following_text .= "<br/><br/>";
            }
            echo $groups_following_text;
        }

        echo $locations_data;
        ?>
            </div>
        </div>

        <?php
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
        FROM group_relationships LEFT JOIN groups ON groups.id=group_relationships.group_id 
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
        $query = "SELECT places.name, plans.place_id, plans.date FROM plans 
            LEFT JOIN places ON places.id=plans.place_id WHERE plans.user_id=$user->id 
                AND plans.date<NOW()
                ORDER BY plans.date DESC LIMIT 0, 5";
        $result = $this->db->query($query);

        $recent_locations = array(); // variables to keep track of locations
        $most_visited_locations = array();

        // make trackers!
        $recent_tracker = 0;
        foreach ($result->result() as $place)
        {
            if (!in_array($place->name, $recent_locations) && $recent_tracker < 5)
            {
                $recent_tracker++;
                $recent_locations[] = $place->name;
            }
            $most_visited_locations[] = $place->name;
        }

        $recent_locations_text = "";
        if (count($recent_locations) > 0)
        {
            $recent_locations_text = "Recently visited:<br/>";
            foreach ($recent_locations as $location)
            {
                $recent_locations_text .= "<font style=\"color:blue;\">" . $location . "</font>, ";
            }
            $recent_locations_text = substr($recent_locations_text, 0, -2);
            $recent_locations_text .= "<br/><br/>";
        }

        $most_visited_locations = array_count_values($most_visited_locations);
        asort($most_visited_locations);
        $most_visited_locations = array_reverse($most_visited_locations, TRUE);

        $most_visited_text = "";
        if (count($most_visited_locations) > 0)
        {
            $most_visited_text .= "Most visited:<br/>";
            foreach ($most_visited_locations as $location => $count)
            {
                $most_visited_text .= "<font style=\"color:blue;\">" . $location . "</font>, ";
            }
            $most_visited_text = substr($most_visited_text, 0, -2);
            $most_visited_text .= "<br/>";
        }

        $return_string = "<font style=\"font-size:20px; text-align:center;\">Locations</font><br/>" .$recent_locations_text . $most_visited_text;
        if(!(empty($recent_locations_text) && empty($most_visited_text)))
        {
                    return $return_string;
        }else{
            return "";
        }
    }
    
    function insert_profile_picture()
    {
        $logo_text = "logo_" .rand(1,25) .".png";
        ?>
        <img src="/application/assets/images/logos/<?php echo $logo_text; ?>" />
             <?php
    }

}