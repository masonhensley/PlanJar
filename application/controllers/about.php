<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class About extends CI_Controller
{

    public function index()
    {
        $this->load->view('about_view');
    }

    public function foo()
    {
        $this->load->view('image_test_view');
    }

}

?>
