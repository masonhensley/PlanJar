<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    public function index() {
        if ($this->ion_auth->logged_in()) 
        {
            $this->load->view('home_view');
        } else {
            logout();
        }
    }

    // logs user out and redirects to login page
    public function logout() {
        $this->ion_auth->logout();
        redirect('/login/');
    }

}

?>
