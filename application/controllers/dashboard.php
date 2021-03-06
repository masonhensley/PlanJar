<?php

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        // Parent constructor
        parent::__construct();


        // Redirect if not logged in
        if (!$this->ion_auth->logged_in())
        {
            redirect('login');
        }
    }

    public function index($initial_tab = 'profile', $action_arg = '')
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
            'action_arg' => $action_arg,
            'school' => $this->load_groups->user_school())
        );
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
    }

// Removes a following relationship.
    public function remove_following()
    {
        $user = $this->ion_auth->get_user();
        $following_id = $this->input->get('following_id');

        $query_string = "DELETE FROM friend_relationships WHERE user_id = ? AND follow_id = ?";
        $query = $this->db->query($query_string, array($user->id, $following_id));

// Update the notification status
        $this->load->model('notification_ops');
        $query_string = "SELECT id FROM notifications WHERE type = ? AND user_id = ? AND  subject_id = ?";
        $query = $this->db->query($query_string, array('follow_notif', $user->id, $following_id));

        if ($query->num_rows() > 0)
        {
            $this->notification_ops->update_notification_accepted($query->row()->id, false, true);
        }
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
                "ORDER BY user_meta.first_name ASC";
        $query = $this->db->query($query_string, array($user->id));

        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                $this->follow_ops->echo_user_entry($row, 'remove following', null, false);
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
                "ORDER BY user_meta.first_name ASC";
        $query = $this->db->query($query_string, array($user->id));
        if ($query->num_rows() > 0)
        {
            foreach ($query->result() as $row)
            {
                if ($this->follow_ops->is_following($user->id, $row->user_id))
                {
                    $this->follow_ops->echo_user_entry($row, 'following', null, false);
                } else
                {
                    $this->follow_ops->echo_user_entry($row, 'add following', null, false);
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

        $query_string = "SELECT groups.id, groups.name, group_relationships.user_following_id
            FROM group_relationships JOIN groups
            ON group_relationships.group_id = groups.id
            WHERE group_relationships.user_following_id = ? OR group_relationships.user_joined_id = ?
            ORDER BY groups.name ASC";
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
    public function remove_group_following()
    {
        $user = $this->ion_auth->get_user();
        $group_id = $this->input->get('group_id');

// Delete the relationship
        $query_string = "DELETE FROM group_relationships WHERE group_id = ? AND user_following_id = ?";
        $query = $this->db->query($query_string, array($group_id, $user->id));
    }

// Removes the appropriate group relationship
// Removes the group if no members are left
    public function remove_group_joined()
    {
        $user = $this->ion_auth->get_user();
        $group_id = $this->input->get('group_id');

// Delete the relationship
        $query_string = "DELETE FROM group_relationships WHERE group_id = ? AND user_joined_id = ?";
        $query = $this->db->query($query_string, array($group_id, $user->id));

// Update the notification status
        $this->load->model('notification_ops');
        $query_string = "SELECT id FROM notifications
            WHERE (type = ? AND user_id = ?) OR (type = ? AND originator_id = ?) AND subject_id = ?";
        $query = $this->db->query($query_string, array(
            'group_invite',
            $user->id,
            'join_group_request',
            $user->id,
            $group_id));

        if ($query->num_rows() > 0)
        {
            $this->notification_ops->update_notification_accepted($query->row()->id, false, true);
        }

// Delete the group if no users are joined
        $this->load->model('group_ops');
        if (count($this->group_ops->get_group_members($group_id)) == 0)
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

    public function get_all_notifications()
    {
        $this->load->model('notification_ops');
        $this->notification_ops->display_all_notifications();
    }

    public function get_unread_notifications()
    {
        $this->load->model('notification_ops');
        $this->notification_ops->display_unread_notifications();
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

            // Quit if the user doesn't exist
            if (!is_object($user))
            {
                return;
            }

            $format = "profile_view";
        }

        $this->load_profile->display_profile($user, $format, $this->input->get('force_accept_button'));
    }

    public function update_box()
    {
// update a user's box
        $box_text = $this->input->get('box_text');
        $box_text = trim($box_text);
        $box_text = mysql_real_escape_string($box_text);

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
        $name = trim($this->input->get('group_name'));
        $description = trim($this->input->get('group_description'));
        $privacy = $this->input->get('privacy');
        $location_source = $this->input->get('location_source');

// Create the group.
        $group_result = $this->group_ops->add_group($name, $description, $privacy, $location_source);

// Conflict
        if ($group_result['status'] == 'success')
        {
// Join the user to the group
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

    public function update_email_prefs()
    {
// Rectify the params
        $event_invite = $this->input->get('event_invite') ? 1 : 0;
        $follow_notif = $this->input->get('follow_notif') ? 1 : 0;
        $group_invite = $this->input->get('group_invite') ? 1 : 0;
        $join_group_request = $this->input->get('join_group_request') ? 1 : 0;

        $query_string = "UPDATE user_meta
            SET event_invite = ?, follow_notif = ?, group_invite = ?, join_group_request = ?
            WHERE user_id = ?";
        $query = $this->db->query($query_string, array($event_invite,
            $follow_notif,
            $group_invite,
            $join_group_request,
            $this->ion_auth->get_user()->id));
    }

    public function get_email_prefs()
    {
        $query_string = "SELECT event_invite, follow_notif, group_invite, join_group_request FROM user_meta WHERE user_id = ?";
        $query = $this->db->query($query_string, array($this->ion_auth->get_user()->id));

        echo(json_encode($query->row()));
    }

    public function change_password()
    {
        $old_password = $this->input->get('old_password');
        $new_password = $this->input->get('new_password');
        $new_password_1 = $this->input->get('new_password_1');

        $this->ion_auth_model->change_password($this->ion_auth->get_user()->email, $old_password, $new_password, $new_password_1);
    }

    // Uploads a picture to the uploads directory
    // Returns the user id and img tag
    public function upload_picture()
    {
        $image = $_FILES['image'];

        // Reject non-jpg files
        if ($image['type'] != 'image/jpeg')
        {
            echo(json_encode(array(
                'status' => 'error',
                'message' => 'Sorry, but we only accept jpeg images right now.'
            )));
        } else if ($image['size'] > 1000000)
        {
            // Reject over-sized files
            echo(json_encode(array(
                'status' => 'error',
                'message' => 'Sorry, your file must be less than ?MB.'
            )));
        } else if ($image['error'] != 0)
        {
            // Unhandled error
            echo(json_encode(array(
                'status' => 'error',
                'message' => 'Sorry, an unexpected error occuured.'
            )));
        } else
        {
            // Save the file
            $user_id = $this->ion_auth->get_user()->id;
            $file_path = "/var/www/uploads/$user_id.jpg";
            move_uploaded_file($image['tmp_name'], $file_path);

            // Get the dimensions
            list($width, $height) = getimagesize($file_path);

            // Success
            echo(json_encode(array(
                'status' => 'success',
                'img' => urlencode(base_url() . "uploads/$user_id.jpg"),
                'width' => $width,
                'height' => $height
            )));
        }
    }

    // Crops the image and stores it to the profile
    public function crop_temp_image()
    {
        $user = $this->ion_auth->get_user();

        $x1 = $this->input->get('x1');
        $y1 = $this->input->get('y1');
        $x2 = $this->input->get('x2');
        $y2 = $this->input->get('y2');

        // Load the lib and filepath
        $this->load->library('image_lib');
        $temp_file = '/var/www/uploads/' . $user->id . '.jpg';

        // Create the config info for the manipulation class
        $config = array(
            'image_library' => 'gd2',
            'source_image' => $temp_file,
            'width' => $x2 - $x1,
            'height' => $y2 - $y1,
            'x_axis' => $x1,
            'y_axis' => $y1,
            'maintain_ratio' => false
        );

        // Crop the image
        $this->image_lib->initialize($config);
        if ($this->image_lib->crop())
        {
            // Successful crop. Setup the config
            $config = array(
                'image_library' => 'gd2',
                'source_image' => $temp_file,
                'width' => 80,
                'height' => 80
            );

            // Resize to 80x80
            if (!$this->image_lib->resize())
            {
                echo(json_encode(array(
                    'status' => 'error',
                    'message' => 'There was an error cropping your image. Try again.'
                )));
                return;
            }
        } else
        {
            echo(json_encode(array(
                'status' => 'error',
                'message' => 'There was an error cropping your image. Try again.'
            )));
            return;
        }

        // Delete the existing image
        if ($user->image_name != '')
        {
            unlink('/var/www/user_images/' . $user->image_name . '.jpg');
        }

        // Copy the image to the user images folder, update the user, and delete the first file
        $file_name = $user->id . dechex(rand(1000, 9999999999));
        $destination = "/var/www/user_images/$file_name.jpg";
        copy($temp_file, $destination);
        $this->ion_auth->update_user($user->id, array('image_name' => $file_name));
        unlink($temp_file);

        echo(json_encode(array('status' => 'success')));
    }

    public function request_join_group()
    {
        $group_id = $this->input->get('group_id');

        $this->load->model('group_ops');
        $member_list = $this->group_ops->get_group_members($group_id);

        // Pick 5 random users if there are more than 5
        if (count($member_list) > 5)
        {
            for ($i = 0; $i < 5; ++$i)
            {
                $index = array_rand($member_list);
                $new_member_list[] = $member_list[$index];
                unset($member_list[$index]);
            }
            $member_list = $new_member_list;
        }

        $this->load->model('notification_ops');
        $this->notification_ops->notify($member_list, array(), 'join_group_request', $group_id);
    }

}
?>
