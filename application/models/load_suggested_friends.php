<?php

class Load_suggested_friends extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function suggested_friends($user_id, $grad_year, $school_id)
    {

        $user = $this->ion_auth->get_user();

        // new query that selects all the followers of your followers
        // not DISTINCT because we want duplicates for followers of followers
        $connection_query = "
            SELECT friend_relationships.follow_id FROM
                (SELECT friend_relationships.follow_id AS friend_id FROM friend_relationships 
                WHERE friend_relationships.user_id=$user->id)new_user
            JOIN friend_relationships ON friend_relationships.user_id=new_user.friend_id 
                AND friend_relationships.follow_id <> $user->user_id  
";
        $result_array = $this->db->query($connection_query);

        // query to pull all your classmates
        $schoolmate_query = "
            SELECT DISTINCT user_meta.user_id FROM user_meta
            WHERE user_meta.school_id=$user->school_id 
                AND user_meta.grad_year=$user->grad_year
                AND user_meta.user_id <> $user->user_id
            ";
        $result_array_2 = $this->db->query($schoolmate_query);

        // query to pull all your groupmates not including you 
        $groupmate_query = "
            SELECT DISTINCT group_relationships.user_joined_id FROM
                (SELECT group_relationships.group_id AS id FROM group_relationships
                WHERE group_relationships.user_joined_id=$user->id)group_joined_id
            JOIN group_relationships ON group_relationships.group_id=group_joined_id.id
            WHERE group_relationships.user_joined_id <> $user->id
            ";
        $result_array_3 = $this->db->query($groupmate_query);

        // combine the 3 arrays here into one array called "connection array"
        $connection_array = array();
        foreach ($result_array->result() as $row)
        {
            $connection_array[] = $row->follow_id;
        }
        foreach ($result_array_2->result() as $row)
        {
            $connection_array[] = $row->user_id;
        }
        foreach ($result_array_3->result() as $row)
        {
            $connection_array[] = $row->user_joined_id;
        }

        // remove ids of peope you are already following
        $this->load->model('follow_ops');
        $following_ids = $this->follow_ops->get_following_ids();
        $suggested_friends = array();
        foreach ($connection_array as $id)
        {
            if (!in_array($id, $following_ids))
            {
                $suggested_friends[] = $id;
            }
        }

        $suggested_friends = array_count_values($suggested_friends);
        asort($suggested_friends);
        $suggested_friends = array_reverse($suggested_friends, TRUE);
        $display_limit = 400; // set the display limit
        $result = $this->generate_suggested_friends($suggested_friends);
        if ($result)
        {
            $this->display_suggested_friends($result, $suggested_friends, 'suggested', $display_limit);
        } else
        {
            ?>
            <center><i><font style="color:gray;">Nothing to show</font></i></center><br/>
            <?php
        }
    }

    function display_suggested_friends($query_result, $suggested_friends, $options, $display_limit) //this function displays the suggested friends
    {
        // this won't work until we delete the database again 
        // because there are group relationships of people who have been deleted
        if ($query_result->num_rows() > 0)
        {
            $this->load->model('follow_ops');
            $count = 0;
            foreach ($query_result->result() as $row)
            {
                if ($count < $display_limit)
                {
                    $this->follow_ops->echo_user_entry($row, $options, $suggested_friends);
                }
                $count++;
            }
        } else
        {
            ?>
            <center><i><font style="color:gray;">Nothing to show</font></i></center><br/>
            <?php

        }
    }

    function generate_suggested_friends($suggested_friends)
    {
        if (count($suggested_friends) > 0)
        {
            // this query pulls all the information needed to display suggested friends
            $query = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                    "FROM user_meta LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                    "WHERE  ";
            $mutual_friend_count = array(); // keep track of mutual friends to display
            foreach ($suggested_friends as $id => $count)
            {
                $query .= "user_meta.user_id=$id OR ";
            }
            $query = substr($query, 0, strlen($query) - 3); // This cuts off the last "OR" and adds ")"
            $query .= "ORDER BY CASE user_meta.user_id ";
            $counter = 1;
            foreach ($suggested_friends as $id => $count)
            {
                $query .= "WHEN $id THEN $counter ";
                $counter++;
            }
            $query .= "END";
            return $this->db->query($query);
        } else
        {
            return false;
        }
    }

}
?>