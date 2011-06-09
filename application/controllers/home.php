<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function index()
    {
        // if user is logged in, load home view, otherwise logout
        if ($this->ion_auth->logged_in())
        {
            $this->load->view('home_view');
            
        } else
        {
            $this->logout();
        }
    }

    // logs user out and redirects to login page
    public function logout()
    {
        $this->ion_auth->logout();
        redirect('/login/');
    }
}

// For Mason to fuck with...
public function foo() {
    $this->load->view('foo_view');
}

?>