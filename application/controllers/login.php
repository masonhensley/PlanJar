<?php

// prevent direct script access
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller
{

    // Index function runs first when site is loaded
    public function index()
    {

        // Check if user is logged in
        // if user is not logged in, load login.php
        if (!$this->ion_auth->logged_in())
        {
            $this->load->view('login_view');
        } else
        {
            // if user is logged in, redirect to planjar.com/home/
            redirect('/home/', 'refresh');
        }
    }

    // Signs up a user
    // Returns 'success' or 'failure'
    // $additional_data must be a JSON array multidimentional array.
    // This function is called upon a successful submit
    public function try_sign_up()
    {
        $email = $this->input->get('su_email_1');
        $password = $this->input->get('su_password');

        // populate the associative array additional_data with sex/college/birth info
        $birthday = $this->input->get('su_month') . '/' .
                $this->input->get('su_day') . '/' .
                $this->input->get('su_year');

        $additional_data = array(
            'school' => $this->input->get('su_school'),
            'sex' => $this->input->get('su_sex'),
            'first_name' => $this->input->get('su_first_name'),
            'last_name' => $this->input->get('su_last_name'),
            'birthday' => $birthday,
            'grad_year' => $this->input->get('su_grad_year')
        );

        $registered = $this->ion_auth->register($email, $password, $email, $additional_data);

        if ($registered)
        {
            echo "success";
        } else
        {
            if (!$this->ion_auth->username_check($email))
            {
                echo "username_error";
            }
        }
    }

    public function try_log_in()
    {
        $email = $this->input->get('li_email');
        $password = $this->input->get('li_password');
        $remember = $this->input->get('li_remember');

        if ($remember)
        {
            $remember = true;
        } else
        {
            $remember = false;
        }

        $logged_in = $this->ion_auth->login($email, $password, $remember);

        if (!$logged_in)
        {
            echo "error";
        } else
        {
            echo "/home/";
        }
    }
    
    // Returns a list of schools (in JSON format) that match the needle string.
    public function search_schools() {
        
        $this->load->database();
        $needle = $this->input->get('needle');
        $search_terms = explode(' ', $needle);
        
        $like_clauses = '';
        foreach($search_terms as $term) {
            $like_clauses .= "`school` LIKE '%%" . $needle . "%%' OR ";
        }
        $like_clauses = substr($like_clauses, 0, -4);
        $query = $this->db->query("SELECT `school`, `city` FROM `school_data` WHERE " . $like_clauses . 'LIMIT 10');
        
        // Convert the set of results to JSON.
        foreach ($query->result_array() as $row) {
            $result_array[] = $row;
        }
        
        // Return the data.
        echo (json_encode($result_array));
    }

}