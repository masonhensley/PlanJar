<?php

class Load_suggested_friends extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function suggested_friends($user_id)
    {
        $friends_query = "SELECT follow_id FROM friends where user_id=$user_id"; // query pulls all people you are following
        $friends_following_result = $this->db->query($friends_query);
        $suggested_friends = array();
        if ($friends_following_result->num_rows() > 0) // if you are following 1 or more people
        {
            $already_following = array(); // keep track of the people you are already following
            $friend_of_friend_ids = $this->find_friends_of_friends($friends_following_result, &$already_following);
            $friend_of_friend_list = array();  // keep track of friend of friend ids

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
                asort($suggested_friends); // this sorts the array by count
                $suggested_friends = array_reverse($suggested_friends, TRUE);  // this orders the array descending 

                $result = $this->generate_suggested_friends($friend_of_friend_list, $suggested_friends);
                $this->display_suggested_friends($result, $suggested_friends, $options);
            }
        } else
        {
            $group_suggestions = $this->find_group_suggestions($user_id);
            
            $this->show_suggested_school_friends($user_id); // in the case that you are not following anyone, and there are no mutual followers
        }
    }

    function display_suggested_friends($query_result, $suggested_friends=null, $options) //this function displays the suggested friends
    {
        $this->load->model('follow_ops');
        foreach ($query_result->result() as $row)
        {
            $this->follow_ops->echo_user_entry($row, $options, $suggested_friends);
        }
    }

    function find_friends_of_friends($friend_of_friend_result)
    {
        $friend_of_friend_query = "SELECT follow_id FROM friends WHERE "; // generate query to find all friends of friends
        
        foreach ($friend_of_friend_result->result() as $friend_id)
        {
            $already_following[] = $friend_id->follow_id; // update $already_following id array
            $friend_of_friend_query .= "user_id=$friend_id->follow_id OR "; // long or clause for each friend id
        }
        $friend_of_friend_query = substr($friend_of_friend_query, 0, strlen($friend_of_friend_query) - 4); // This cuts off the last "OR" and adds ")"
        $friend_of_friend_ids = $this->db->query($friend_of_friend_query);
        return $friend_of_friend_ids;
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

    function show_suggested_school_friends($user_id)
    {
        echo "<div style=\"padding-left:25px;padding-right:25px;\"=>Could not find second-degree relationships.  Expanded search results to include your school</div>";

        $query = "SELECT user_meta.school_id FROM user_meta
             LEFT JOIN school_data ON school_data.id=user_meta.school_id WHERE user_meta.user_id=$user_id";
        $result = $this->db->query($query);
        $row = $result->row();
        $school_id = $row->school_id;

        $query = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school
            FROM user_meta 
            LEFT JOIN school_data ON user_meta.school_id=school_data.id
            WHERE school_id=$school_id AND user_id!=$user_id LIMIT 0, 10";
        $result = $this->db->query($query);
        $options = "suggested_school";
        $this->display_suggested_friends($result, null, $options);
    }
    
    function find_group_suggestions($user_id)
    {
        // left off here, find group friends
        $query = "SELECT user_id FROM friends WHERE follow_id=$user_id";
    }

}

?>