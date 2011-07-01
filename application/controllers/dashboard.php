<?php

class Dashboard extends CI_Controller
{

    public function index()
    {
        if ($this->ion_auth->logged_in())
        {
            $user_info = $this->ion_auth->get_user();

            // retrieve other useful variables for view
            $firstname = $user_info->first_name;
            $lastname = $user_info->last_name;

            // Lookup the groups by id.
            $this->load->model('load_groups');
            $joined_groups = $this->load_groups->get_groups(json_decode($user_info->joined_groups));
            $followed_groups = $this->load_groups->get_groups(json_decode($user_info->followed_groups));

            // Pass the necessary information to the view.
            $this->load->view('dashboard_view', array(
                'firstname' => $firstname,
                'lastname' => $lastname,
                'joined_groups' => $joined_groups,
                'followed_groups' => $followed_groups)
            );
        } else
        {
            $this->logout();
        }
    }

    // logs user out and redirects to login page
    public function logout()
    {
        $this->ion_auth->logout();
        redirect('/login/');
    }
    
    // Return HTML for the users the user is following.
    public function get_following() {
        $query_string = "SELECT ";
    }

}

?>