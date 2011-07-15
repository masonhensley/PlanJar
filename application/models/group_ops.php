<?php

class Group_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Search for groups to follow by name
    function search_for_groups($needle)
    {
        $needle = trim($needle);
        if ($needle != '')
        {
            // Break into search terms
            $needle_array = explode(' ', $needle);

            // Generate a query string to cross-reference all needle terms with the group names
            $needle_where = '';
            foreach ($needle_array as $cur_needle)
            {
                $needle_where .= "groups.name LIKE '%%$cur_needle%%' AND ";
            }

            // Trim the end of the string
            if ($needle_where != '')
            {
                $needle_where = substr($needle_where, 0, -5);
            }

            // Generate a query string to exclude already followed or joined groups
            $already_following = '';
            $following_groups_list = $this->get_following_groups();
            foreach ($following_groups_list as $group_id)
            {
                $already_following .= "groups.id <> $group_id AND ";
            }

            // Trim the end of the string
            if ($already_following != '')
            {
                $already_following = substr($already_following, 0, -5);
            }

            $query_string = "SELECT id, name " .
                    "FROM groups WHERE ($needle_where) AND ($already_following)";

            $query = $this->db->query($query_string);

            // Echo the results
            foreach ($query->result() as $row)
            {
                $this->echo_group_entry($row, 'add following');
            }
        }
    }

    public function get_following_groups()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT group_id FROM group_relationships " .
                "WHERE user_following_id = ? OR user_joined_id = ?";
        $query = $this->db->query($query_string, array($user->id, $user->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = $row->group_id;
        }

        return $return_array;
    }

    public function get_joined_groups()
    {
        $user = $this->ion_auth->get_user();

        $query_string = "SELECT group_id FROM group_relationships " .
                "WHERE user_joined_id = ?";
        $query = $this->db->query($query_string, array($user->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = $row->group_id;
        }

        return $return_array;
    }

    // Echos a group entry.
    public function echo_group_entry($row, $option = '', $suggested_groups=null)
    {
        ?>
        <div class="group_entry" group_id="<?php echo($row->id); ?>">
            <div class="group_entry_left">
                <center>
                    <div class="group_picture"></div>
                </center>
            </div>
            <div class="group_entry_middle">
                <div class="group_name">
                    <?php
                    echo($row->name) . "<br/>";
                    if ($option == 'suggested groups')
                    {
                        echo $suggested_groups[$row->id] . " connection";
                        if ($suggested_groups[$row->id] > 1)
                        {
                            echo"s";
                        }
                    }
                    ?>

                </div>
            </div>
            <div class="group_entry_left_side">
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
                } else if ($option == 'remove joined')
                {
                    ?>
                    <div class="remove_joined">Unjoin</div>
                    <?php
                } else if ($option == 'suggested groups')
                {
                    ?>
                    <div class="add_following">Follow</div>
            <?php
        }
        ?>
            </div>
        </div>
        <?php
    }

    // Returns true if the user is following the specified group
    public function user_is_following($group_id)
    {
        $this->load->database();
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT * FROM group_relationships WHERE group_id = ? AND user_joined_id = ?";
        $query = $this->db->query($query_string, array($group_id, $user_id));

        return $query->num_rows() > 0;
    }

}
?>
