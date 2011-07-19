<?php

class Dashboard extends CI_Controller
{

    public function index($initial_tab = 'following', $suggested = '')
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
                'lastname' => $lastname,
                'initial_tab' => $initial_tab,
                'suggested' => $suggested,
                'user_school' => $this->_get_user_school())
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
    public function add_user_following()
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
            $this->follow_ops->echo_user_entry($row, 'remove following');
        }
    }

    // Return HTML for the users following the user.
    public function get_followers()
    {
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
            if ($this->follow_ops->is_following($user->id, $row->user_id))
            {
                $this->follow_ops->echo_user_entry($row, 'following');
            } else
            {
                $this->follow_ops->echo_user_entry($row, 'add following');
            }
        }
    }

    // Returns HTML containing details about a follower.
    public function get_follower_details()
    {
        $follower_id = $this->input->get('follower_id');
        echo("Information for user id $follower_id...");
    }

    // this function returns html for the suggested friends list
    public function get_suggested_friends()
    {
        $user_info = $this->ion_auth->get_user();
        $user_id = $user_info->id;
        $grad_year = $user_info->grad_year;
        $school_id = $user_info->school_id;
        $this->load->model('load_suggested_friends');
        $this->load_suggested_friends->suggested_friends($user_id, $grad_year, $school_id);
    }

    // Returns HTML for the list of groups the user is following.
    public function get_following_groups()
    {
        $this->load->model('group_ops');
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT groups.id, groups.name, group_relationships.user_following_id " .
                "FROM group_relationships LEFT JOIN groups " .
                "ON group_relationships.group_id = groups.id " .
                "WHERE group_relationships.user_following_id = ? OR group_relationships.user_joined_id = ? " .
                "ORDER BY groups.name ASC";
        $query = $this->db->query($query_string, array($user->id, $user->id));

        foreach ($query->result() as $row)
        {
            if ($row->user_following_id == $user->id)
            {
                $this->group_ops->echo_group_entry($row, 'remove following');
            } else
            {
                $this->group_ops->echo_group_entry($row, 'remove joined');
            }
        }
    }

    public function suggest_groups()
    {
        $this->load->model('load_suggested_groups');
        $returnHTML = $this->load_suggested_groups->suggested_groups();
        echo "$returnHTML";
    }

    public function get_group_details()
    {
        $this->load->model('group_ops');

        $group_id = $this->input->get('group_id');

        echo("Information for group $group_id");
        if ($this->group_ops->user_is_following($group_id))
        {
            echo('<div class="add_joined">Join</div>');
        }
    }

    public function add_group_following()
    {
        $this->load->model('group_ops');

        $this->group_ops->follow_group($this->input->get('group_id'));
    }

    public function remove_group_following()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "DELETE FROM group_relationships WHERE group_id = ? AND user_following_id = ?";
        $query = $this->db->query($query_string, array($this->input->get('group_id'), $user->id));
    }

    public function remove_group_joined()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "DELETE FROM group_relationships WHERE group_id = ? AND user_joined_id = ?";
        $query = $this->db->query($query_string, array($this->input->get('group_id'), $user->id));
    }

    public function add_group_joined()
    {
        $this->load->model('group_ops');

        $this->group_ops->join_group($this->input->get('group_id'));
    }

    public function get_joined_groups()
    {
        $this->load->model('group_ops');
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT groups.id, groups.name, group_relationships.user_following_id " .
                "FROM group_relationships LEFT JOIN groups " .
                "ON group_relationships.group_id = groups.id " .
                "WHERE group_relationships.user_following_id = ? OR group_relationships.user_joined_id = ? " .
                "AND " .
                "ORDER BY groups.name ASC";
        $query = $this->db->query($query_string, array($user->id, $user->id));

        foreach ($query->result() as $row)
        {
            if ($row->user_following_id == $user->id)
            {
                $this->group_ops->echo_group_entry($row, 'remove following');
            } else
            {
                $this->group_ops->echo_group_entry($row, 'remove joined');
            }
        }
    }

    public function group_search()
    {
        $this->load->model('group_ops');

        $this->group_ops->search_for_groups($this->input->get('needle'));
    }

    public function get_notifications()
    {
        $this->load->model('notification_ops');
        $this->notification_ops->get_notifications();
    }

    public function get_profile()
    {
        $this->load->model('load_profile');

        $user = $this->input->get('user_id');
        if ($user == 'user')
        {
            $user = $this->ion_auth->get_user();
        } else
        {
            $user = $this->ion_auth->get_user($user);
        }
        $this->load_profile->display_profile($user);
    }

    private function _get_user_school()
    {
        $query_string = "SELECT school FROM school_data WHERE id = ?";
        $query = $this->db->query($query_string, array($this->ion_auth->get_user()->school_id));

        return $query->row()->school;
    }

    public function create_group()
    {
        $this->load->model('group_ops');
        $user = $this->ion_auth->get_user();

        // This just looks nicer than a long function call.
        $name = $this->input->get('group_name');
        $description = $this->input->get('group_description');
        $school_id = $user->school_id;
        $description = $this->input->get('privacy');

        // Create the group.
        $this->add_group($name, $description, $privacy, $location_source);

        // Join the user to the group
        $this->follow_group($this->db->last_query());
        $this->join_group($this->db->last_query());

        // Capture and process the invite lists
        $invited_users = explode(',', $this->input->get('group_invite_users'));
        if ($invited_users[0] == '')
        {
            $invited_users = array();
        }

        $invited_groups = explode(',', $this->input->get('group_invite_groups'));
        if ($invited_groups[0] == '')
        {
            $invited_groups = array();
        }

        // Invite people and groups if necessary.
        if (count($invited_users) > 0)
        {
            $this->load->model('notification_ops');
            $this->notification_ops->notify_users($invited_users, 'group_invite', $this->db->insert_id());
        }

        if (count($invited_groups) > 0)
        {
            $this->load->model('notification_ops');
            $this->notification_ops->notify_joined_groups($invited_groups, 'group_invite', $this->db->insert_id());
        }

        // Success
        echo('success');
    }

}

?>