<?php

Class Load_plan_comments extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function display_comments($plan_id)
    {
        // get the event_id
        $query = "SELECT event_id FROM plans WHERE id=$plan_id";
        $result = $this->db->query($query);
        $row = $result->row();
        $event_id = $row->event_id;

        // select the comments for the given event
        $query = "
                SELECT comment, user_id, time
                FROM plan_comments
                WHERE event_id=$event_id
                ORDER BY time ASC
                ";
        $result = $this->db->query($query);

        ob_start();
       foreach($result->result() as $user_comment)
       {
           echo $user_comment->comment ."<br/><br/>";
       }
        return ob_get_clean();
    }

}
?>
