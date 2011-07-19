<?php

class Load_suggested_friends extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function suggested_friends($user_id, $grad_year, $school_id)
    {
        $number_of_results = 0; // this keeps track of the number of items displayed to it can be limited to 15 (or whatever)
        $display_limit = 15; // the max number of results that can be displayed for suggested friends
        $friends_query = "SELECT follow_id FROM friends where user_id=$user_id"; // query pulls all people you are following
        $friends_following_result = $this->db->query($friends_query);
        $suggested_friends = array();
        $already_following = array(); // keep track of the people you are already following

        if ($friends_following_result->num_rows() > 0) // if you are following 1 or more people
        {

            $friend_of_friend_query = "SELECT follow_id FROM friends WHERE "; // generate query to find all friends of friends
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
                if($number_of_results > 0)
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
        foreach ($already_following as $friend_id)
        {
            $query .= " AND user_id!=$friend_id";
        }
        $query .= " AND (user_meta.grad_year=$grad_year OR user_meta.grad_year=" .$grad_year+1 ." OR user_meta.grad_year=" .$grad_year-1 
        ." LIMIT 0, 15";
        $result = $this->db->query($query);
        $options = "suggested_school";
        if($result->num_rows() >0)
        {
                     echo "<div style=\"padding-top:5px; text-align:center;padding-top:10px;padding-bottom:10px;\">Could not find any mutual connections<br/>
                         Expanded search results to include people from your school</div>";
        }
        $this->display_suggested_friends($result, null, $options, 15);
    }

}

?>