<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
    }

    // logout
    function logout()
    {
        $this->ion_auth->logout();
        redirect('login');
    }

    //forgot password
    function forgot_password()
    {
        if ($this->input->get('email'))
        {
            // Run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($this->input->get('email'));

            if ($forgotten)
            {
                echo('success');
            } else
            {
                echo('There was a problem processing your request. Please try again.');
            }
        } else
        {
            $this->load->view('auth/forgot_password');
        }
    }

    //reset password - final step for forgotten password
    public function reset_password($code)
    {
        $reset = $this->ion_auth->forgotten_password_complete($code);

        if ($reset)
        {  //if the reset worked then show post reset page
            $this->load->view('auth/reset_password_view');
        } else
        { //if the reset didnt work then send them back to the forgot password page
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //activate the user
    function activate($id, $code=false)
    {
        if ($code !== false)
            $activation = $this->ion_auth->activate($id, $code);
        else if ($this->ion_auth->is_admin())
            $activation = $this->ion_auth->activate($id);

        if ($activation)
        {
            // Get the school's info
            $user = $this->ion_auth->get_user($id);
            $query_string = "SELECT group_id, latitude, longitude, city FROM school_data WHERE id = ?";
            $query = $this->db->query($query_string, array($user->school_id));
            $row = $query->row();

            // Join the user to his school's group
            $group_id = $row->group_id;
            $this->load->model('group_ops');
            $this->group_ops->join_group($group_id, $id);

            // Set the user's location to the school location
            $this->ion_auth->update_user($user->id, array(
                'latitude' => $row->latitude,
                'longitude' => $row->longitude,
                'city_state' => $row->city
            ));

            // Redirect to the login page
            redirect("/login", 'refresh');
        } else
        {
            show_404();
        }
    }

}
