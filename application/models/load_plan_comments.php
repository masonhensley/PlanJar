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
        
            <?php
            foreach ($result->result() as $user_comment)
            {
                $this->display_user_comment($user_comment);
            }
            ?>
        
        <?php
        return ob_get_clean();
    }

    function display_user_comment($user_comment)
    {
        $user = $this->ion_auth->get_user($user_comment->user_id);
        $display_time = $user_comment->time;
        $this->load->model('load_profile');
        ?>
        <div class="user_comment" user_id="<?php echo $user_comment->user_id; ?>">
            <div class="user_comment_picture">
                <?php
                $this->load_profile->insert_profile_picture(55);
                ?>
            </div>
            <div class="user_comment_top_bar">
                <?php
                echo $user->first_name . " " . $user->last_name . " says...";
                if($user->user_id == $user_comment->user_id)
                {
                    ?>
                <div class="delete_comment">
                    delete
                </div>
                <?php
                }
                ?>
            </div>

            <div class="user_comment_body">
                <?php
                echo $user_comment->comment;
                ?>
            </div>
        </div>
        <?php
    }

}
?>
