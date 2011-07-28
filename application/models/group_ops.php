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

    // Returns an array containing the ids of the groups the user is following (contains joined groups)
    function get_following_groups()
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

    // Returns an array containing the ids of the user's joined groups
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
    public function echo_group_entry($row, $option = '', $suggested_groups = null)
    {
        ?>
        <div class="group_entry" group_id="<?php echo($row->id); ?>">
            <div class="group_entry_left">
                <center>
                    <div class="group_picture">
                        <?php $logo_text = "logo_" . rand(1, 25) . ".png"; ?>
                        <img src="/application/assets/images/logos/<?php echo $logo_text; ?>" style="width:100%; height:100%;" />
                    </div>
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
                if ($option == 'suggested groups' || $option == 'add following')
                {
                    ?>
                    <div class="add_following">follow</div>
                    <?php
                } else if ($option == 'following')
                {
                    ?>
                    <div class="following">following</div>
                    <?php
                } else if ($option == 'joined')
                {
                    ?>
                    <div class="joined">joined</div>
                    <?php
                } else
                    ?>
            </div>
        </div>
        <?php
    }

    // Returns true if the user is following the specified group
    public function user_is_following($group_id)
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT * FROM group_relationships WHERE group_id = ? AND user_following_id = ?";
        $query = $this->db->query($query_string, array($group_id, $user_id));

        return $query->num_rows() > 0;
    }

    // Returns true if the user has joined the specified group
    public function user_is_joined($group_id)
    {
        $user_id = $this->ion_auth->get_user()->id;

        $query_string = "SELECT * FROM group_relationships WHERE group_id = ? AND user_joined_id = ?";
        $query = $this->db->query($query_string, array($group_id, $user_id));

        return $query->num_rows() > 0;
    }

    // Adds a gruop to the database.
    // If school_id isn't blank, use the latitude and longitude of the school.
    // Returns the newly created group id.
    public function add_group($name, $description, $privacy, $location_source)
    {
        $user = $this->ion_auth->get_user();

        if ($location_source == 'school')
        {
            // Get the latitude and longitude from the school table.
            $query_string = "SELECT latitude, longitude FROM school_data WHERE id = ?";
            $query = $this->db->query($query_string, array($user->school_id));
            $row = $query->row();

            $latitude = $row->latitude;
            $longitude = $row->longitude;
            $school_id = $user->school_id;
        } else
        {
            $latitude = $user->latitude;
            $longitude = $user->longitude;
            $school_id = NULL;
        }
        $query_string = "INSERT INTO groups VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, DEFAULT)";
        $query = $this->db->query($query_string, array($name, $latitude, $longitude, $description, $school_id, $privacy));

        return $this->db->insert_id();
    }

    // Joins the user to the specified group (the user can optionally be specified)
    public function join_group($group_id, $user_id = 'foobar')
    {
        // Make $user_id useful
        if ($user_id == 'foobar')
        {
            $user_id = $this->ion_auth->get_user()->id;
        }

        $query_string = "UPDATE group_relationships " .
                "SET user_following_id = DEFAULT, user_joined_id = ? " .
                "WHERE group_id = ? AND user_following_id = ?";
        $query = $this->db->query($query_string, array(
                    $user_id,
                    $group_id,
                    $user_id,
                ));
    }

    // Follows the user to the specified group (the user can optionally be specified)
    function follow_group($group_id, $user_id = 'foobar')
    {
        // Make $user_id useful
        if ($user_id == 'foobar')
        {
            $user_id = $this->ion_auth->get_user()->id;
        }

        $query_string = "INSERT IGNORE INTO group_relationships VALUES (DEFAULT, ?, ?, DEFAULT)";
        $query = $this->db->query($query_string, array(
                    $group_id,
                    $user_id,
                ));
    }

    // Returns a list of users who joined the supplied groups
    function get_users($group_list)
    {
        var_dump($group_list);
        $return_array = array();

        if (count($group_list) > 0)
        {
            $query_string = "SELECT user_joined_id FROM group_relationships WHERE (";

            foreach ($group_list as $group)
            {
                $query_string .= "group_id = $group OR ";
            }
            $query_string = substr($query_string, 0, -4);
            $query_string .= ") AND user_joined_id <> NULL";

            $query = $this->db->query($query_string);

            foreach ($query->result() as $row)
            {
                $return_array[] = $row->user_joined_id;
            }
        }

        return $return_array;
    }

    // Returns (id, name) pairs of the user's joined groups
    function get_joined_groups_tuples()
    {
        $query_string = "SELECT groups.id, groups.name
            FROM group_relationships LEFT JOIN groups ON group_relationships.group_id = groups.id
            WHERE group_relationships.user_joined_id = ? ORDER BY groups.name ASC";
        $query = $this->db->query($query_string, array($this->ion_auth->get_user()->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = array('id' => $row->id, 'name' => $row->name);
        }

        return $return_array;
    }

}
?>
