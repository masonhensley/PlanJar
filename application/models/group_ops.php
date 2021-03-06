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

        $user = $this->ion_auth->get_user();
        $query_string = "SELECT id, name,
                ((ACOS(SIN(? * PI() / 180) * SIN(latitude * PI() / 180) 
                + COS(? * PI() / 180) * COS(latitude * PI() / 180) * COS((? - longitude) 
                * PI() / 180)) * 180 / PI()) * 60 * 1.1515) AS distance
                FROM groups
                WHERE MATCH (groups.name) AGAINST (? IN BOOLEAN MODE)
                HAVING distance <= 30
                AND ($already_following)";
        $query = $this->db->query($query_string, array(
            $user->latitude,
            $user->latitude,
            $user->longitude,
            str_replace(' ', '* ', $needle) . '*'));

        // Echo the results
        foreach ($query->result() as $row)
        {
            $this->echo_group_entry($row, 'add following');
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
                        <img src="/application/assets/images/logos/<?php echo rand(1, 6) . '.png'; ?>" style="width:100%; height:100%;" />
                    </div>
                </center>
            </div>
            <div class="group_entry_middle">
                <div class="group_name">
                    <?php
                    echo($row->name);
                    ?>
                </div>
            </div>

            <?php
            if ($option == 'suggested groups' || $option == 'add following')
            {
                ?>
                <div class="not_following">not following</div>
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

            <div style="position:absolute; top:17px; right:2px; font-size:10px; color:green;">
                <?php
                if ($option == 'suggested groups')
                {
                    echo $suggested_groups[$row->id] . "+ connection";
                    if ($suggested_groups[$row->id] > 1)
                    {
                        echo"s";
                    }
                }
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
    public function user_is_joined($group_id, $user_id = 'foo')
    {
        if ($user_id == 'foo')
        {
            $user_id = $this->ion_auth->get_user()->id;
        }

        $query_string = "SELECT * FROM group_relationships WHERE group_id = ? AND user_joined_id = ?";
        $query = $this->db->query($query_string, array($group_id, $user_id));

        return $query->num_rows() > 0;
    }

// Adds a gruop to the database.
// If school_id isn't blank, use the latitude and longitude of the school.
// Returns the newly created group id.
    function add_group($name, $description, $privacy, $location_source)
    {
        $user = $this->ion_auth->get_user();

        if ($location_source == 'school')
        {
            // Check for a pre-existing group
            $query_string = "SELECT school_data.school
                FROM groups JOIN school_data ON groups.school_id = school_data.id
                WHERE groups.name = ? AND groups.school_id = ?";
            $query = $this->db->query($query_string, array($name, $user->school_id));

            if ($query->num_rows() > 0)
            {
                return array(
                    'status' => 'conflict',
                    'message' => 'A group with that name already exists within the ' . $query->row()->school . ' network.');
            }

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

            // Check for a pre-existing group within 20 miles
            $query_string = "SELECT id
            FROM groups
            WHERE name = ? AND school_id = ? AND
            ((ACOS(SIN(? * PI() / 180) * SIN(latitude * PI() / 180) 
            + COS(? * PI() / 180) * COS(latitude * PI() / 180) * COS((? - longitude) 
            * PI() / 180)) * 180 / PI()) * 60 * 1.1515) <= 20";
            $query = $this->db->query($query_string, array(
                $latitude,
                $latitude,
                $longitude,
                $name,
                NULL
                    ));
            if ($query->num_rows() > 0)
            {
                return array(
                    'status' => 'conflict',
                    'message' => 'A group with that name already exists near you.');
            }
        }

        // No conflicts
        $query_string = "INSERT INTO groups VALUES (DEFAULT, ?, ?, ?, ?, ?, ?, DEFAULT)";
        $query = $this->db->query($query_string, array($name, $latitude, $longitude, $description, $school_id, $privacy));

        return array('status' => 'success', 'group_id' => $this->db->insert_id());
    }

// Joins the user to the specified group (the user can optionally be specified)
    public function join_group($group_id, $user_id = false)
    {
        // Make $user_id useful
        if ($user_id === false)
        {
            $user_id = $this->ion_auth->get_user()->id;
        }

        $this->follow_group($group_id, $user_id);

        $query_string = "UPDATE group_relationships
            SET user_following_id = DEFAULT, user_joined_id = ?
            WHERE group_id = ? AND user_following_id = ?";
        $query = $this->db->query($query_string, array(
            $user_id,
            $group_id,
            $user_id,
                ));

        // Mark all associated group notifications as read and accepted
        $query_string = "UPDATE notifications SET viewed = 1, accepted = 1
            WHERE (type = ? AND user_id = ?) OR (type = ? AND originator_id = ?) AND subject_id = ?";
        $query = $this->db->query($query_string, array(
            'group_invite',
            $user_id,
            'join_group_request',
            $user_id,
            $group_id
                ));
    }

// Follows the user to the specified group (the user can optionally be specified)
    function follow_group($group_id, $user_id = false)
    {
        // Make $user_id useful
        if ($user_id === false)
        {
            $user_id = $this->ion_auth->get_user()->id;
        }

        $query_string = "INSERT IGNORE INTO group_relationships
            SELECT 'DEFAULT', ?, ?, NULL
            FROM DUAL
            WHERE NOT EXISTS (
            SELECT id
            FROM group_relationships
            WHERE group_id = ? AND user_joined_id = ?)";
        $query = $this->db->query($query_string, array(
            $group_id,
            $user_id,
            $group_id,
            $user_id
                ));
    }

// Returns a list of users who joined the supplied group
    function get_group_members($group_id)
    {
        $return_array = array();

        $query_string = "SELECT user_joined_id FROM group_relationships WHERE
            group_id = ? AND user_joined_id <> 'NULL'";
        $query = $this->db->query($query_string, array($group_id));

        foreach ($query->result() as $row)
        {
            $return_array[] = $row->user_joined_id;
        }

        return $return_array;
    }

// Returns (id, name) pairs of the user's joined groups
    function get_joined_groups_tuples()
    {
        $query_string = "SELECT groups.id, groups.name
            FROM group_relationships LEFT JOIN groups ON group_relationships.group_id = groups.id
            WHERE group_relationships.user_joined_id = ? AND groups.school_group = 0
            ORDER BY groups.name ASC";
        $query = $this->db->query($query_string, array($this->ion_auth->get_user()->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = array('id' => $row->id, 'name' => $row->name);
        }

        return $return_array;
    }

// Removes all trace of a group
    function delete_group($group_id)
    {
        // Delete the group
        $query_string = "DELETE FROM groups WHERE id = ?";
        $query = $this->db->query($query_string, array($group_id));

        // Delete the followers
        $query_string = "DELETE FROM group_relationships WHERE group_id = ?";
        $query = $this->db->query($query_string, array($group_id));

        // Delete corresponding notifications
        $query_string = "DELETE FROM notifications WHERE (type = ? OR type = ?) AND subject_id = ?";
        $query = $this->db->query($query_string, array(
            'group_invite',
            'join_group_request',
            $group_id));
    }

}
?>
