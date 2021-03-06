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
                SELECT comment, user_id, time, id
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
        $logged_in_user = $this->ion_auth->get_user();
        $comment_user = $this->ion_auth->get_user($user_comment->user_id);

        $display_time = date("F j, Y, g:i a", strtotime($user_comment->time));

        $this->load->model('load_profile');

        $tracker = 0;
        ?>
        <div class="user_comment" comment_id="<?php echo $user_comment->id; ?>">
            <div class="user_comment_picture">
                <a href="/dashboard/following/<?php echo $user_comment->user_id; ?>">
                    <?php
                    $this->load_profile->insert_profile_picture($user_comment->user_id, 55);
                    ?>
                </a>
            </div>
            <div class="user_comment_top_bar">
                <a href="/dashboard/following/<?php echo $user_comment->user_id; ?>">
                    <?php echo $comment_user->first_name . " " . $comment_user->last_name; ?>
                </a>
                <?php
                echo "says...";
                ?>

            </div>


            <div class="user_comment_body">
                <?php
                echo $user_comment->comment;
                if ($logged_in_user->user_id == $user_comment->user_id)
                {
                    ?>
                    <div class="delete_comment">
                        delete
                    </div>
                    <?php
                }
                ?>
            </div>
            <div class="comment_display_time">
                <?php
                echo $display_time;
                ?>
            </div>
        </div>
        <?php
    }

}
?>
