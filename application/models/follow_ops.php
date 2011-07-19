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

            // Break into search terms
            $needle_array = explode(' ', $needle);

            // Generate query strings to cross-reference all needle terms with the first and last names in the db
            $needle_where = '';
            foreach ($needle_array as $cur_needle)
            {
                $needle_where .= "(user_meta.first_name LIKE '%%$cur_needle%%' OR " .
                        "user_meta.last_name LIKE '%%$cur_needle%%') AND ";
            }

            // Trim the end of the string
            if (count($needle_array) > 0)
            {
                $needle_where = substr($needle_where, 0, -5);
            }

            $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                    "FROM user_meta LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                    "WHERE ($needle_where) AND user_meta.user_id <> ?";

            // Generate a string to exclude people the user is already following.
            $following_ids = $this->get_following_ids();
            if (count($following_ids) > 0)
            {
                $query_string .= " AND user_meta.user_id <> '" . implode("' AND user_meta.user_id <> '", $following_ids) . "'";
            }

            $query = $this->db->query($query_string, array($user->id));

            // Echo the results
            foreach ($query->result() as $row)
            {
                $this->echo_user_entry($row, 'add following');
            }
        }
    }

    // Return a list of following ids
    function get_following_ids()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT follow_id FROM friends WHERE user_id = ?";
        $query = $this->db->query($query_string, array($user->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = $row->follow_id;
        }

        return $return_array;
    }

    // Echos a user_entry, which is used as a following/follower list item
    function echo_user_entry($row, $option = '', $suggested_friends=null)
    {
        ?>
        <div class="user_entry" user_id="<?php echo($row->user_id); ?>">
            <div class="user_entry_left">
                <center>
                    <div class="user_picture">
                        <?php $logo_text = "logo_" . rand(1, 25) . ".png"; ?>
                        <img src="/application/assets/images/logos/<?php echo $logo_text; ?>" style="width:100%; height:100%;" />
                    </div>
                </center>
            </div>

            <div class="user_entry_middle">



                <div class="user_name">
                    <?php
                    echo($row->first_name . ' ' . $row->last_name);
                    echo "<br>";
                    $year_display = substr($row->grad_year, -2);
                    echo $row->school . " ('" . $year_display . ")<br/>";
                    if ($option == 'suggested')
                    {
                        $id = $row->user_id;
                        echo "$suggested_friends[$id] connection";
                        if ($suggested_friends[$id] > 1)
                        {
                            echo "s";
                        }
                    } else if ($option == 'suggested_school')
                    {
                        
                    }
                    ?>
                </div>          
            </div>
            <?php
            if ($option == 'remove following')
            {
                ?>
                <div class="remove_following">Unfollow</div>
                <?php
            } else if ($option == 'add following')
            {
                ?>
                <div class="add_following">Follow</div>
                <?php
            } else if ($option == 'following')
            {
                ?>
                <div class="following">Following</div>
                <?php
            } else if ($option == 'suggested')
            {
                ?>
                <div class="add_following">Follow</div>
                <?php
            } else if ($option == 'suggested_school')
            {
                ?>
                <div class="add_following">Follow</div>
            <?php
        }
        ?>
        </div>
        <?php
    }

    // Returns true if $user_id is following $follow_id
    public function is_following($user_id, $follow_id)
    {
        $query_string = "SELECT * FROM friends WHERE user_id = ? AND follow_id = ?";
        $query = $this->db->query($query_string, array($user_id, $follow_id));
        return $query->num_rows() > 0;
    }

}
?>
