<?php

class Load_groups extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Returns a list of [id, name] pairs for each group id in the argument.
    // Accepts a list.
    function get_groups($group_id_list)
    {
        $this->load->database();

        $return_array = array();

        if (count($group_id_list) == 0)
        {
            $where_clause = implode("' OR id = '", $group_id_list);
            $query_string = "SELECT id, name FROM groups WHERE id = $where_clause";
            $query = $this->db->query($query_string);

            foreach ($query->result() as $row)
            {
                $return_array[] = array('id' => $row->id, 'name' => $row->name);
            }
        }

        //return $return_array;
        return array('id' => 0, 'name' => $query_string);
    }

}

?>
