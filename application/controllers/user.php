<?php

class User extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
        // Your own constructor code
    }
    
    public function check_email() {
        // email_check($this->get('email'));
        echo('hello');
    }
    
    public function signup($email, $password, $additional_data) {
        
    }

}