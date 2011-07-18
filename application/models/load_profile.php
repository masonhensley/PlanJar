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
        $groups_joined = $this->get_groups_joined($user);
        $groups_following = $this->get_groups_following($user);
        
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
            </div>
            <div class="profile_user_information"><?php
        $year_display = substr($user->grad_year, -2);
        echo $user->first_name . " " . $user->last_name . "<br/>";
        echo $row->school . " ('" . $year_display . ")<br/>";
        ?>
            </div>
        </div>


        <div class="profile_body">
            <div class="profile_body_text"><?php
            // Code to display groups joined
        $groups_joined_text = "";
        if (count($groups_joined > 0))
        {
            $groups_joined_text .= "Groups joined: ";
            foreach ($groups_joined as $group)
            {
                $groups_joined_text .= $group . ", ";
            }
            $groups_joined_text = substr($groups_joined_text, 0, -2);
            $groups_joined_text .= "<br/>";
        }
        echo $groups_joined_text;
        
        // Code to display groups following
        $groups_following_text = "";
        if(count($groups_following) > 0)
        {
            $groups_following_text .= "Groups following: ";
            foreach($groups_following as $group)
            {
                $groups_following_text .= $group .", ";
            }
            $groups_following_text = substr($groups_following_text,0,-2);
            $groups_following_text .= "<br/>";
        }
        echo $groups_following_text;
        
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
        var_dump($query);
        $result = $this->db->query($query);
        $groups_following = array();
        foreach($result->result() as $group)
        {
            $groups_following[] = $group->name;
        }
        return $groups_following;
    }

}