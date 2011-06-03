<?php

class User extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
    }
    
    public function check_email() {
        $this->load->library('Ion_auth');
        
        echo(email_check($this->get('email')));
    }
    
    public function signup($email, $password, $additional_data) {
        
    }

}