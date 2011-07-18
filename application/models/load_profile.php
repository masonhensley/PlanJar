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
        $school = $result->first_row('school');

        
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
            </div>
            <div class="profile_user_information"><?php
            
                                echo $user->first_name . " " . $user->last_name ."<br/>";
                                echo $school;
                                
        ?>
            </div>
        </div>
        <div class="profile_body">
        </div>

        <?php
    }

}