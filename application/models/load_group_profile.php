<?php

class Load_group_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function _load_group_profile($group_id)
    {
        $group_info = $this->_get_group_details($group_id);
        $this->_display_profile($group_info);
    }

    function _get_group_details($group_id)
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
            if ($group_relationship != NULL)
            {
                $number_joined++;
            }
        }
        $return_array['number_joined'] = $number_joined;

        return $return_array;
    }

    function _display_profile($group_info)
    {
        $this->load->model('group_ops');
        ?>
        <div class="group_profile_header">
            <?php
            if ($group_info['school_group'])
            {
                echo "School Profile";
            } else
            {
                echo "Group Profile";
            }
            ?>

        </div>
        <div class="profile_top_bar">
            <div class="profile_picture">
        <?php $this->_insert_profile_picture(); ?>
            </div>
            <div class="profile_user_information">
                <?php
                echo "<br/><font style=\"font-size:20px; font-weight:bold;\">" . $group_info['name'] . "</font><br/>";
                if ($this->group_ops->user_is_following($group_info['id']))
                {
                    echo "<font style=\"color:green; font-weight:bold;\">following</font>";
                } else if ($this->group_ops->user_is_joined($group_info['id']))
                {
                    echo "<font style=\"color:purple; font-weight:bold;\">joined</font>";
                } else
                {
                    echo "<font style=\"color:gray\">(not following)</font>";
                }
                ?>
            </div>
        </div>
        <div class="profile_body">
            <div class="profile_body_text">
                <?php
                echo "<font style=\"color:gray;\">";
                echo "Members: " . $group_info['number_joined'] . "&nbsp;&nbsp;&nbsp;&nbsp;Followers: " . $group_info['number_following'];
                echo "</font><br/><hr/>";

                if ($group_info['school_group']) // show that if it is a designated school group, or school affiliation
                {
                    echo "<font style=\"font-weight:bold; font-size:12px;\">This is a designated school group, only open to students from";
                    echo $group_info['school'] ."</font><br/>";
                } else
                {
                    if (isset($group_info['school']))
                    {
                        echo "<font style=\"color:gray;\">School:</font> <font style=\"color:black; font-weight:bold;\">" . $group_info['school'] . "</font><br/><br/>";
                    }
                }

                echo "<font style=\"color:gray;\">Description</font><br/>";
                echo "<font style=\"\">" . $group_info['description'] . "</font><br/><hr/><br/>";
                ?>
            </div>
        </div>
        <div class="profile_bottom_bar" group_id="<?php echo $group_info['id'] ?>">
            <?php
            $bottom_bar_text = "";
            $bottom_bar_buttons = "";
            if ($this->group_ops->user_is_joined($group_info['id']))  // if you are joined
            {
                $bottom_bar_text .= "You are a <font style=\"color:purple;font-weight:bold;\">member</font> of this group";
                $bottom_bar_buttons.= "<div class=\"remove_following\">unjoin</div>";
            } else if ($this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'open') // if you are following and group is open
            {
                $bottom_bar_text .= "Group is <div style=\"color:green;font-weight:bold;\">open</div>";
                $bottom_bar_buttons .= "<div class=\"remove_following\">unfollow</div>";
                $bottom_bar_buttons .= "<div class=\"add_joined\">join group</div>";
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
            echo "<div style=\"float:left;margin-left:10px;\">" . $bottom_bar_text . "</div><div style=\"float:right;\">" . $bottom_bar_buttons . "</div>";
            ?>
        </div>

        <?php
    }

    function _insert_profile_picture()
    {
        $logo_text = "logo_" . rand(1, 25) . ".png";
        ?>
        <img src="/application/assets/images/logos/<?php echo $logo_text; ?>" />
        <?php
    }

}
?>
