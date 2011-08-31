<?php

class User extends CI_Controller
{

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    public function get_prof_pic($user_id)
    {
        if ($user_id == 'rand')
        {
            // Random image
            $logo_text = "logo_" . rand(1, 25);
            $filename = "/var/www/uploads/profile_jars/$logo_text.png";

            $this->output->set_content_type('image/png');
            $handle = fopen($filename, 'r');
            fpassthru($handle);
        } else
        {
            $prof_picture = $this->ion_auth->get_user($user_id)->prof_picture;

            $this->output->set_content_type('image/jpeg');
            echo($prof_picture);
        }
    }

}