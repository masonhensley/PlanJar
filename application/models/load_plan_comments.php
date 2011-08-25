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
        <font style="color:darkgray;">
        
        <textarea id="comment_area" name="comments" cols="30" rows="3" maxlength="139"  style="float:left;height:45px;margin-top:2px;color:gray" >Leave a comment for this event...</textarea>
        
        </font>
        <div class="submit_comment">Submit</div>
        <?php
        return ob_get_clean();
    }

}
?>
