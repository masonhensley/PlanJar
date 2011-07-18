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
        $groups_joined = $this->get_groups($user);
        
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
            </div>
            <div class="profile_user_information"><?php
            
                                $year_display = substr($user->grad_year, -2);
                                echo $user->first_name . " " . $user->last_name ."<br/>";
                                echo $row->school ." (" .$year_display .")<br/>";
                                
        ?>
            </div>
        </div>
        <div class="profile_body">
        </div>

        <?php
    }
    
    function get_groups($user)
    {
        $query = "SELECT groups.name FROM group_relationships 
        LEFT JOIN groups ON groups.id=group_relationships.id 
        WHERE group_relationships.user_joined_id=$user->id";
        
        var_dump($query);
    }

}