<?php

Class Load_plan_comments extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function display_comments($plan_id)
    {
        ob_start();
        ?>
        <textarea id="comment_area" name="comments" cols="30" rows="3" maxlength="139">Leave a comment for this event...</textarea>
        <div class="submit_comment">Submit</div>
        <?php
        return ob_get_clean();
    }

}
?>
