<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
    public function index() {
        $this->load->view('login.php');
        // The following line should be unnecessary, but the ion_auth library won't autoload.
        // See the applicatin/config/autoload.php file.
        $this->load->library('ion_auth');
        if ($this->ion_auth->username_check('test')) {
            echo('success');
        } else {
            echo('failure');
        };
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */