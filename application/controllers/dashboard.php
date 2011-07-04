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
    public function get_following()
    {
        $this->load->database();
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT user_meta.first_name, user_meta.last_name, friends.follow_id " .
                "FROM friends LEFT JOIN  user_meta " .
                "ON friends.follow_id = user_meta.user_id WHERE friends.user_id = ? " .
                "ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($user->id));

        foreach ($query->result() as $row)
        {
            ?>
            <div class="following_entry" following_id="<?php echo($row->follow_id); ?>">
                <div class="following_name">
                    <?php echo($row->first_name . ', ' . $row->last_name); ?>
                </div>
            </div>
            <?php
        }
    }

    public function get_followers()
    {
        $this->load->database();
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT user_meta.first_name, user_meta.last_name, friends.user_id " .
                "FROM friends LEFT JOIN  user_meta " .
                "ON friends.user_id = user_meta.user_id WHERE friends.follow_id = ? " .
                "ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($user->id));

        foreach ($query->result() as $row)
        {
            ?>
            <div class="follower_entry" follower_id="<?php echo($row->user_id); ?>">
                <div class="follower_name">
                    <?php echo($row->first_name . ', ' . $row->last_name); ?>
                </div>
            </div>
            <?php
        }
    }

}
?>