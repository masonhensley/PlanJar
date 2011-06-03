<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -  
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    
    // Returns 'true' if the email exists, 'false' otherwise
    private function check_email() {
        echo($this->ion_auth->email_check(
                $this->input->get('email')) ? 'true' : 'false');
    }

    // Signs up a user
    // Returns 'success' or 'failure'
    // $additional_data must be a JSON array multidimentional array.
    private function signup() {
        $name = $this->input->get('first_name');
        $email = $this->input->get('$email_1');
        $password = $this->input->get('$password');
        $additional_data = $this->input->get('$additional_data');
        echo($this->ion_auth->register($email, $password, $email, $additional_data) ? 'success' : 'failure');
    }
    
    public function index() {
        $this->load->view('login.php');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
