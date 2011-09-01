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
        $this->load->library('email');
        $this->email->clear();
        $this->email->from('noreply@planjar.com', 'PlanJar');
        $this->email->to('bossier330@gmail.com');
        $this->email->subject('PlanJar test email');
        $this->email->message('yup');
        $this->email->send();

        echo($this->email->print_debugger());
    }

}

?>
