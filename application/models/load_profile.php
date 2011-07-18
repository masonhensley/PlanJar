<?php

class Load_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function display_profile()
    {
        $this->output();
    }

    function output()
    {
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
            </div>
            <div class="profile_user_information">

            </div>
        </div>
        <div class="profile_body">
        </div>

    <?php
    }
}