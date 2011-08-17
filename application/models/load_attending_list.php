<?php

class Load_plan_data extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function _display_attending_list()
    {
        // get #attending
        $query = "SELECT event_id FROM plans WHERE id=$plan_id";
        $result = $this->db->query($query);
        $event_id = $result->row()->event_id;

        // select all the people attending the event
        $query = "
        SELECT user_meta.user_id, user_meta.first_name, user_meta.last_name, user_meta.grad_year, school_data.school 
        FROM plans JOIN user_meta ON user_meta.user_id=plans.user_id 
        WHERE plans.event_id=$event_id
        ";

        $result = $this->db->query($query);
        $this->display_attending_list($result);
    }

    function display_attending_list($query_result)
    {
        $this->load->model('follow_ops');
        $follow_ids = $this->follow_ops->get_follow_ids();

        $count = 0;
        ?>

        <div id="friends_plans_panel" class="modal" style="left:43%; top:19%;">
            <div class="title_bar">
                Friends
                <input  type="button" id="cancel_friends_panel"  style="float:right;" value="X"/>
            </div>
            <div id="friend_modal_content">
                <br/>
                Select a friend to view their upcoming plans
                <br/><hr/>
                <div class="friend_list">

                    <?php
                    foreach ($query_result->result() as $row)
                    {
                        if (in_array($row->user_id, $follow_ids))
                        {
                            $this->follow_ops->echo_user_entry($row, 'already_following');
                        } else
                        {
                            $this->follow_ops->echo_user_entry($row, 'suggested');
                        }

                        $count++;
                    }
                    ?>
                </div>
            </div>
        </div>
        <?php
    }

}