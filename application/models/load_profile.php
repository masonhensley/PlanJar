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
        var_dump($groups_joined);
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
            </div>
            <div class="profile_user_information"><?php
            
                                $year_display = substr($user->grad_year, -2);
                                echo $user->first_name . " " . $user->last_name ."<br/>";
                                echo $row->school ." ('" .$year_display .")<br/>";
                                $display_groups_text = "";
                                if(count($groups_joined > 0))
                                {
                                    $display_groups_text .= "Groups joined: ";
                                    foreach($groups_joined as $group)
                                    {
                                        $display_groups_text .= $group .", ";
                                    }
                                    //$display_groups_text = substr($display_groups_text,-2);
                                }
                                echo $display_groups_text;
        ?>
            </div>
        </div>
        <div class="profile_body">
        </div>

        <?php
    }
    
    function get_groups($user)
    {
        $query = "SELECT groups.name, group_relationships.id 
        FROM group_relationships LEFT JOIN groups ON groups.id=group_relationships.group_id 
        WHERE group_relationships.user_joined_id=$user->id";
        $result = $this->db->query($query);
        $groups_joined = array();
        foreach($result->result() as $group)
        {
            $groups_joined[] = $group->name;
        }
        return $groups_joined;
    }

}