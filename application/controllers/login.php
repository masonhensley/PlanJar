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
            'school_id' => $this->input->get('su_school_id'),
            'sex' => $this->input->get('su_sex'),
            'first_name' => $this->input->get('su_first_name'),
            'last_name' => $this->input->get('su_last_name'),
            'birthday' => $birthday,
            'grad_year' => $this->input->get('su_grad_year')
        );

        $registered = $this->ion_auth->register($email, $password, $email, $additional_data);

        if ($registered)
        {
            // Join the user to the school group.
            $this->load->model('group_ops');
            $this->group_ops->follow_group($additional_data['school_id']);
            $this->group_ops->join_group($additional_data['school_id']);

            echo "/login/post_sign_up";
        } else
        {
            echo "error";
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
    public function search_schools()
    {
        $needle = $this->input->get('needle');
        $search_terms = explode(' ', $needle);

        $like_clauses = '';
        foreach ($search_terms as $term)
        {
            $like_clauses .= "`school` LIKE '%%" . $term . "%%' OR ";
        }
        $like_clauses = substr($like_clauses, 0, -4);
        $query = $this->db->query("SELECT `id`, `school`, `city` FROM `school_data` WHERE " . $like_clauses . ' LIMIT 10');

        // Convert the set of results to JSON.
        foreach ($query->result_array() as $row)
        {
            $result_array[] = $row;
        }

        // Return the data.
        echo(json_encode($result_array));
    }

    // Returns true if the username is available, false otherwise.
    public function check_email_unique()
    {
        $email_exists = $this->ion_auth->email_check($this->input->get('email'));

        echo($this->db->last_query());
        if ($email_exists)
        {
            echo('false');
        } else
        {
            echo('true');
        }
    }

    public function get_school_by_id()
    {
        $query_string = "SELECT `school` FROM `school_data` WHERE `id` = ? LIMIT 1";
        $query = $this->db->query($query_string, array($this->input->get('id')));

        if ($query->num_rows() == 0)
        {
            // Return an error if no entries come up.
            echo('error');
        } else
        {
            // Return the first result.
            $row = $query->row_array();
            echo($row['school']);
        }
    }

    // Post sign up message
    public function post_sign_up()
    {
        $this->load->view('post_sign_up_view');
    }

    public function foo()
    {
        $this->load->view('foo');
    }

    public function check_email_domain()
    {
        $email = $this->input->get('email');

        $query_string = "SELECT * FROM school_data WHERE domain = ?";
        $query = $this->db->query($query_string, array(substr($email, strpos($email, '@') + 1)));
        if ($query->num_rows() > 0)
        {
            echo ('allowed');
        } else
        {
            echo('denied');
        }
    }

}