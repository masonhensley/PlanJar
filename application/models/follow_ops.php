<?php

class Follow_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Search for users to follow by name
    function search_for_users($needle)
    {
        $needle = trim($needle);
        if ($needle != '')
        {
            $user = $this->ion_auth->get_user();

            $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school
                    FROM user_meta LEFT JOIN school_data ON user_meta.school_id = school_data.id
                    WHERE MATCH(user_meta.first_name, user_meta.last_name) AGAINST (? IN BOOLEAN MODE)
                    AND user_meta.user_id <> ?";

            // Generate a string to exclude people the user is already following.
            $following_ids = $this->get_following_ids();
            if (count($following_ids) > 0)
            {
                $query_string .= " AND user_meta.user_id <> '" . implode("' AND user_meta.user_id <> '", $following_ids) . "'";
            }

            $query = $this->db->query($query_string, array(str_replace(' ', '* ', $needle) . '*', $user->id));

            // Echo the results
            foreach ($query->result() as $row)
            {
                $this->echo_user_entry($row, 'add following', $profile_links_enabled = false);
            }
        }
    }

    // Return a list of following ids
    function get_following_ids()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT follow_id FROM friend_relationships WHERE user_id = ?";
        $query = $this->db->query($query_string, array($user->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = $row->follow_id;
        }

        return $return_array;
    }

    // Returns (id, name) pairs of the user's followers
    function get_followers_tuples()
    {
        $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name
            FROM friend_relationships LEFT JOIN user_meta ON friend_relationships.user_id = user_meta.user_id
            WHERE friend_relationships.follow_id = ? ORDER BY user_meta.last_name ASC";
        $query = $this->db->query($query_string, array($this->ion_auth->get_user()->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = array('id' => $row->user_id, 'name' => $row->first_name . ' ' . $row->last_name);
        }

        return $return_array;
    }

    // Echos a user_entry, which is used as a following/follower list item
    function echo_user_entry($row, $option = '', $suggested_friends=null, $profile_links_enabled=true)
    {
        $this->load->model('load_profile');
        ob_start();
        ?>
        <div class="user_entry" user_id="<?php echo($row->user_id); ?>">
            <div class="user_entry_left">
                <center>
                    <div class="user_picture">
                        <?php
                        if ($profile_links_enabled)
                        {
                            ?>
                            <a href="/dashboard/following/<?php echo $row->user_id; ?>">
                                <?php
                            }
                            ?>

                            <?php
                            $this->load_profile->insert_profile_picture($row->user_id, 50);
                            if ($profile_links_enabled)
                            {
                                ?>   
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                </center>
            </div>
            <div class="user_entry_middle">
                <div class="user_name">
                    <?php
                    if ($profile_links_enabled)
                    {
                        ?> 
                        <a href="/dashboard/following/<?php echo $row->user_id; ?>">
                            <?php
                        }
                        ?>
                        <font style="font-weight:bold;color:black;">
                        <?php
                        echo $row->first_name . ' ' . $row->last_name;
                        ?>
                        </font>
                        <?php
                        if ($profile_links_enabled)
                        {
                            ?>
                        </a>
                        <?php
                    }
                    ?><br/><?php
            $year_display = substr($row->grad_year, -2);
                    ?> 
                    <font style="color:gray;">
                    <?php
                    echo $row->school . " ('" . $year_display . ")";
                    ?></font><br/><?php
            if ($option == 'suggested')
            {
                $number_of_connections = $suggested_friends[$row->user_id];
                        ?>
                        <font style="color:green; font-size:10px; position:absolute;bottom:21px;right:1px;">
                        <?php
                        echo "$number_of_connections+ connections";
                        ?></font><?php
        }
                    ?>
                </div>          
            </div>
            <?php
            if ($option == 'remove following')
            {
                ?>
                <div class="following">following</div>
                <!--<div class="remove_following">unfollow</div>-->
                <?php
            } else if ($option == 'add following')
            {
                ?>
                <div class="add_following">follow</div>
                <?php
            } else if ($option == 'following')
            {
                ?>
                <div class="following">friend</div>
                <?php
            } else if ($option == 'suggested')
            {
                ?>
                <div class="add_following">follow</div>
                <?php
            } else if ($option == 'suggested_school')
            {
                ?>
                <div class="add_following">follow</div>
                <?php
            } else if ($option == 'already_following')
            {
                ?>
                <div class="following">following</div>
                <?php
            } else if ($option == 'this_is_you')
            {
                ?>
                <div class="following">this is you</div>
                <?php
            }
            ?>
        </div>
        <?php
        echo ob_get_clean();
    }

    // Returns true if $user_id is following $follow_id
    function is_following($user_id, $follow_id)
    {
        $query_string = "SELECT * FROM friend_relationships WHERE user_id = ? AND follow_id = ?";
        $query = $this->db->query($query_string, array($user_id, $follow_id));
        return $query->num_rows() > 0;
    }

    // Adds the specified user to the user's following list
    function add_user_following($following_id, $skip_notif_check = false)
    {
        if (!$skip_notif_check)
        {
            // See if the user has any follow notifications
            $query_string = "SELECT id FROM notifications
            WHERE user_id = ? AND type= ? AND subject_id = ?";
            $query = $this->db->query($query_string, array($this->ion_auth->get_user()->id, 'follow_notif', $following_id));

            echo($this->db->last_query);
//            if ($query->num_rows() > 0)
//            {
//                // Accept the notification 
//                $this->load->model('notification_ops');
//                $this->notification_ops->accept_notification($query->row()->id);
//                return;
//            }
        }

        $query_string = "INSERT IGNORE INTO friend_relationships VALUES (DEFAULT, ?, ?)";
        $query = $this->db->query($query_string, array(
            $this->ion_auth->get_user()->id,
            $following_id
                ));

        // Notify the given user
        $this->load->model('notification_ops');
        $this->notification_ops->notify(array($following_id), array(), 'follow_notif', $this->ion_auth->get_user()->id);
    }

}
?>
