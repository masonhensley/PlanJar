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

        // Pre-calculate values
        $birthday = $this->input->get('su_month') . '/' .
                $this->input->get('su_day') . '/' .
                $this->input->get('su_year');
        $this->load->model('sign_up_ops');
        $school_id = $this->sign_up_ops->get_school_from_email($email);

        // Populate the user data array
        $additional_data = array(
            'school_id' => $school_id,
            'sex' => $this->input->get('su_sex'),
            'first_name' => $this->input->get('su_first_name'),
            'last_name' => $this->input->get('su_last_name'),
            'birthday' => $birthday,
            'grad_year' => $this->input->get('su_grad_year')
        );

        $registered = $this->ion_auth->register($email, $password, $email, $additional_data);

        if ($registered)
        {
            // Only necessary to bypass the email activation. Remove when email activation is in place.
            // Get the school's group id
            $this->ion_auth->login($email, $password);
            $user = $this->ion_auth->get_user();
            $query_string = "SELECT group_id FROM school_data WHERE id = ?";
            $query = $this->db->query($query_string, array($user->school_id));
            $group_id = $query->row()->group_id;

            // Join the user to his school's group
            $this->load->model('group_ops');
            $this->group_ops->follow_group($user->id, $group_id);
            $this->group_ops->join_group($user->id, $group_id);


            echo "/home";
            // End email activation stuff
            // Redirect to the post sign up page
            //echo "/login/post_sign_up";
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

    // Returns true if the email is open and of the correct domain.
    // Returns errors otherwise
    public function email_check()
    {
        $email = $this->input->get('su_email_1');

        $query_string = "SELECT * FROM school_data WHERE email_domain = ?";
        $query = $this->db->query($query_string, array(substr($email, strpos($email, '@') + 1)));

        $email_exists = $this->ion_auth->email_check($this->input->get('email'));

        if ($query->num_rows() == 0)
        {
            echo ("If you entered you university address, PlanJar isn't at your school yet, but we're coming!");
        } else if ($email_exists)
        {
            echo('That email address is already in use.');
        } else
        {
            echo('true');
        }
    }

}