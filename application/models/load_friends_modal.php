<?php

class Load_friends_modal extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    function load_friends_panel()
    {
        ?>
        <div id="friends_plans_panel" class="modal">
            <div class="title_bar">
                <input  type="button" id="cancel_plan"  style="float:right;" value="X"/>
            </div>
        </div> 
        <?php
    }

}
?>