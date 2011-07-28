<?php

class Load_group_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Returns the HTML of the group profile and the gorup id in an array
    function load_group_profile($group_id)
    {
        $group_info = $this->get_group_details($group_id);
        $return_html = $this->return_display_profile($group_info);

        return array('html' => $return_html, 'group_id' => $group_info['id']);
    }

    // Gathers information about the group and returns it in an array
    function get_group_details($group_id)
    {
        $query = "SELECT school_id, name, description, privacy, id, school_group FROM groups WHERE id=$group_id"; // get the group info
        $result = $this->db->query($query);
        $return_array = $result->row_array();
        if (isset($return_array['school_id']))
        {
            $id = $return_array['school_id'];
            $query2 = "SELECT school FROM school_data WHERE id=$id"; // get the school name
            $result2 = $this->db->query($query2);
            $result2 = $result2->row_array();
            $return_array['school'] = $result2['school'];
        }
        $query3 = "SELECT user_joined_id, user_following_id FROM group_relationships WHERE group_id=$group_id";
        $result3 = $this->db->query($query3);
        $return_array['number_following'] = $result3->num_rows();
        $number_joined = 0;
        foreach ($result3->result() as $group_relationship)
        {
            if ($group_relationship->user_joined_id)
            {
                $number_joined++;
            }
        }
        $return_array['number_joined'] = $number_joined;

        return $return_array;
    }

    // Returns the HTML of a group profile in string form
    function return_display_profile($group_info)
    {
        $this->load->model('group_ops');

        $return_string = '<div class="group_profile_header">';

        if ($group_info['school_group'])
        {
            $return_string .= "School Profile";
        } else
        {
            $return_string .= "Group Profile";
        }

        $return_string .= '</div>';
        $return_string .= '<div class="profile_top_bar">';
        $return_string .= '<div class="profile_picture">';
        $return_string .= $this->return_profile_picture();
        $return_string .= '</div>';
        $return_string .= '<div class="profile_user_information">';

        $return_string .= "<br/><font style=\"font-size:20px; font-weight:bold;\">" . $group_info['name'] . "</font><br/>";
        if ($this->group_ops->user_is_following($group_info['id']))
        {
            $return_string .= "<font style=\"color:green; font-weight:bold;\">following</font>";
        } else if ($this->group_ops->user_is_joined($group_info['id']))
        {
            $return_string .= "<font style=\"color:purple; font-weight:bold;\">joined</font>";
        } else
        {
            $return_string .= "<font style=\"color:gray\">(not following)</font>";
        }

        $return_string .= '</div>';
        $return_string .= '</div>';
        $return_string .= '<div class="profile_body">';
        $return_string .= '<div class="profile_body_text">';

        $return_string .= "<font style=\"color:gray;\">";
        $return_string .= "Members: " . $group_info['number_joined'] . "&nbsp;&nbsp;&nbsp;&nbsp;Followers: " . $group_info['number_following'];
        $return_string .= "</font><br/><hr/>";

        if ($group_info['school_group']) // show that if it is a designated school group, or school affiliation
        {
            $return_string .= "<font style=\"font-weight:bold; font-size:12px;\">This is a designated school group, only open to students from ";
            $return_string .= $group_info['school'] . "</font><br/><br/>";
        } else
        {
            if (isset($group_info['school']))
            {
                $return_string .= "<font style=\"color:gray;\">School:</font> <font style=\"color:black; font-weight:bold;\">" . $group_info['school'] . "</font><br/><br/>";
            }
        }

        $return_string .= "<font style=\"color:gray;\">Description</font><br/>";
        $return_string .= "<font style=\"\">" . $group_info['description'] . "</font><br/><hr/><br/>";

        $return_string .= '</div>';
        $return_string .= '</div>';
        $return_string .= '<div class="profile_bottom_bar" group_id="' . $group_info["id"] . '">';

        $bottom_bar_text = "";
        $bottom_bar_buttons = "";
        if ($this->group_ops->user_is_joined($group_info['id']))  // if you are joined
        {
            if ($group_info['school_group']) // if you are joined and it is a school group
            {
                $bottom_bar_text .= "Group is <font style=\"color:red;font-weight:bold;\">closed</font>";
                $bottom_bar_buttons .= "<font style=\"color:gray; font-style:italic;\">cannot leave school group</font>";
            } else // if you are joined and it is a regular group
            {
                $bottom_bar_text .= "You are a <font style=\"color:purple;font-weight:bold;\">member</font> of this group";
                $bottom_bar_buttons.= "<div class=\"invite_people\" style=\"margin-right:3px;\">Invite people</div>";
                $bottom_bar_buttons.= "<div class=\"remove_following\">unjoin</div>";
            }
        } else if ($group_info['school_group']) // if you are following and it is a school group
        {
            $bottom_bar_text .= "Group is <font style=\"color:red;font-weight:bold;\">closed</font>";
        } else if ($this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'open') // if you are following and group is open
        {
            $bottom_bar_text .= "Group is <font style=\"color:green;font-weight:bold;\">open</font>";
            $bottom_bar_buttons .= "<div class=\"add_joined\" style=\"margin-right:3px;\">join group</div>";
            $bottom_bar_buttons .= "<div class=\"remove_following\">unfollow</div>";
        } else if ($this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'loose') // if you are following and the group is loose
        {
            $bottom_bar_text .= "Group is <font style=\"color:red;font-weight:bold;\">closed</font>";
            $bottom_bar_buttons .= "<div class=\"remove_following\">unfollow</div>";
        } else if (!$this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'open') // if you are not following and the group is open
        {
            $bottom_bar_text .= "Group is <font style=\"color:green;font-weight:bold;\">open</font>";
        } else if (!$this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'loose')
        {
            $bottom_bar_text .= "Group is <font style=\"color:red;font-weight:bold;\">closed</font>";
        }
        $return_string .= "<div style=\"float:left;margin-left:10px;\">" . $bottom_bar_text . "</div>";
        if ($group_info['school_group'])
        {
            $return_string .= "<div style=\"position:absolute; right:0px; bottom:0px;\">" . $bottom_bar_buttons . "</div>";
        } else
        {
            $return_string .= "<div style=\"position:absolute; right:-15px; bottom:-11px;\">" . $bottom_bar_buttons . "</div>";
        }

        $return_string .= '</div>';

        return $return_string;
    }

    function return_profile_picture()
    {
        $logo_text = "logo_" . rand(1, 25) . ".png";

        return "<img src=\"/application/assets/images/logos/$logo_text\"/>";
    }

}
?>
