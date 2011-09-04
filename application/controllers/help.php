<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Help extends CI_Controller
{

    public function index()
    {
        header("Location: http://www.faqme.com/planjar");
    }

}

?>
