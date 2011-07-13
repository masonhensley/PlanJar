<?php
class Load_suggested_friends extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    function suggested_friends($user_id)
    {
        $friends_query = "SELECT follow_id FROM friends where user_id=$user_id";
        $result = $this->db->query($friends_query);
        $friend_of_friend_query = "SELECT follow_id FROM friends WHERE ";
        foreach($result->result() as $friend_id)
        {
            $friend_of_friend_query .= "user_id=$friend_id->follow_id OR ";
        }
        $friend_of_friend_query  = substr($friend_of_friend_query , 0, strlen($friend_of_friend_query ) - 4); // This cuts off the last "OR" and adds ")"
        $friend_of_friend_ids = $this->db->query($friend_of_friend_query);
        
        // keep track of friend of friend ids
        $friend_of_friend_list = array();
        foreach($friend_of_friend_ids->result() as $friend_of_friend_id)
        {
            $friend_of_friend_list[] = $friend_of_friend_id->follow_id;
        }
        
        $suggested_friends = array_count_values($friend_of_friend_list);
        
        return "$suggested_friends";
    }
}
?>
