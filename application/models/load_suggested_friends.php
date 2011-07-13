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
        $result = $this->db->query($friends_query);

        $friend_of_friend_query = "SELECT follow_id FROM friends WHERE "; // generate query to find all friends of friends
        foreach ($result->result() as $friend_id)
        {
            $friend_of_friend_query .= "user_id=$friend_id->follow_id OR "; // long or clause for each friend id
        }
        $friend_of_friend_query = substr($friend_of_friend_query, 0, strlen($friend_of_friend_query) - 4); // This cuts off the last "OR" and adds ")"
        $friend_of_friend_ids = $this->db->query($friend_of_friend_query);

        $friend_of_friend_list = array();  // keep track of friend of friend ids
        foreach ($friend_of_friend_ids->result() as $friend_of_friend_id)
        {
            if ($friend_of_friend_id->follow_id != $user_id) // this makes sure your user_id is not added to the list
            {
                $friend_of_friend_list[] = $friend_of_friend_id->follow_id;
            }
        }
        $suggested_friends = array_count_values($friend_of_friend_list);     // this turns the array of follow ids into an associative array structured: $user_id => $count
        asort($suggested_friends); // this sorts the array by count
        $suggested_friends = array_reverse($suggested_friends, TRUE);  // this orders the array descending 


        $query = "SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school " .
                "FROM user_meta LEFT JOIN school_data ON user_meta.school_id = school_data.id " .
                "WHERE  ";

        foreach ($suggested_friends as $id => $count)
        {
            $query .= "user_meta.user_id=$id OR ";
        }
        $query = substr($query, 0, strlen($query) - 4); // This cuts off the last "OR" and adds ")"
        $result = $this->db->query($query);

        $this->load->model('follow_ops');
        foreach ($result->result() as $row)
        {
            $this->follow_ops->echo_user_entry($row, 'add following');
        }

        return "";
    }
}

?>
