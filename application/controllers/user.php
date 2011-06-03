<?php

class User extends CI_Controller {

    // Constructor
    public function __construct() {
        parent::__construct();
    }

    // Returns 'true' if the email exists, 'false' otherwise
    public function check_email() {
        echo($this->ion_auth->email_check(
                $this->input->get('email')) ? 'true' : 'false');
    }

    // Signs up a user
    // Returns 'success' or 'failure'
    // $additional_data must be a JSON array multidimentional array.
    public function signup() {
        $email = $this->input->get('$email');
        $password = $this->input->get('$password');
        $additional_data = $this->input->get('$additional_data');
        echo($this->ion_auth->register($email, $password, $email, $additional_data) ? 'success' : 'failure');
    }

}