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
        echo($this->notification_ops->create_email_notification('Hi Parker,<br/><br/>John Doe has invited you to rage at Dan McGuiness.'));
    }

}

?>
