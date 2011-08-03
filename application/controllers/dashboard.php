<?php

class Dashboard extends CI_Controller
{

    public function index($initial_tab = 'profile', $suggested = '')
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
                'school' => $this->load_groups->user_school())
            );
        } else
        {
            $this->logout();
        }
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
// Capture the parameter
        $following_id = $this->input->get('following_id');

// Add the relationship entry
        $this->load->model('follow_ops');
        $this->follow_ops->add_user_following($following_id);

// Notify the given user
        $this->load->model('notification_ops');
        $this->notification_ops->notify(array($following_id), 'follow_notif', $this->ion_auth->get_user()->id);
    }

// Removes a following relationship.
    public function remove_following()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "DELETE FROM friend_relationships WHERE user_id = ? AND follow_id = ?";
        $query = $this->db->query($query_string, array($user->id, $this->input->get('following_id')));
    }

// Return HTML for the users the user is following.
    public function get_following()
    {
        $this->load->model('follow_ops');
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                "FROM friend_relationships LEFT JOIN  user_meta ON friend_relationships.follow_id = user_meta.user_id " .
                "LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                "WHERE friend_relationships.user_id = ? " .
                "ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($user->id));

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $this->follow_ops->echo_user_entry($row, 'remove following');
            }
        } else
        {
            ?> 
            <div style="width:100%; height:40px; background-color:white;text-align: center;border-top:1px solid black;padding-top:15px;">
                You are not following anyone yet
            </div>
            <?php
        }
    }

// Return HTML for the users following the user.
    public function get_followers()
    {
        $this->load->model('follow_ops');
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                "FROM friend_relationships LEFT JOIN  user_meta ON friend_relationships.user_id = user_meta.user_id " .
                "LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                "WHERE friend_relationships.follow_id = ? " .
                "ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($user->id));
        if ($query->num_rows() > 0)
        {
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
        } else
        {
            ?> 
            <div style="width:100%; height:40px; background-color:white;text-align: center;border-top:1px solid black;padding-top:15px; padding-bottom: 15px">
                You don't have any followers yet<br/>
                <a href="/dashboard/following/suggested" style="color:#110055; font-weight:bold;">Find friends</a>
            </div>
            <?php
        }
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
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                if ($row->user_following_id == $user->id)
                {
                    $this->group_ops->echo_group_entry($row, 'following');
                } else
                {
                    $this->group_ops->echo_group_entry($row, 'joined');
                }
            }
        } else
        {
            ?> 
            <div style="width:100%; height:40px; background-color:white;text-align: center;border-bottom: 1px solid black; border-top:1px solid black;padding-top:15px; padding-bottom: 15px">
                You are not following any groups<br/>
                <a href="/dashboard/groups/suggested" style="color:#110055; font-weight:bold;">Find groups</a>
            </div>
            <?php
        }
    }

    public function suggest_groups()
    {
        $this->load->model('load_suggested_groups');
        $returnHTML = $this->load_suggested_groups->suggested_groups();
        echo "$returnHTML";
    }

// Returns the HTML for the group info as well as the associated group id
    public function get_group_details()
    {
        $this->load->model('load_group_profile');
        $group_id = $this->input->get('group_id');
        $this->load_group_profile->load_group_profile($group_id);
    }

    public function add_group_following()
    {
        $this->load->model('group_ops');
        $this->group_ops->follow_group($this->input->get('group_id'));
    }

    // Removes the appropriate group relationship
    // Removes the group if no members are left
    public function remove_group_following()
    {
        $user = $this->ion_auth->get_user();
        $group_id = $this->input->get('group_id');

        // Delete the relationship
        $query_string = "DELETE FROM group_relationships WHERE group_id = ? AND (user_following_id = ? OR user_joined_id = ?)";
        $query = $this->db->query($query_string, array($group_id, $user->id, $user->id));

        // Delete the group if no users are joined
        $this->load->model('group_ops');
        if (count($this->group_ops->get_users(array($group_id))) == 0)
        {
            $this->group_ops->delete_group($group_id);
        }
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
                $this->group_ops->echo_group_entry($row, 'following');
            } else
            {
                $this->group_ops->echo_group_entry($row, 'joined');
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
        $format = ""; // this keeps track of whether the profile is being viewed by the user or someone else

        if ($user == 'user')
        {
            $user = $this->ion_auth->get_user();
            $format = "profile_edit";
        } else
        {
            $user = $this->ion_auth->get_user($user);
            $format = "profile_view";
        }

        $this->load_profile->display_profile($user, $format);
    }

    public function update_box()
    {
// update a user's box
        $box_text = $this->input->get('box_text');
        $id = $this->ion_auth->get_user()->id;
        $query = "UPDATE user_meta SET box='$box_text' WHERE user_meta.user_id=$id";
        $this->db->query($query);
        echo $box_text;
    }

// Creates a group as defined by the given data
// Returns the group id
    public function create_group()
    {
        $this->load->model('group_ops');
        $user = $this->ion_auth->get_user();

// This just looks nicer than a long function call.
        $name = $this->input->get('group_name');
        $description = $this->input->get('group_description');
        $privacy = $this->input->get('privacy');
        $location_source = $this->input->get('location_source');

// Create the group.
        $group_result = $this->group_ops->add_group($name, $description, $privacy, $location_source);

// Conflict
        if ($group_result['status'] == 'success')
        {
// Join the user to the group
            $this->group_ops->follow_group($group_result['group_id']);
            $this->group_ops->join_group($group_result['group_id']);
        }

// Return
        echo(json_encode($group_result));
    }

    public function update_notification_viewed()
    {
        $this->load->model('notification_ops');
        $this->notification_ops->update_notification_viewed($this->input->get('notif_id'), $this->input->get('value'));
    }

    public function accept_notification()
    {
        $this->load->model('notification_ops');
        $this->notification_ops->accept_notification($this->input->get('notif_id'));
    }

}
?>
