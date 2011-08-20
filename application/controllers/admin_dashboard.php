<?php

class Admin_dashboard extends CI_Controller
{

    public function index()
    {
        if ($this->ion_auth->logged_in() && $this->ion_auth->get_user()->group_id == 1)
        {
            $this->load->view('admin_dashboard_view');
        } else
        {
            redirect('login');
        }
    }

}
?>
