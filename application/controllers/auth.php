<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!class_exists('Controller'))
{

    class Controller extends CI_Controller
    {
        
    }

}

class Auth extends Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->database();
        $this->load->helper('url');
    }

    //redirect if needed, otherwise display the user list
    function index()
    {
        if (!$this->ion_auth->logged_in())
        {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->is_admin())
        {
            //redirect them to the home page because they must be an administrator to view this
            redirect($this->config->item('base_url'), 'refresh');
        } else
        {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //list the users
            $this->data['users'] = $this->ion_auth->get_users_array();
            $this->load->view('auth/index', $this->data);
        }
    }

    //log the user in
    // Returns true if successfull. An error message otherwise
    public function login()
    {
        $email = $this->input->get('email');
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

    //log the user out
    public function logout()
    {
        $logout = $this->ion_auth->logout();

        //redirect them to login
        redirect('login');
    }

    //forgot password
    function forgot_password()
    {
        $this->load->view('forgot_password_view');


//        if ($this->input->post())
//        
//        $this->form_validation->set_rules('email', 'Email Address', 'required');
//        if ($this->form_validation->run() == false)
//        {
//            //setup the input
//            $this->data['email'] = array('name' => 'email',
//                'id' => 'email',
//            );
//            //set any errors and display the form
//            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
//            $this->load->view('auth/forgot_password', $this->data);
//        } else
//        {
//            //run the forgotten password method to email an activation code to the user
//            $forgotten = $this->ion_auth->forgotten_password($this->input->post('email'));
//
//            if ($forgotten)
//            { //if there were no errors
//                $this->session->set_flashdata('message', $this->ion_auth->messages());
//                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
//            } else
//            {
//                $this->session->set_flashdata('message', $this->ion_auth->errors());
//                redirect("auth/forgot_password", 'refresh');
//            }
//        }
    }

    //reset password - final step for forgotten password
    public function reset_password($code)
    {
        $reset = $this->ion_auth->forgotten_password_complete($code);

        if ($reset)
        {  //if the reset worked then send them to the login page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/login", 'refresh');
        } else
        { //if the reset didnt work then send them back to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    // Change password
    public function change_password()
    {
        $old_password = $this->input->get('old_password');
        $new_password = $this->input->get('new_password');
        $new_password_1 = $this->input->get('new_password_1');

        $this->ion_auth_model->change_password($this->ion_auth->get_user()->email, $old_password, $new_password, $new_password_1);
    }

    //activate the user
    function activate($id, $code=false)
    {
        if ($code !== false)
            $activation = $this->ion_auth->activate($id, $code);
        else if ($this->ion_auth->is_admin())
            $activation = $this->ion_auth->activate($id);


        if ($activation)
        {
            // Get the school's group id
            $user = $this->ion_auth->get_user($id);
            $query_string = "SELECT group_id, latitude, longitude, city FROM school_data WHERE id = ?";
            $query = $this->db->query($query_string, array($user->school_id));
            $row = $query->row();
            $group_id = $row->group_id;

            // Join the user to his school's group
            $this->load->model('group_ops');
            $this->group_ops->follow_group($group_id, $id);
            $this->group_ops->join_group($group_id, $id);

            // Set the user's location to the school location
            $this->ion_auth->update_user($user->id, array(
                'latitude' => $row->latitude,
                'longitude' => $row->longitude,
                'city_state' => $row->city
            ));

            // Redirect to the login page
            redirect("/login", 'refresh');
        } else
        {
            //redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //deactivate the user
    function deactivate($id = NULL)
    {
        // no funny business, force to integer
        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', 'confirmation', 'required');
        $this->form_validation->set_rules('id', 'user ID', 'required|is_natural');

        if ($this->form_validation->run() == FALSE)
        {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->get_user_array($id);
            $this->load->view('auth/deactivate_user', $this->data);
        } else
        {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes')
            {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id'))
                {
                    show_404();
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin())
                {
                    $this->ion_auth->deactivate($id);
                }
            }

            //redirect them back to the auth page
            redirect('auth', 'refresh');
        }
    }

    //create a new user
    function create_user()
    {
        $this->data['title'] = "Create User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
            redirect('auth', 'refresh');
        }

        //validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'required|xss_clean');
        $this->form_validation->set_rules('last_name', 'Last Name', 'required|xss_clean');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
        $this->form_validation->set_rules('phone1', 'First Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
        $this->form_validation->set_rules('phone2', 'Second Part of Phone', 'required|xss_clean|min_length[3]|max_length[3]');
        $this->form_validation->set_rules('phone3', 'Third Part of Phone', 'required|xss_clean|min_length[4]|max_length[4]');
        $this->form_validation->set_rules('company', 'Company Name', 'required|xss_clean');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() == true)
        {
            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));
            $email = $this->input->post('email');
            $password = $this->input->post('password');

            $additional_data = array('first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),
            );
        }
        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data))
        { //check to see if we are creating the user
            //redirect them back to the admin page
            $this->session->set_flashdata('message', "User Created");
            redirect("auth", 'refresh');
        } else
        { //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array('name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array('name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['company'] = array('name' => 'company',
                'id' => 'company',
                'type' => 'text',
                'value' => $this->form_validation->set_value('company'),
            );
            $this->data['phone1'] = array('name' => 'phone1',
                'id' => 'phone1',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone1'),
            );
            $this->data['phone2'] = array('name' => 'phone2',
                'id' => 'phone2',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone2'),
            );
            $this->data['phone3'] = array('name' => 'phone3',
                'id' => 'phone3',
                'type' => 'text',
                'value' => $this->form_validation->set_value('phone3'),
            );
            $this->data['password'] = array('name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array('name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'value' => $this->form_validation->set_value('password_confirm'),
            );
            $this->load->view('auth/create_user', $this->data);
        }
    }

    function _get_csrf_nonce()
    {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce()
    {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue'))
        {
            return TRUE;
        } else
        {
            return FALSE;
        }
    }

}
