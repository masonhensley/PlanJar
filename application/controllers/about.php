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
        $this->load->model('notification_ops');
        var_dump($this->notification_ops->deduce_accepted('follow_notif', 48, 25, 48));
    }

}

?>
