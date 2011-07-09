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

            // Pass the necessary information to the view.
            $this->load->view('dashboard_view', array(
                'firstname' => $firstname,
                'lastname' => $lastname)
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

    // Searches for people to follow by name and returns HTML
    public function follow_search()
    {
        $this->load->model('follow_ops');
        $this->follow_ops->search_for_users($this->input->get('needle'));
    }

    // Adds a following relationship if one doesn't already exist.
    public function add_following()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "INSERT IGNORE INTO friends VALUES (DEFAULT, ?, ?)";
        $query = $this->db->query($query_string, array(
                    $user->id,
                    $this->input->get('following_id')
                ));
    }

    // Removes a following relationship.
    public function remove_following()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "DELETE FROM friends WHERE user_id = ? AND follow_id = ?";
        $query = $this->db->query($query_string, array($user->id, $this->input->get('following_id')));
    }

    // Return HTML for the users the user is following.
    public function get_following()
    {
        $this->load->database();
        $this->load->model('follow_ops');
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                "FROM friends LEFT JOIN  user_meta ON friends.follow_id = user_meta.user_id " .
                "LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                "WHERE friends.user_id = ? " .
                "ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($user->id));

        foreach ($query->result() as $row)
        {
            $this->follow_ops->user_follow_entry($row, 'remove following');
        }
    }

    // Return HTML for the users following the user.
    public function get_followers()
    {
        $this->load->database();
        $this->load->model('follow_ops');
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                "FROM friends LEFT JOIN  user_meta ON friends.user_id = user_meta.user_id " .
                "LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                "WHERE friends.follow_id = ? " .
                "ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($user->id));

        foreach ($query->result() as $row)
        {
            $this->follow_ops->user_follow_entry($row);
        }
    }

    // Returns HTML containing details about a follower.
    public function get_follower_details()
    {
        $follower_id = $this->input->get('follower_id');
        echo("Information for user id $follower_id...");
    }

}
?>