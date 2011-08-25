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
                ORDER BY time DESC
                ";
        $result = $this->db->query($query);

        ob_start();
        ?>
        <div class="user_comments_section">
            <?php
            foreach ($result->result() as $user_comment)
            {
                $this->display_user_comment($user_comment);
            }
            ?>
        </div>
        <?php
        return ob_get_clean();
    }

    function display_user_comment($user_comment)
    {
        ?>
        <div class="user_comment">
            <div class="user_comment_picture">

            </div>
            <div class="user_comment_top_bar">
            </div>

            <div class="user_comment_body">

            </div>
        </div>
        <?php
    }

}
?>
