<?php

class Load_suggested_groups extends CI_Model
{
    function __contsruct()
    {
        parent::construct();
    }
    // returns an html string of groups suggested to follow
    function suggested_groups()
    {
        $this->get_users_following();
    }
    function get_users_following()
    {
        $user = $this->ion_auth->get_user();
        $query = "SELECT follow_id FROM friends where user_id=$user->id";
        $result = $this->db->query($query);
        var_dump($result);
    }
}

?>
