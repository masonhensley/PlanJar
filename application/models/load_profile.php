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
            <div class="profile_user_information">
                <br/><font style="font-size:20px;"><font style="font-weight:bold;"><?php echo $user->first_name . " " . $user->last_name; ?></font><br/>
                <font style="color:darkgray;"><?php echo $row->school ." "; ?>('<?php echo substr($user->grad_year, -2); ?>)</font></font><br/>
            </div>
        </div>
        <hr/>
        <div class="profile_body">
            <div class="profile_body_text"><?php
        // Code to display groups joined
                ?><br/><font style="font-size:25px; margin-left:195px;">Groups</font><br/>Joined<br/><?php
        if (count($groups_joined > 0))
        {
            foreach ($groups_joined as $group)
            {
                         ?><font style="color:green; font-size: 16px;"><?php echo $group ."     "; ?></font><?php
            }
        } else
        {
                    ?><font style="font-style:italic;">Nothing to show</font><?php
        }

        // Code to display groups following
                ?><br/><br/>Following<br/><?php
        if (count($groups_following) > 0)
        {
            foreach ($groups_following as $group)
            {
                        ?><font style="color:purple; font-size:16px;"><?php echo $group ."     "; ?></font><?php
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
            if (!in_array($place->name, $recent_locations) && $recent_tracker < 5)
            {
                $recent_tracker++;
                $recent_locations[] = $place->name;
            }
            $most_visited_locations[] = $place->name;
        }
        $most_visited_locations = array_count_values($most_visited_locations);
        asort($most_visited_locations);
        $most_visited_locations = array_reverse($most_visited_locations, TRUE);
        
        ob_start();
        ?><font style="font-size:25px; margin-left:200px;">Places</font><br/>
        <font style="font-size:18px;">Recently visited</font><br/><?php
        if (count($recent_locations) > 0)
        {
            foreach ($recent_locations as $location)
            {
                ?><font style="color:navy;"><?php echo $location;?></font><?php
            }
        }else{
            ?><font style="font-style:italic;">Nothing to show</font><?php
        }
        
        ?><br/><br/><font style="font-size:18px;">Most visited</font><br/><?php
        if (count($most_visited_locations) > 0)
        {
            foreach ($most_visited_locations as $location => $count)
            {
                ?><font style="color:navy;"><?php echo $location; ?></font><?php
            }  
        }else{
            ?><font style="font-style:italic;">Nothing to show</font><?php
        }
        return ob_get_clean();
    }

    function insert_profile_picture()
    {
        $logo_text = "logo_" . rand(1, 25) . ".png";
        ?>
        <img src="/application/assets/images/logos/<?php echo $logo_text; ?>" />
        <?php
    }

}