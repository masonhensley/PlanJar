<?php

class Load_groups extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function joined_groups()
    {
        $user = $this->ion_auth->get_user();
        $query_string = "SELECT group_relationships.group_id, groups.name FROM group_relationships LEFT JOIN groups ON group_relationships.group_id = group.id " .
                "WHERE group_relationships.user_joined_id = ?";
        $query = $this->db->query($query_string, array($user->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = array('id' => $row->group_id,
                'name' => $row->name);
        }

        return $return_array;
    }

    function followed_groups()
    {
        $user = $this->ion_auth->get_user();
        $query_string = "SELECT group_relationships.group_id, groups.name FROM group_relationships LEFT JOIN groups ON group_relationships.group_id = group.id " .
                "WHERE group_relationships.user_following_id = ?";
        $query = $this->db->query($query_string, array($user->id));

        $return_array = array();
        foreach ($query->result() as $row)
        {
            $return_array[] = array('id' => $row->group_id,
                'name' => $row->name);
        }

        return $return_array;
    }

}

?>
