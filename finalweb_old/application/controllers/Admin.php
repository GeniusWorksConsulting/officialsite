<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $config["num_links"] = 3;
        $config['full_tag_open'] = '<ul class="pagination pagination-sm">';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = 'Next &gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&lt; Prev';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $this->pagination->initialize($config);

        $this->lang->load('auth');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $user = $this->ion_auth->user()->row();
        $this->user_id = $user->id;
        $this->logged_in_name = $user->first_name . ' ' . $user->last_name;
        $this->groups = $this->ion_auth->get_users_groups($user->id)->row()->description;

        $this->load->model('backend_model', 'backend');
    }

    /**
     * Dashboard
     */
    public function index() {
        $currentWeek = $this->backend->getcurrentweek();

        $this->data['currentWeek'] = $currentWeek;
        $this->data['type'] = $this->backend->get_table('type');

        $this->data['previous'] = $this->backend->previousAssessment('', 4);
        $this->data['squadList'] = $this->backend->get_table('squad_group');
        $this->data['title'] = 'Dashboard';
        $this->data['body'] = 'admin/welcome_message';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Dashboard
     */
    public function index_old() {
        $currentWeek = $this->backend->getcurrentweek();

        $this->data['currentWeek'] = $currentWeek;
        $this->data['type'] = $this->backend->get_table('type');

        $this->data['users'] = $this->ion_auth->users(array('members'))->result();
        $this->data['title'] = 'Dashboard';
        $this->data['body'] = 'admin/welcome_message';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * QA dashboard
     */
    public function qadashboard() {
        $currentWeek = $this->backend->getcurrentweek();

        $this->data['currentWeek'] = $currentWeek;
        $this->data['type'] = $this->backend->get_table('type');

        $this->data['previous'] = $this->backend->previousAssessment('', 4);

        $this->data['users'] = $this->ion_auth->users(array('qamember'))->result();
        $this->data['title'] = 'Dashboard';
        $this->data['body'] = 'admin/qadashboard';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Create a new QA Member
     */
    public function addQA() {
        $this->data['title'] = $this->lang->line('create_user_heading');

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() === TRUE) {
            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data)) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/addQA", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->data['body'] = 'admin/addQA';
            $this->load->view('layouts/main', $this->data);
        }
    }

    /**
     * Edit a QA
     *
     * @param int|string $id
     */
    public function editQA($id) {
        $this->data['title'] = $this->lang->line('edit_user_heading');

        $user = $this->ion_auth->user($id)->row();
        //$groups = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();
        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        //$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'email' => strtolower($this->input->post('email'))
                );

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/viewQA', 'refresh');
                    } else {
                        redirect('auth/login', 'refresh');
                    }
                } else {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/viewQA', 'refresh');
                    } else {
                        redirect('auth/login', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );

        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $user->email),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password'
        );

        $this->data['body'] = 'admin/editQA';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Edit a Lead
     *
     * @param int|string $id
     */
    public function editLead($id) {
        $this->data['title'] = $this->lang->line('edit_user_heading');

        $user = $this->ion_auth->user($id)->row();
        //$groups = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();
        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        //$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'email' => strtolower($this->input->post('email'))
                );

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/viewLead', 'refresh');
                    } else {
                        redirect('auth/login', 'refresh');
                    }
                } else {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/viewLead', 'refresh');
                    } else {
                        redirect('auth/login', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );

        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $user->email),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password'
        );

        $this->data['body'] = 'admin/editLead';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Activate the user
     *
     * @param int         $id   The user ID
     * @param string|bool $code The activation code
     */
    public function activate($id, $code = FALSE) {
        if ($code !== FALSE) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/index", 'refresh');
        } else {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    /**
     * Deactivate the user
     *
     * @param int|string|null $id The user ID
     */
    public function deactivate($id = NULL) {
        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() === FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->data['title'] = lang('deactivate_heading');
            $this->data['body'] = 'admin/deactivate_user';
            $this->load->view('layouts/main', $this->data);
        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    return show_error($this->lang->line('error_csrf'));
                }

                $this->ion_auth->deactivate($id);
            }

            // redirect them back to the auth page
            redirect('admin/index', 'refresh');
        }
    }

    /**
     * @return array A CSRF key-value pair
     */
    public function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    /**
     * @return bool Whether the posted CSRF token matches
     */
    public function _valid_csrf_nonce() {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Manage QA Member
     */
    public function viewQA() {
        //list the users
        $this->data['users'] = $this->ion_auth->users(array('qamember'))->result();
        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $this->data['title'] = $this->lang->line('index_heading');
        $this->data['body'] = 'admin/viewQA';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Create a new Squad Member
     */
    public function addSquad() {
        $this->data['title'] = 'Add Squad';

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');
        $this->form_validation->set_rules('squad_group', 'squad_group', 'required', array('required' => 'Please select group name.'));

        if ($this->form_validation->run() === TRUE) {
            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone'),
                'squad_group' => $this->input->post('squad_group'),
            );
        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, array(3))) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/addSquad", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->data['squad_group'] = $this->backend->get_table('squad_group');
            $this->data['body'] = 'admin/addSquad';
            $this->load->view('layouts/main', $this->data);
        }
    }

    /**
     * Edit a QA
     *
     * @param int|string $id
     */
    public function editSquad($id) {
        $this->data['title'] = $this->lang->line('edit_user_heading');

        $user = $this->ion_auth->user($id)->row();
        //$groups = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();
        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        //$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('squad_group', 'squad_group', 'required', array('required' => 'Please select group name.'));

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'squad_group' => $this->input->post('squad_group')
                );

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/viewSquad', 'refresh');
                    } else {
                        redirect('auth/login', 'refresh');
                    }
                } else {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    if ($this->ion_auth->is_admin()) {
                        redirect('admin/viewSquad', 'refresh');
                    } else {
                        redirect('auth/login', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );
        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $user->email),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password'
        );

        $this->data['squad_group'] = $this->backend->get_table('squad_group');
        $this->data['body'] = 'admin/editSquad';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Manage Squad Member
     */
    public function viewSquad() {
        $config['base_url'] = site_url('admin/viewSquad');
        $config['total_rows'] = $this->ion_auth->users(array('members'))->num_rows();
        $config['per_page'] = '20';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["count"] = $this->uri->segment(3) + 1;
        $data["pagination"] = $this->pagination->create_links();

        //list the users
        $data["users"] = $this->ion_auth->offset($page)->limit($config['per_page'])->users(array('members'))->result();
        $data["currentWeek"] = $this->backend->getcurrentweek();

        foreach ($data['users'] as $k => $user) {
            $data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $data['title'] = 'Manage Squad';
        $data['body'] = 'admin/viewSquad';
        $this->load->view('layouts/main', $data);
    }

    /**
     * Manage Squad Number
     */
    public function squadnumber($squad_group = NULL) {
        if ($squad_group) {
            $data["row"] = $this->backend->get_table_row('squad_group', array("squad_group" => $squad_group));
        }

        $data["list"] = $this->backend->get_table('squad_group', array('status' => 0));

        $data['title'] = 'Squad Number';
        $data['body'] = 'admin/squad-number';
        $this->load->view('layouts/main', $data);
    }

    public function saveSquadnumber() {
        $this->form_validation->set_rules('squad_name', 'squad_name', 'trim|required');
        $this->form_validation->set_rules('site', 'site', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('squad_group') == NULL) {
                $dataPost = array(
                    'squad_name' => $this->input->post('squad_name'),
                    'site' => $this->input->post('site')
                );

                $boolean = $this->backend->insert_data('squad_group', $dataPost);
            } else {
                $dataPost = array(
                    'squad_name' => $this->input->post('squad_name'),
                    'site' => $this->input->post('site')
                );

                $boolean = $this->backend->update_data('squad_group', array('squad_group' => $this->input->post('squad_group')), $dataPost);
            }

            if ($boolean) {
                $this->session->set_flashdata('message', 'Success!');
            } else {
                $this->session->set_flashdata('message', 'Something wrong, please try again.');
            }

            redirect('admin/squadnumber');
        }

        $data["list"] = $this->backend->get_table('squad_group', array('status' => 0));

        $data['title'] = 'Squad Number';
        $data['body'] = 'admin/squad-number';
        $this->load->view('layouts/main', $data);
    }

    /**
     * View Category
     */
    public function viewcategory() {
        $this->data["list"] = $this->backend->categories(array('c.status' => 0));

        $this->data['title'] = 'View Category';
        $this->data['body'] = 'admin/viewcategory';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Add Category
     */
    public function addcategory($cat_id = null) {
        if ($cat_id) {
            $this->data["row"] = $this->backend->get_table_row('category', array("cat_id" => $cat_id));
        }

        $this->data["list"] = $this->backend->get_table('assessment');

        $this->data['title'] = 'Add Category';
        $this->data['body'] = 'admin/addcategory';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Save Category
     */
    public function savecategory() {
        $this->form_validation->set_rules('assessment', 'assessment', 'required', array('required' => 'Please select atleast one assessment'));
        $this->form_validation->set_rules('name', 'name', 'trim|required', array('required' => 'Enter the name of category'));
        $this->form_validation->set_rules('weighting', 'weighting', 'trim|required|numeric', array('required' => 'Enter the weighting of category'));

        if ($this->form_validation->run() === true) {
            if ($this->input->post('cat_id') == NULL) {
                $dataPost = array(
                    'assessment' => $this->input->post('assessment'),
                    'name' => $this->input->post('name'),
                    'weighting' => $this->input->post('weighting'),
                    'created' => date('Y-m-d H:i:s')
                );

                $insertedId = $this->backend->insert_data('category', $dataPost);
                if ($insertedId) {
                    $this->session->set_flashdata('message', 'Your Data Has Been Successfully Saved.');
                } else {
                    $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
                }
            } else {
                $dataPost = array(
                    'assessment' => $this->input->post('assessment'),
                    'name' => $this->input->post('name'),
                    'weighting' => $this->input->post('weighting')
                );

                $updateStatus = $this->backend->update_data('category', array('cat_id' => $this->input->post('cat_id')), $dataPost);
                if ($updateStatus) {
                    $this->session->set_flashdata('message', 'Your Data Has Been Successfully Changed.');
                } else {
                    $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
                }
            }
            redirect('admin/addcategory');
        }

        $this->data["list"] = $this->backend->get_table('assessment');

        $this->data['title'] = 'Add Category';
        $this->data['body'] = 'admin/addcategory';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Delete Category
     */
    public function deletecategory($cat_id = null) {
        if ($cat_id == NULL) {
            return show_404();
        }

        $dataPost = array(
            'status' => 1
        );

        $updateStatus = $this->backend->update_data('category', array('cat_id' => $cat_id), $dataPost);
        if ($updateStatus) {
            $this->session->set_flashdata('message', 'Your Data Has Been Deleted.');
        } else {
            $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
        }

        redirect('admin/viewcategory');
    }

    /**
     * View Question
     */
    public function viewquestion() {
        $config['base_url'] = site_url('admin/viewquestion');
        $config['total_rows'] = $this->backend->questions(array('q.status' => 0));
        $config['per_page'] = '20';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["count"] = $this->uri->segment(3) + 1;
        $data["pagination"] = $this->pagination->create_links();

        //list the users
        $data["list"] = $this->backend->questions(array('q.status' => 0), $config['per_page'], $page);

        $data['title'] = 'View Questions';
        $data['body'] = 'admin/viewquestion';
        $this->load->view('layouts/main', $data);
    }

    /**
     * Get Questions Ajax
     */
    public function getQuestions() {
        $this->data['Quelist'] = $this->backend->get_table('question', array('status' => 0));
        $this->load->view('admin/questionList', $this->data);
    }

    /**
     * Count Question
     */
    public function getCount() {
        $this->load->view('admin/questionList');
    }

    /**
     * Get Question Answer
     */
    public function getAnswers() {
        $dataPost = $this->input->post();

        $answers = $this->backend->get_table('answer', array('question_id' => $dataPost['question_id']));
        echo json_encode($answers);
    }

    /**
     * Add Questions
     */
    public function addquestion($question_id = null) {
        if ($question_id) {
            $this->data["row"] = $this->backend->get_table_row('question', array("question_id" => $question_id));
        }

        $this->data["list"] = $this->backend->get_table('category', array('status' => 0));

        $this->data['title'] = 'Add Question';
        $this->data['body'] = 'admin/addquestion';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Delete Question
     */
    public function deletequestion($question_id = null) {
        if ($question_id == NULL) {
            return show_404();
        }

        $dataPost = array(
            'status' => 1
        );

        $updateStatus = $this->backend->update_data('question', array('question_id' => $question_id), $dataPost);
        if ($updateStatus) {
            $this->session->set_flashdata('message', 'Your Data Has Been Deleted.');
        } else {
            $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
        }

        redirect('admin/viewquestion');
    }

    /**
     * Save Question
     */
    public function saveQuestion() {
        $this->form_validation->set_rules('cat_id', 'cat_id', 'required', array('required' => 'Please select cateogry name'));
        $this->form_validation->set_rules('description', 'description', 'trim|required', array('required' => 'Please enter question'));
        //$this->form_validation->set_rules('is_parent', 'is_parent', 'required', array('required' => 'This feild is required'));
        $this->form_validation->set_rules('weight', 'weight', 'required|numeric', array('required' => 'Weight of question'));
        if ($this->input->post('is_parent') == '1') {
            $this->form_validation->set_rules('parent_id', 'parent_id', 'required', array('required' => 'Please select parent question.'));
            $this->form_validation->set_rules('answer_id', 'answer_id', 'required', array('required' => 'Please select answers.'));
        }
        $this->form_validation->set_rules('no_answer', "no_answer", "required|numeric", array('required' => 'Select one answer'));

        if ($this->form_validation->run() === true) {
            if ($this->input->post('question_id') == NULL) {
                $parent_id = 0;
                $answer_id = 0;
                $count = 0;

                if ($this->input->post('is_parent') == 1) {
                    $parent_id = $this->input->post('parent_id');
                    $answer_id = $this->input->post('answer_id');
                }

                if ($this->input->post('has_child') == 1) {
                    $count = $this->input->post('count');
                }

                $data = array(
                    'cat_id' => $this->input->post('cat_id'),
                    'description' => $this->input->post('description'),
                    'is_parent' => $this->input->post('is_parent'),
                    'has_child' => $this->input->post('has_child'),
                    'evaluation' => $this->input->post('evaluation'),
                    'parent_id' => $parent_id,
                    'answer_id' => $answer_id,
                    'count' => $count,
                    'weight' => $this->input->post('weight'),
                    'no_answer' => $this->input->post('no_answer'),
                    'date_added' => date('Y-m-d H:i:s')
                );

                $question_id = $this->backend->insert_data('question', $data);
                if ($question_id) {
                    $batchData = array();

                    for ($i = 0; $i < count($this->input->post('answer')); $i++) {
                        $batchData[$i]['answer'] = $this->input->post('answer')[$i];
                        $batchData[$i]['rating'] = $this->input->post('rating')[$i];
                        $batchData[$i]['weighting'] = $this->input->post('weighting')[$i];
                        $batchData[$i]['isZero'] = $this->input->post('isZero')[$i];
                        $batchData[$i]['sectionZero'] = $this->input->post('sectionZero')[$i];
                        $batchData[$i]['question_id'] = $question_id;
                        $batchData[$i]['created'] = date('Y-m-d H:i:s');
                    }

                    $this->backend->insert_batch('answer', $batchData);
                }

                $this->session->set_flashdata('message', 'Your Data Has Been Successfully Saved.');
                redirect('admin/addquestion');
            } else {
                $parent_id = 0;
                $answer_id = 0;
                $count = 0;

                if ($this->input->post('is_parent') == 1) {
                    $parent_id = $this->input->post('parent_id');
                    $answer_id = $this->input->post('answer_id');
                }

                if ($this->input->post('has_child') == 1) {
                    $count = $this->input->post('count');
                }

                $data = array(
                    'cat_id' => $this->input->post('cat_id'),
                    'description' => $this->input->post('description'),
                    'is_parent' => $this->input->post('is_parent'),
                    'has_child' => $this->input->post('has_child'),
                    'evaluation' => $this->input->post('evaluation'),
                    'parent_id' => $parent_id,
                    'answer_id' => $answer_id,
                    'count' => $count,
                    'weighting' => $this->input->post('weighting'),
                    'no_answer' => $this->input->post('no_answer')
                );

                $this->backend->update_data('answer', array('question_id' => $this->input->post('question_id')), $data);

                for ($i = 0; $i < count($this->input->post('answer')); $i++) {
                    if ($this->input->post('answer_id')[$i] != NULL) {
                        $uData = array(
                            'answer' => $this->input->post('answer')[$i],
                            'rating' => $this->input->post('rating')[$i],
                            'weighting' => $this->input->post('weighting')[$i],
                            'question_id' => $this->input->post('question_id')[$i]
                        );

                        $this->backend->update_data('answer', array('answer_id' => $this->input->post('answer_id')[$i]), $uData);
                    } else {
                        $uData = array(
                            'answer' => $this->input->post('answer')[$i],
                            'rating' => $this->input->post('rating')[$i],
                            'weighting' => $this->input->post('weighting')[$i],
                            'isZero' => $this->input->post('isZero')[$i],
                            'sectionZero' => $this->input->post('sectionZero')[$i],
                            'question_id' => $this->input->post('question_id')[$i]
                        );

                        $this->backend->insert_data('answer', $uData);
                    }
                }

                $this->session->set_flashdata('message', 'Your Data Has Been Successfully Changed.');
                redirect('admin/addquestion');
            }
        }

        $this->data["list"] = $this->backend->get_table('category', array('status' => 0));

        $this->data['title'] = 'Add Question';
        $this->data['body'] = 'admin/addquestion';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Change password
     */
    public function change_password() {
        $this->data['title'] = 'Change Password';
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() === FALSE) {
            // display the form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'class' => 'form-control',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'class' => 'form-control',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'class' => 'form-control',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );

            // render
            $this->data['body'] = 'admin/change-password';
            $this->load->view('layouts/main', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('admin/index', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('admin/change_password', 'refresh');
            }
        }
    }

    /**
     * Manage Scheduler Member
     */
    public function viewScheduler() {
        //list the users
        $this->data['users'] = $this->ion_auth->users(array('scheduler'))->result();
        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $this->data['title'] = 'Manage Scheduler';
        $this->data['body'] = 'admin/viewScheduler';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Add Scheduler User
     */
    public function addLead() {
        $this->data['title'] = 'New Lead';

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() === TRUE) {
            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone')
            );
        }

        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, array(4))) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/addLead", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->data['body'] = 'admin/addLead';
            $this->load->view('layouts/main', $this->data);
        }
    }

    /**
     * Manage Lead
     */
    public function viewLead() {
        //list the users
        $this->data['users'] = $this->ion_auth->users(array('lead'))->result();
        foreach ($this->data['users'] as $k => $user) {
            $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $this->data['title'] = $this->lang->line('index_heading');
        $this->data['body'] = 'admin/viewLead';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Create a new Lead Member
     */
    public function addScheduler() {
        $this->data['title'] = 'Add Scheduler';

        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');
        if ($identity_column !== 'email') {
            $this->form_validation->set_rules('identity', $this->lang->line('create_user_validation_identity_label'), 'trim|required|is_unique[' . $tables['users'] . '.' . $identity_column . ']');
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        } else {
            $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        }
        $this->form_validation->set_rules('phone', $this->lang->line('create_user_validation_phone_label'), 'trim');
        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() === TRUE) {
            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone' => $this->input->post('phone')
            );
        }

        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data, array(5))) {
            // check to see if we are creating the user
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("admin/addScheduler", 'refresh');
        } else {
            // display the create user form
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(
                'name' => 'first_name',
                'id' => 'first_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('first_name'),
            );
            $this->data['last_name'] = array(
                'name' => 'last_name',
                'id' => 'last_name',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('last_name'),
            );
            $this->data['identity'] = array(
                'name' => 'identity',
                'id' => 'identity',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['phone'] = array(
                'name' => 'phone',
                'id' => 'phone',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('phone'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password'),
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'type' => 'password',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('password_confirm'),
            );

            $this->data['body'] = 'admin/addScheduler';
            $this->load->view('layouts/main', $this->data);
        }
    }

    /**
     * Edit a Lead
     *
     * @param int|string $id
     */
    public function editScheduler($id) {
        $this->data['title'] = $this->lang->line('edit_user_heading');

        $user = $this->ion_auth->user($id)->row();
        //$groups = $this->ion_auth->groups()->result_array();
        //$currentGroups = $this->ion_auth->get_users_groups($id)->result();
        // validate form input
        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'trim|required');
        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'trim|required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'trim|required|valid_email');
        //$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone' => $this->input->post('phone'),
                    'email' => strtolower($this->input->post('email'))
                );

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect('admin/addScheduler', 'refresh');
                } else {
                    // redirect them back to the admin page if admin, or to the base url if non admin
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('admin/addScheduler', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['first_name'] = array(
            'name' => 'first_name',
            'id' => 'first_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('first_name', $user->first_name),
        );
        $this->data['last_name'] = array(
            'name' => 'last_name',
            'id' => 'last_name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('last_name', $user->last_name),
        );

        $this->data['email'] = array(
            'name' => 'email',
            'id' => 'email',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('email', $user->email),
        );
        $this->data['phone'] = array(
            'name' => 'phone',
            'id' => 'phone',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('phone', $user->phone),
        );
        $this->data['password'] = array(
            'name' => 'password',
            'id' => 'password',
            'class' => 'form-control',
            'type' => 'password'
        );
        $this->data['password_confirm'] = array(
            'name' => 'password_confirm',
            'id' => 'password_confirm',
            'class' => 'form-control',
            'type' => 'password'
        );

        $this->data['body'] = 'admin/editScheduler';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * View Previous Assessment
     */
    public function viewPrevious() {
        $this->data['list'] = $this->backend->previousAssessment('');

        $this->data['title'] = 'Previous Assessment';
        $this->data['body'] = 'admin/viewPrevious';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * View Squad Previous Assessment
     */
    public function viewAssessment() {
        if (isset($_GET)) {
            $dataGet = $this->input->get();
            if ($dataGet['week'] != NULL & $dataGet['month'] != NULL & $dataGet['year'] != NULL) {

                $this->data['currentWeek'] = $dataGet;
                $this->data['type'] = $this->backend->get_table('type');
                if ($dataGet['squad'] != NULL) {
                    $this->data['squadList'] = $this->backend->get_table('squad_group', array('squad_group' => $dataGet['squad']));
                } else {
                    $this->data['squadList'] = $this->backend->get_table('squad_group');
                }

                $this->data['title'] = 'View Assessment';
                $this->data['body'] = 'admin/viewAssessment';
                $this->load->view('layouts/main', $this->data);
            } else {
                show_404();
            }
        }
    }

    /**
     * View Squad Previous Assessment
     */
    public function viewQAassess() {
        if (isset($_GET)) {
            $dataGet = $this->input->get();
            if ($dataGet['week'] != NULL & $dataGet['month'] != NULL & $dataGet['year'] != NULL & $dataGet['user_id'] != NULL) {

                $this->data['currentWeek'] = $dataGet;
                $this->data['type'] = $this->backend->get_table('type');
                $this->data['squadList'] = $this->backend->get_table('qa_squad', array('user_id' => $dataGet['user_id'], 'week' => $dataGet['week'], 'month' => $dataGet['month'], 'year' => $dataGet['year']));

                $this->data['title'] = 'View Assessment';
                $this->data['body'] = 'admin/viewQAassess';
                $this->load->view('layouts/main', $this->data);
            } else {
                show_404();
            }
        }
    }

}
