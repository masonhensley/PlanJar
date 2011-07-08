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

    public function follow_search()
    {
        $needle = trim($this->input->get('needle'));
        if ($needle != '')
        {
            $needle_array = explode(' ', $needle);

            $this->load->model('load_groups');
            $joined_groups = $this->load_groups->joined_groups();

            $first_name_where = '';
            $last_name_where = '';
            foreach ($needle_array as $cur_needle)
            {
                $first_name_where .= "user_meta.first_name LIKE '%%$cur_needle%%' OR ";
                $last_name_where .= "user_meta.last_name LIKE '%%$cur_needle%%' OR ";
            }

            if (count($needle_array) > 0)
            {
                $first_name_where = substr($first_name_where, 0, -4);
                $last_name_where = substr($last_name_where, 0, -4);
            }

            $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                    "FROM user_meta LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                    "WHERE ($first_name_where) OR ($last_name_where)";

            $query = $this->db->query($query_string);

            foreach ($query->result() as $row)
            {
                $this->_echo_following_entry($row);
            }
        }
    }

    public function add_following()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT * FROM friends WHERE user_id = ? AND follow_id = ?";
        $query = $this->db->query($query_string, array($user->id, $this->input->get('following_id')));

        if ($query->num_rows() == 0)
        {
            $query_string = "INSERT INTO friends VALUES (DEFAULT, ?, ?)";
            $query = $this->db->query($query_string, array($user->id, $this->input->get('following_id')));
        }
    }

    public function remove_following()
    {
        $user = $this->ion_auth->get_user();
        $query_string = "DELETE FROM friends WHERE user_id = ? AND follow_id = ?";
        $query = $this->db->query($query_string, array($user->id, $this->input->get('following_id')));

        echo($this->db->last_query());
    }

// Return HTML for the users the user is following.
    public function get_following()
    {
        $this->load->database();
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                "FROM friends LEFT JOIN  user_meta ON friends.follow_id = user_meta.user_id " .
                "LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                "WHERE friends.user_id = ? " .
                "ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($user->id));

        foreach ($query->result() as $row)
        {
            $this->_echo_following_entry($row, true);
        }
    }

    private function _echo_following_entry($row, $delete_visible = false)
    {
        ?>
        <div class="following_entry" user_id="<?php echo($row->user_id); ?>">
            <div class="following_entry_left">
                <center>
                    <div class="user_picture"></div>

                    <div class="grad_year">
                        <?php echo('Class of ' . $row->grad_year); ?>
                    </div>
                </center>
            </div>

            <div class="following_entry_middle">
                <div class="user_name">
                    <?php echo($row->first_name . ' ' . $row->last_name); ?>
                </div>

                <div class="user_school">
                    <?php echo($row->school); ?>
                </div>
            </div>
            <?php
            if ($delete_visible)
            {
                $display_val = 'block';
            } else
            {
                $display_val = 'none';
            }
            ?>
            <div class="remove_following" style="display: <?php echo($display_val); ?>">Un-follow</div>
        </div>
        <?php
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
            
        }
    }

    public function get_follower_details()
    {
        $follower_id = $this->input->get('follower_id');
        echo("Information for user id $follower_id...");
    }

}
?>