<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    // Index function runs first when site is loaded
    public function index() {

        //Check if user is logged in
        // if user is not logged in, load login.php
        if (!$this->ion_auth->logged_in()) {
            $this->load->view('login.php');
        } else {
            // if user is logged in, redirect to planjar.com/home/
            redirect('/home/', 'refresh');
        }
    }

    // Signs up a user
    // Returns 'success' or 'failure'
    // $additional_data must be a JSON array multidimentional array.
    // This function is called upon a successful submit
    public function try_sign_up() {
        $email = $this->input->get('$su_email_1');
        $password = $this->input->get('$su_password');

        // populate the associative array additional_data with sex/college/birth info
        $additional_data = array(
            'school' => $this->input->get('su_school'),
            'sex' => $this->input->get('su_sex'),
            'first_name' => $this->input->get('su_first_name'),
            'last_name' => $this->input->get('su_last_name'),
            'birthday' => $this->input->get('su_birthday'),
            'grad_year' => $this->input->get('su_grad_year')
        );


        echo($password);
        $registered = $this->ion_auth->register($email, $password, $email, $additional_data);



//        if($registered)
//        {
//            echo "success";
//        }else{
//            if(!$this->ion_auth->username_check($email))
//            {
//                echo "username_error";
//            }
//        }
    }

    public function try_log_in() {
        $email = $this->input->get('$li_email');
        $password = $this->input->get('$li_password');
        $remember = $this->input->get('li_remember');
        //$logged_in = $this->ion_auth->login($email, $password, $remember);
        $logged_in = true;
        if (!$logged_in) {
            echo "error";
        } else {
            echo "/home/";
        }
    }

}
