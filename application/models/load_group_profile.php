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
        $query = "SELECT school_id, name, description, privacy, id FROM groups WHERE id=$group_id"; // get the group info
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
        ?>
        <div class="profile_top_bar">
            <div class="profile_picture">
                <?php $this->_insert_profile_picture(); ?>
            </div>
            <div class="profile_user_information">
                <?php
                echo "<br/><font style=\"font-size:20px; font-weight:bold;color:navy;\">" . $group_info['name'] . "</font><br/>";
                echo $group_info['number_joined'] . " members<br/>";
                echo $group_info['number_following'] . " followers";
                ?>
            </div>
        </div>
        <div class="profile_body">
            <div class="profile_body_text">
                <?php
                if (isset($group_info['school']))
                {
                    echo "School: <font style=\"color:purple; font-weight:bold;\">" . $group_info['school'] . "</font><br/><br/>";
                }
                echo "<font style=\"font-weight:bold;\">Description</font><br/>";
                echo $group_info['description'] . "<br/><br/>";
                ?>
            </div>
            <div class="profile_bottom_bar">
                <?php
                $this->load->model('group_ops');
                if ($this->group_ops->user_is_following($group_info['id'])) // this is for your following list
                {
                    if ($this->group_ops->user_is_joined($group_info['id']))  // if you are joined
                    {
                        echo "You are a <font style=\"color:purple;font-weight:bold;\">member</font> of this group";
                    } else if ($group_info['privacy'] == 'open') // if you are not joined and the group privacy is "open"
                    {
                        echo "This group is <font style=\"color:green;font-weight:bold;\">open</font>";
                        echo('<div class="add_joined">Join Group</div>');
                    } else if ($group_info['privacy'] == 'loose')
                    { // if you are not joined and the group privacy is "loose"
                        echo "This group is <font style=\"color:red;font-weight:bold;\">closed</font> and requires an invitation";
                    }
                } else
                { // this is for the suggested groups list
                    if ($group_info['privacy'] == 'open') // if you are not joined and the group privacy is "open"
                    {
                        echo "This group is <font style=\"color:green;font-weight:bold;\">open</font>";
                    } else
                    {
                        echo "This group is <font style=\"color:red;font-weight:bold;\">closed</font> and requires an invitation";
                    }
                }

                /*
                  if ($group_info['privacy'] = 'open')
                  {

                  $this->load->model('group_ops');
                  if ($this->group_ops->user_is_following($group_info['id']))
                  {
                  echo('<div class="add_joined">Join Group</div>');
                  }
                  }else if($this->group_ops->user_is_joined($group_info['id'])){
                  echo "You are <font style=\"color:purple;font-weight:bold;\">joined</font> in this group";
                  }else{

                  }
                 * 
                 */
                ?>
            </div>
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
