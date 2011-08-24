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
        <textarea id="box_text_area" name="comments" cols="30" rows="4" maxlength="139">Enter a comment yo</textarea>
        </font>
        <?php
        return ob_get_clean();
    }

}
?>
