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
            $this->load->view('login_view', array(
                'using_ie' => $this->using_ie()
            ));
        } else
        {
            // if user is logged in, redirect to planjar.com/home/
            redirect('/home/', 'refresh');
        }
    }

    public function using_ie()
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'];
        $ub = False;
        if (preg_match('/MSIE/i', $u_agent))
        {
            $ub = True;
        }

        return $ub;
    }

    // Signs up a user
    // Returns 'success' or 'failure'
    // $additional_data must be a JSON array multidimentional array.
    // This function is called upon a successful submit
    public function try_sign_up()
    {
        $email = trim($this->input->get('su_email_1'));
        $password = $this->input->get('su_password');

        // Pre-calculate values
        $birthday = new DateTime();
        $birthday->setDate($this->input->get('su_year'), $this->input->get('su_month'), $this->input->get('su_day'));

        $this->load->model('sign_up_ops');
        $school_id = $this->sign_up_ops->get_school_from_email($email);

        // Populate the user data array
        $additional_data = array(
            'school_id' => trim($school_id),
            'sex' => trim($this->input->get('su_sex')),
            'first_name' => trim($this->input->get('su_first_name')),
            'last_name' => trim($this->input->get('su_last_name')),
            'birthday' => trim($birthday->format('Y-m-d')),
            'grad_year' => trim($this->input->get('su_grad_year'))
        );

        if ($this->ion_auth->register($email, $password, $email, $additional_data))
        {
            // Redirect to the post sign up page
            echo "/login/post_sign_up";
        } else
        {
            echo "There was a problem creating your account.";
        }
    }

    public function log_in()
    {
        $email = trim($this->input->get('email'));
        $password = $this->input->get('password');
        $remember = (bool) $this->input->get('remember');

        $logged_in = $this->ion_auth->login($email, $password, $remember);

        if (!$logged_in)
        {
            echo("That user name and password combination is not correct.");
        } else
        {
            echo('success');
        }
    }

    // Returns a list of schools (in JSON format) that match the needle string.
    public function search_schools()
    {
        $needle = trim($this->input->get('needle'));
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

    // Returns true if the email is open and of the correct domain.
    // Returns errors otherwise
    public function email_check()
    {
        $email = trim($this->input->get('email'));
        $black_list = array(
            'kristin.shorter@vanderbilt.edu',
            'kristin.torrey@vanderbilt.edu',
            'k.torrey@vanderbilt.edu',
            'k.shorter@vanderbilt.edu'
        );

        $query_string = "SELECT * FROM school_data WHERE email_domain = ?";
        $query = $this->db->query($query_string, array(substr($email, strpos($email, '@') + 1)));

        $email_exists = $this->ion_auth->email_check($email);

        if ($query->num_rows() == 0)
        {
            echo ("If you entered you university address, PlanJar isn't at your school yet, but we're coming!");
        } else if ($email_exists)
        {
            echo('That email address is already in use.');
        } else if (array_search(strtolower($email), $black_list))
        {
            echo('true');
        }
    }

}