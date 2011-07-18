<?php

class Load_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function display_profile($user)
    {
        
        $this->output($user);
    }

    function output($user)
    {
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
            </div>
            <div class="profile_user_information">
                <?php echo $user->first_name ." " .$user->last_name; ?>
            </div>
        </div>
        <div class="profile_body">
        </div>

    <?php
    }
}