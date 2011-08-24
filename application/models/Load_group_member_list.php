<?php

class Load_group_member_list extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function _display_group_members($group_id)
    {
        // get group members
        $this->load->model('group_ops');
        $this->group_ops->get_group_members($group_id);

        // select all the people in the group for query
        $query = "
        SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school 
        FROM plans 
        JOIN user_meta ON user_meta.user_id=plans.user_id 
        LEFT JOIN school_data ON user_meta.school_id = school_data.id
        WHERE plans.event_id=$event_id
        ";
        $query_result = $this->db->query($query);
        
         // echo the user entries 
        $this->load->model('load_attending_list');
        $this->load_attending_list->display_user_list($query_result);
    }

}

?>
