<?php

class Load_suggested_groups extends CI_Model
{

    function __contsruct()
    {
        parent::construct();
    }

    // returns an html string of groups suggested to follow
    function suggested_groups()
    {
        $users_following = $this->get_users_following();
        if (count($users_following) > 0)
        {
            $suggested_groups = $this->get_suggested_groups($users_following);
            $suggested_groups = array_count_values($suggested_groups);
            $number_of_results = count($suggested_groups);
            asort($suggested_groups);
            $suggested_groups = array_reverse($suggested_groups, TRUE);
            var_dump($suggested_groups);
            $this->generate_suggested_groups($suggested_groups);
        }
    }

    function get_users_following()
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT follow_id FROM friends WHERE user_id=$user->id";
        $result = $this->db->query($query);
        $users_following = array();
        foreach ($result->result() as $user)
        {
            $users_following[] = $user->follow_id;
        }
        return $users_following;
    }

    function get_suggested_groups($users_following)
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT group_id FROM group_relationships 
            WHERE ";
        $tracker = 0;
        foreach ($users_following as $id)
        {
            if ($tracker == 0)
            {
                $query .= "user_following_id=$id OR user_joined_id=$id ";
            } else if ($tracker == count($users_following) - 1)
            {
                $query .= "OR user_following_id=$id OR user_joined_id=$id";
            } else
            {
                $query .= "OR user_following_id=$id OR user_joined_id=$id ";
            }
            $tracker++;
        }

        $result = $this->db->query($query);
        $group_results = array();
        foreach ($result->result() as $group_id)
        {
            $group_results[] = $group_id->group_id;
        }
        return $group_results;
    }

    function generate_suggested_groups($suggested_groups)
    {
        $group_list = array();

        $query = "SELECT id, name FROM groups WHERE ";
        $counter = 1; // keeps track of the order of groups to be displayed
        $or_clause = "";
        $when_clause = "ORDER BY CASE id ";

        
        foreach ($suggested_groups as $id => $count)
        {
            $or_clause .= "id=$id OR ";
            $when_clause .= "WHEN $id THEN $counter ";
            $group_list[] = $id;
            $counter++;
        }
        
        $when_clause .= "END";
        $query .= $or_clause .$when_clause;
        var_dump($query);        
        
    }

}

?>
