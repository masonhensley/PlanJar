<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help extends CI_Controller
{

    public function index()
    {
        $this->load->view('help_view');
    }

    public function foo()
    {
        $this->load->model('notification_ops');
        $notif_text = "Hi Parker.

Mason Hensley has invited you to PlanJar Launch Parteeeyyy! at Tin Roof for Tuesday the 6th. ";
        $image = '<img src="/user/get_prof_pic/2"/>';
        echo($this->notification_ops->create_email_notification($notif_text, '900000', $image));
    }

}

?>
