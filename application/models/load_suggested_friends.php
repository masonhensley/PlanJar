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
        $connection_query = "
            SELECT friend_relationships.follow_id FROM
                (SELECT friend_relationships.follow_id AS friend_id FROM friend_relationships 
                WHERE friend_relationships.user_id=$user->id)new_user
            JOIN friend_relationships ON friend_relationships.user_id=new_user.friend_id 
                AND friend_relationships.follow_id <> $user->user_id  
";
        $result = $this->db->query($connection_query);
        $result_array = $result->row_array();

        // query to pull all your classmates
        $schoolmate_query = "
            SELECT user_meta.user_id FROM user_meta
            WHERE user_meta.school_id=$user->school_id 
                AND user_meta.grad_year=$user->grad_year
                AND user_meta.user_id <> $user->user_id
            ";
        $result = $this->db->query($schoolmate_query);
        $result_array_2 = $reuslt->row_array();

        // query to pull all your groupmates
        $groupmate_query = "
            SELECT group_relationships.user_joined_id FROM
                (SELECT group_relationships.group_id AS id FROM group_relationships
                WHERE group_relationships.user_joined_id=$user->id)group_joined_id
            JOIN group_relationships ON group_relationships.group_id=group_joined_id.id
            ";
        $result = $this->db->query($groupmate_query);
        $groupmate_result = $result->row_array();

        // combine the 3 arrays here into one array called "connection array"

        $connection_array = array();
        $connection_array = array_count_values($connection_array);
        $number_results = count($connection_array);
        asort($connection_array);
        $suggested_friends = array_reverse($connection_array, TRUE);
        if ($number_results > 0)
        {
            // generate suggested friends and show results
        }




        // old code starts here
        
        $number_of_results = 0; // this keeps track of the number of items displayed to it can be limited to 15 (or whatever)
        $display_limit = 15; // the max number of results that can be displayed for suggested friends
        $friends_query = "SELECT follow_id FROM friend_relationships WHERE user_id=$user_id"; // query pulls all people you are following
        $friends_following_result = $this->db->query($friends_query);
        $suggested_friends = array();
        $already_following = array(); // keep track of the people you are already following

        if ($friends_following_result->num_rows() > 0) // if you are following 1 or more people
        {

            $friend_of_friend_query = "SELECT follow_id FROM friend_relationships WHERE "; // generate query to find all friends of friends
            foreach ($friends_following_result->result() as $friend_id)
            {
                $already_following[] = $friend_id->follow_id; // update $already_following id array
                $friend_of_friend_query .= "user_id=$friend_id->follow_id OR "; // long or clause for each friend id
            }
            $friend_of_friend_query = substr($friend_of_friend_query, 0, strlen($friend_of_friend_query) - 4); // This cuts off the last "OR" and adds ")"
            $friend_of_friend_ids = $this->db->query($friend_of_friend_query);

            $friend_of_friend_list = array();  // keep track of friend of friend ids

            /* ----------- MORE THAN 1 2nd DEGREE CONNECTIONS----------- */

            if ($friend_of_friend_ids->num_rows() > 0) // if there are more than 1 2nd degree connections
            {
                foreach ($friend_of_friend_ids->result() as $friend_of_friend_id)
                {
                    if ($friend_of_friend_id->follow_id != $user_id && !in_array($friend_of_friend_id->follow_id, $already_following)) // this makes sure your user_id or anyone you are already following is not added to the list
                    {
                        $friend_of_friend_list[] = $friend_of_friend_id->follow_id;
                    }
                }
                $suggested_friends = array_count_values($friend_of_friend_list);     // this turns the array of follow ids into an associative array structured: $user_id => $count
                $number_of_results += count($suggested_friends);
                asort($suggested_friends); // this sorts the array by count
                $suggested_friends = array_reverse($suggested_friends, TRUE);  // this orders the array descending, TRUE parameter keeps the indices 
                if ($number_of_results > 0)
                {
                    $result = $this->generate_suggested_friends($friend_of_friend_list, $suggested_friends);
                    $this->display_suggested_friends($result, $suggested_friends, 'suggested', $display_limit);
                }
            }
        }
        /* --------------- BASE CASE SHOWS PEOPLE WHO GO TO SAME SCHOOL------------- */
        if ($number_of_results <= $display_limit)
        {
            $new_limit = $display_limit - $number_of_results;  // takes the difference so the number to display is always the same
            $this->show_suggested_school_friends($new_limit, $already_following); // in the case that you are not following anyone, and there are no mutual followers
        }
    }

    function display_suggested_friends($query_result, $suggested_friends=null, $options, $display_limit) //this function displays the suggested friends
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
    }

    function generate_suggested_friends($friend_of_friend_list, $suggested_friends)
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
    }

    function show_suggested_school_friends($display_limit, $already_following)
    {

        $user = $this->ion_auth->get_user();
        $user_id = $user->id;
        $grad_year = $user->grad_year;
        $school_id = $user->school_id;

        $query = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school
            FROM user_meta 
            LEFT JOIN school_data ON user_meta.school_id=school_data.id
            WHERE school_id=$school_id AND user_id!=$user_id";

        foreach ($already_following as $friend_id) // this makes sure the user hasn't already been shown
        {
            $query .= " AND user_id!=$friend_id";
        }

        $date1 = date("Y");
        $date2 = $date1 + 4;
        $query .= " AND (user_meta.grad_year BETWEEN $date1 AND $date2) ";
        $query .= "ORDER BY (user_meta.grad_year=$grad_year) DESC LIMIT 0, 30";

        $result = $this->db->query($query);

        $options = "suggested_school";
        if ($result->num_rows() > 0)
        {
            echo "<div style=\"padding-top:5px; text-align:center;padding-top:10px;padding-bottom:10px;font-style:italic;border-top:1px solid #AAA;\">Expanded search results to include people from your school</div>";
        }
        $this->display_suggested_friends($result, null, $options, 15);
    }

}

?>