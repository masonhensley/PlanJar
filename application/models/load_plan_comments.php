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
        <textarea id="box_text_area" name="comments" cols="30" rows="3" maxlength="139"  style="height:45px;margin-top:2px;" >Comment me bro</textarea>
        </font>
        <?php
        
        return ob_get_clean();;
    }

}
?>
