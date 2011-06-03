<?php

class User extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
    }
    
    public function check_email() {
        echo($this->ion_auth->email_check($this->input->get('email')));
    }
    
    public function signup($email, $password, $additional_data) {
        
    }

}