<?php
// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {
    
    public function index()
    {
        if($this->ion_auth->logged_in)
        {
            $this->load->view('home.php');
        } else {
            $this->logout();
        }
    }
    
    public function logout()
    {  
        $this->ion_auth->logout();
        redirect('/login/');
    }
}
?>
