<?php

class Sign_up_ops extends CI_Model
{

    // Constructor.
    function __construct()
    {
        parent::__construct();
    }

    // Returns the school id corresponding to the given email's domain
    public function get_school_from_email($email)
    {
        $domain = substr($email, strrpos($email, '@'));

        $query_string = "SELECT id FROM school_data WHERE email_domain = ?";
        $query = $this->db->query($query_string, array($domain));

        //return $query->row()->id;
        return $this->db->last_query();
    }

}

?>
