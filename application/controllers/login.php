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
    // This function is called upon a successful submit
    public function sign_up() {
        $email = $this->input->get('$su_email_1');
        $password = $this->input->get('$su_password');
        
        // populate the associative array additional_data with sex/college/birth info
        //$additional_data['school'] = $this->input->get('su_school');
        //$additional_data['sex'] = $this->input->get('su_sex');
        //$additional_data['first_name'] = $this->input->get('su_first_name');
        //$additional_data['last_name'] = $this->input->get('su_last_name');
        //$additional_data['birthday'] = $this->input->get('su_birthday');
        //$additional_data['grad_year'] = $this->input->get('su_grad_year');
        
        $email = "parker@planjar.com";
        $password = "herro";
        $additional_data['school'] = "Vanderbilt";
        $additional_data['sex'] = "Male";
        $additional_data['first_name'] = "Parjer";
        $additional_data['last_name'] = "Bossier";
        $additional_data['birthday'] = "03/30/1990";
        $additional_data['grad_year'] = "2012";
        
        echo($this->ion_auth->register($email, $password, $email, $additional_data) ? 'success' : 'failure');
    }
    
    public function index() {
        $this->load->view('login.php');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
