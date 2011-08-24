<?php

class Load_attending_list extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function _display_attending_list($plan_id)
    {
        // get the event id for next query
        $query = "SELECT event_id FROM plans WHERE id=$plan_id";
        $result = $this->db->query($query);
        $event_id = $result->row();
        $event_id = $event_id->event_id;

        // select all the people attending the event
        $query = "
        SELECT DISTINCT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school 
        FROM plans 
        JOIN user_meta ON user_meta.user_id=plans.user_id 
        LEFT JOIN school_data ON user_meta.school_id = school_data.id
        WHERE plans.event_id=$event_id
        ";

        $result = $this->db->query($query);
        $this->display_user_list($result);
    }
    
    function _display_group_members($group_id)
    {
        // get group members
        $this->load->model('group_ops');
        $this->group_ops->get_group_members($group_id);

        // select all the people in the group for query
        $query = "
        SELECT DISTINCT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school 
        FROM group_relationships
        JOIN user_meta ON user_meta.user_id=group_relationships.user_joined_id
        LEFT JOIN school_data ON user_meta.school_id = school_data.id
        WHERE group_relationships.group_id=$group_id
        ";
        $query_result = $this->db->query($query);
        
         // echo the user entries 
        $this->load_attending_list->display_user_list($query_result);
    }

    function display_user_list($query_result)
    {
        $this->load->model('follow_ops');
        $follow_ids = $this->follow_ops->get_following_ids(); // load the user's followers to check if they are following the entries
        $user = $this->ion_auth->get_user();
        $count = 0;
        ob_start();

        foreach ($query_result->result() as $row)
        {
            if (in_array($row->user_id, $follow_ids))
            {
                $this->follow_ops->echo_user_entry($row, 'already_following');
            } else if ($row->user_id == $user->id)
            {
                $this->follow_ops->echo_user_entry($row, 'this_is_you');
            } else
            {
                $this->follow_ops->echo_user_entry($row, 'add following');
            }

            $count++;
        }

        echo ob_get_clean();
    }

}