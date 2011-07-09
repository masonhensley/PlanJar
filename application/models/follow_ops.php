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
            $first_name_where = '';
            $last_name_where = '';
            foreach ($needle_array as $cur_needle)
            {
                $first_name_where .= "user_meta.first_name LIKE '%%$cur_needle%%' OR ";
                $last_name_where .= "user_meta.last_name LIKE '%%$cur_needle%%' OR ";
            }

            // Trim the end of the strings
            if (count($needle_array) > 0)
            {
                $first_name_where = substr($first_name_where, 0, -4);
                $last_name_where = substr($last_name_where, 0, -4);
            }

            $query_string = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                    "FROM user_meta LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                    "WHERE ($first_name_where) OR ($last_name_where) AND user_meta.user_id <> " . $user->id;
            $query = $this->db->query($query_string);

            // Echo the results
            foreach ($query->result() as $row)
            {
                $this->user_follow_entry($row, 'add following');
            }
        }
    }

    // Echos a user_follow_entry, which is used as a following/follower list item
    function user_follow_entry($row, $option = '')
    {
        ?>
        <div class="user_follow_entry" user_id="<?php echo($row->user_id); ?>">
            <div class="user_follow_entry_left">
                <center>
                    <div class="user_picture"></div>

                    <div class="grad_year">
                        <?php echo('Class of ' . $row->grad_year); ?>
                    </div>
                </center>
            </div>

            <div class="user_follow_entry_middle">
                <div class="user_name">
                    <?php echo($row->first_name . ' ' . $row->last_name); ?>
                </div>

                <div class="user_school">
                    <?php echo($row->school); ?>
                </div>
            </div>
            <?php
            if ($option == 'remove following')
            {
                ?>
                <div class="remove_following">- Unfollow</div>
                <?php
            } else if ($option == 'add following')
            {
                ?>
                <div class="add_following">+ Follow</div>
                <?php
            }
            ?>
        </div>
        <?php
    }

}
?>
