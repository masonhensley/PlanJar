<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class About extends CI_Controller
{
    public function index()
    {
        if ($this->ion_auth->logged_in())
        {
            $this->load->view('about_view');
        }
    }
}
?>
