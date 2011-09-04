<?php

class Load_group_profile extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    // Returns the HTML of the group profile
    function load_group_profile($group_id)
    {
        $group_info = $this->get_group_details($group_id);
        if ($group_info != '')
        {
            $return_html = $this->return_display_profile($group_info);
        }
    }

    // Gathers information about the group and returns it in an array
    function get_group_details($group_id)
    {
        $query = "SELECT school_id, name, description, privacy, id, school_group FROM groups WHERE id='$group_id'"; // get the group info
        $result = $this->db->query($query);

        // Quit if there are no results
        if ($result->num_rows() == 0)
        {
            return '';
        }

        $return_array = $result->row_array();
        if (isset($return_array['school_id']))
        {
            $id = $return_array['school_id'];
            $query2 = "SELECT school FROM school_data WHERE id=$id"; // get the school name
            $result2 = $this->db->query($query2);
            $result2 = $result2->row_array();
            $return_array['school'] = $result2['school'];
        }
        $query3 = "SELECT DISTINCT user_joined_id, user_following_id FROM group_relationships WHERE group_id=$group_id";
        $result3 = $this->db->query($query3);
        $number_following = 0;
        $number_joined = 0;
        foreach ($result3->result() as $group_relationship)
        {
            if ($group_relationship->user_joined_id)
            {
                $number_joined++;
                $number_following++;
            } else
            {
                $number_following++;
            }
        }
        $return_array['number_following'] = $number_following;
        $return_array['number_joined'] = $number_joined;

        return $return_array;
    }

    // Echos the HTML of a group profile
    function return_display_profile($group_info)
    {
        $this->load->model('group_ops');
        ?><div class="group_profile_header" group_id="<?php echo $group_info["id"]; ?>" priv_type="<?php echo($group_info['privacy']); ?>"><?php
        if ($group_info['school_group'])
        {
            ?>School Profile<?php
        } else
        {
            ?>Group Profile<?php
        }
        ?>
        </div>

        <div class="group_profile_picture">
            <?php echo $this->return_profile_picture(); ?>
        </div>
        <div class="user_profile_header">
            <div class="profile_name_display">
                <br/><font style="font-size:20px; font-weight:bold;"><?php echo $group_info['name']; ?></font><br/><?php
        if ($this->group_ops->user_is_following($group_info['id']))
        {
                ?> <font style="color:green; font-weight:bold;">following</font><?php
        } else if ($this->group_ops->user_is_joined($group_info['id']))
        {
                ?><font style="color:purple; font-weight:bold;">joined</font><?php
        } else
        {
                ?><font style="color:gray">(not following)</font><?php
        }
            ?>
                <br/><br/><br/><br/>
            </div>
        </div>

        <div class="profile_body">
            <div class="profile_body_text">

                <font style="color:gray;">
                Members &nbsp;<font style="font-weight:bold; color:black;"><?php echo $group_info['number_joined']; ?></font>&nbsp;&nbsp;&nbsp;&nbsp;
                Followers &nbsp;<font style="font-weight:bold; color:black;"><?php echo $group_info['number_following']; ?></font>
                <div id="view_group_list" style="position:absolute; top:0px; left:228px">See Members</div>
                </font><br/><hr/>
                <?php
                if ($group_info['school_group']) // show that if it is a designated school group, or school affiliation
                {
                    ?><font style="font-weight:bold; font-size:12px;">This is a designated school group, only open to students from
                    <?php echo $group_info['school']; ?></font><br/><br/><?php
        } else
        {
            if (isset($group_info['school']))
            {
                        ?><font style="color:gray;">School: </font><font style="color:black; font-weight:bold;"><?php echo $group_info['school']; ?></font><br/><br/><?php
            }
        }
                ?><font style="color:gray;">Description</font><br/>
                <font style=""><?php echo $group_info['description']; ?> </font><br/><hr/><br/>

            </div>
        </div>

        <?php
        if ($this->group_ops->user_is_joined($group_info['id']))  // if you are joined
        {
            if ($group_info['school_group']) // if you are joined and it is a school group
            {
                ?>
                <div class="group_bottom_text">
                    Group is <font style="color:red;font-weight:bold;">closed</font>
                    <font style="color:gray; font-style:italic; position:absolute; right:7px; bottom:11px;">cannot leave school group</font>
                </div>
                <?php
            } else // if you are joined and it is a regular group
            {
                ?>
                <div class="group_bottom_text">
                    You are a <font style="color:purple;font-weight:bold;">member</font> of this group
                    <div class="invite_people" style="margin-right:3px;">Invite People</div>
                </div>
                <div class="group_bottom_button">
                    <div class="remove_joined">unjoin</div> 
                </div>
                <?php
            }
        } else if ($group_info['school_group']) // if you are following and it is a school group
        {
            ?>
            <div class="group_bottom_text">
                Group is <font style="color:red;font-weight:bold;">closed</font>
            </div>
            <?php
        } else if ($this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'open') // if you are following and group is open
        {
            ?>
            <div class="group_bottom_text">
                Group is <font style="color:green;font-weight:bold;">open</font>
            </div>
            <div class="remove_following" style="position:absolute; bottom:0px; right:83px;">unfollow</div>
            <div class="add_joined" style="position:absolute; bottom:0px; right:0px;">join group</div>

            <?php
        } else if ($this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'loose') // if you are following and the group is loose
        {
            ?>
            >
            <div class="group_bottom_text">
                <div class="request_to_join">Request to Join Group</div>
                Group is <font style="color:red;font-weight:bold;">closed</font>
            </div>
            <div class="remove_following">unfollow</div>
            <?php
        } else if (!$this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'open') // if you are not following and the group is open
        {
            ?>
            <div class="group_bottom_text">
                Group is <font style="color:green;font-weight:bold;">open</font>
                <div class="add_following">follow</div>
            </div>
            <?php
        } else if (!$this->group_ops->user_is_following($group_info['id']) && $group_info['privacy'] == 'loose')
        {
            ?>
            <div class="group_bottom_text">
                Group is <font style="color:red;font-weight:bold;">closed</font>
                <div class="add_following">follow</div>
            </div>
            <?php
        }
    }

    function return_profile_picture()
    {
        $logo_text = rand(1, 6) . ".png";
        return "<img src=\"/application/assets/images/logos/$logo_text\" style=\"width:50px; height:50px;\" />";
    }

}
?>
