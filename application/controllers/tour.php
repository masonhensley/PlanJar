<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tour extends CI_Controller
{

    public function index()
    {
        $this->load->view('tour_view');
    }

}

?>
