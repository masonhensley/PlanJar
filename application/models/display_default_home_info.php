<?php

class Display_default_info extends CI_Model
{
    function setup_default_view($day)
    {
        echo "this is information pertaining to nashville (for $day) since no groups or locations are selected";
    }
}

?>
