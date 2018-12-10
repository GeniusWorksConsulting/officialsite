<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Superadmin extends MY_Controller {

    public function __construct() {
        parent::__construct();

        $config["num_links"] = 5;
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
        $this->logged_in_name = $user->first_name . ' ' . $user->last_name;
        $this->logged_in_id = $user->id;
        $this->group_name = $this->ion_auth->get_users_groups($user->id)->row()->name;
        $this->groups = $this->ion_auth->get_users_groups($user->id)->row()->description;
        $this->users = $this->ion_auth->groups()->result();

        $this->load->model('backend_model', 'backend');
    }

    /**
     * Dashboard
     */
    public function index() {
        //$test = 10 * 20;
        //$expected_result = 200;
        //$test_name = 'Adds one plus one';
        //$this->unit->run($test, $expected_result, $test_name, 'Write notes here.');
        //echo $this->unit->report();

        $this->data['content'] = 'superadmin/welcome';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * User Group
     */
    public function usergroups() {
        $groups = $this->ion_auth->groups()->result();
        $this->data['groups'] = $groups;

        $this->data['content'] = 'superadmin/usergroups';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Create a new group
     */
    public function create_group() {
        // validate form input
        $this->form_validation->set_rules('group_name', 'Group Name', 'required|alpha_dash');

        if ($this->form_validation->run() === TRUE) {
            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
            if ($new_group_id) {
                // check to see if we are creating the group
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("superadmin/usergroups", 'refresh');
            } else {
                $this->session->set_flashdata('message', "Error! Something wrong.");
                redirect("superadmin/usergroups", 'refresh');
            }
        } else {
            // set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['content'] = 'auth/create_group';
            $this->load->view('superadmin/common/main', $this->data);
        }
    }

    /**
     * Edit a group
     */
    public function edit_group($id) {
        // bail if no group id given
        if (!$id || empty($id)) {
            redirect('auth', 'refresh');
        }

        $group = $this->ion_auth->group($id)->row();

        // validate form input
        $this->form_validation->set_rules('group_name', 'Group Name', 'required|alpha_dash');
        if (isset($_POST) && !empty($_POST)) {
            if ($this->form_validation->run() === TRUE) {
                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);
                if ($group_update) {
                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }
                redirect("superadmin/usergroups", 'refresh');
            }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        $this->data['group'] = $group;
        $this->data['content'] = 'auth/edit_group';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Manage Permission
    public function permissions() {
        $this->data['permissions'] = $this->ion_auth_acl->permissions('full');

        $this->data['content'] = 'permission/permissions';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Add Permission
    public function add_permission() {
        if ($this->input->post() && $this->input->post('cancel')) {
            redirect('superadmin/permissions', 'refresh');
        }

        $this->form_validation->set_rules('perm_key', 'key', 'required|trim');
        $this->form_validation->set_rules('perm_name', 'name', 'required|trim');

        $this->form_validation->set_message('required', 'Please enter a %s');

        if ($this->form_validation->run() === FALSE) {
            $this->data['message'] = ($this->ion_auth_acl->errors() ? $this->ion_auth_acl->errors() : $this->session->flashdata('message'));

            $this->data['content'] = 'permission/add_permission';
            $this->load->view('superadmin/common/main', $this->data);
        } else {
            $new_permission_id = $this->ion_auth_acl->create_permission($this->input->post('perm_key'), $this->input->post('perm_name'));
            if ($new_permission_id) {
                // check to see if we are creating the permission
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("superadmin/permissions", 'refresh');
            }
        }
    }

    // Edit Permission
    public function update_permission() {
        if ($this->input->post() && $this->input->post('cancel')) {
            redirect('superadmin/permissions', 'refresh');
        }

        $permission_id = $this->uri->segment(3);

        if (!$permission_id) {
            $this->session->set_flashdata('message', "No permission ID passed");
            redirect('superadmin/permissions', 'refresh');
        }

        $permission = $this->ion_auth_acl->permission($permission_id);

        $this->form_validation->set_rules('perm_key', 'key', 'required|trim');
        $this->form_validation->set_rules('perm_name', 'name', 'required|trim');

        $this->form_validation->set_message('required', 'Please enter a %s');

        if ($this->form_validation->run() === FALSE) {
            $this->data['message'] = ($this->ion_auth_acl->errors() ? $this->ion_auth_acl->errors() : $this->session->flashdata('message'));
            $this->data['permission'] = $permission;

            $this->data['content'] = 'permission/edit_permission';
            $this->load->view('superadmin/common/main', $this->data);
        } else {
            $additional_data = array(
                'perm_name' => $this->input->post('perm_name')
            );

            $update_permission = $this->ion_auth_acl->update_permission($permission_id, $this->input->post('perm_key'), $additional_data);
            if ($update_permission) {

                // check to see if we are creating the permission
                // redirect them back to the admin page
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('superadmin/permissions', 'refresh');
            }

            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect('superadmin/permissions', 'refresh');
        }
    }

    // Delete Permission
    public function delete_permission() {
        if ($this->input->post() && $this->input->post('cancel')) {
            redirect('superadmin/permissions', 'refresh');
        }

        $permission_id = $this->uri->segment(3);

        if (!$permission_id) {
            $this->session->set_flashdata('message', "No Permission ID Passed");
            redirect("superadmin/permissions", 'refresh');
        }

        if ($this->input->post() && $this->input->post('delete')) {
            if ($this->ion_auth_acl->remove_permission($permission_id)) {
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect("superadmin/permissions", 'refresh');
            } else {
                echo $this->ion_auth_acl->messages();
            }
        } else {
            $this->data['message'] = ($this->ion_auth_acl->errors() ? $this->ion_auth_acl->errors() : $this->session->flashdata('message'));

            $this->data['content'] = 'permission/delete_permission';
            $this->load->view('superadmin/common/main', $this->data);
        }
    }

    // Groups
    public function groups() {
        $this->data['groups'] = $this->ion_auth->groups()->result();

        $this->data['content'] = 'permission/groups';
        $this->load->view('superadmin/common/main', $this->data);
    }

    public function group_permissions() {
        if ($this->input->post() && $this->input->post('cancel')) {
            redirect('superadmin/groups', 'refresh');
        }

        $group_id = $this->uri->segment(3);

        if (!$group_id) {
            $this->session->set_flashdata('message', "No Group ID Passed");
            redirect('superadmin/groups', 'refresh');
        }

        if ($this->input->post() && $this->input->post('save')) {
            foreach ($this->input->post() as $k => $v) {
                if (substr($k, 0, 5) == 'perm_') {
                    $permission_id = str_replace("perm_", "", $k);

                    if ($v == "X") {
                        $this->ion_auth_acl->remove_permission_from_group($group_id, $permission_id);
                    } else {
                        $this->ion_auth_acl->add_permission_to_group($group_id, $permission_id, $v);
                    }
                }
            }

            redirect('superadmin/groups', 'refresh');
        }

        $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key');
        $this->data['group_permissions'] = $this->ion_auth_acl->get_group_permissions($group_id);

        $this->data['content'] = 'permission/group_permissions';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Users
    public function user_list() {
        $this->data['users'] = $this->ion_auth->users()->result();

        $this->data['content'] = 'permission/users';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Manage User
    public function manage_user() {
        $user_id = $this->uri->segment(3);

        if (!$user_id) {
            $this->session->set_flashdata('message', "No user ID passed");
            redirect("superadmin/user_list", 'refresh');
        }

        $this->data['user'] = $this->ion_auth->user($user_id)->row();
        $this->data['user_groups'] = $this->ion_auth->get_users_groups($user_id)->result();
        $this->data['user_acl'] = $this->ion_auth_acl->build_acl($user_id);

        $this->data['content'] = 'permission/manage_user';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // User Permission
    public function user_permissions() {
        $user_id = $this->uri->segment(3);

        if (!$user_id) {
            $this->session->set_flashdata('message', "No user ID passed");
            redirect("superadmin/user_list", 'refresh');
        }

        if ($this->input->post() && $this->input->post('cancel')) {
            redirect("superadmin/manage_user/{$user_id}", 'refresh');
        }

        if ($this->input->post() && $this->input->post('save')) {
            foreach ($this->input->post() as $k => $v) {
                if (substr($k, 0, 5) == 'perm_') {
                    $permission_id = str_replace("perm_", "", $k);

                    if ($v == "X") {
                        $this->ion_auth_acl->remove_permission_from_user($user_id, $permission_id);
                    } else {
                        $this->ion_auth_acl->add_permission_to_user($user_id, $permission_id, $v);
                    }
                }
            }

            redirect("superadmin/manage_user/{$user_id}", 'refresh');
        }

        $user_groups = $this->ion_auth_acl->get_user_groups($user_id);

        $this->data['user_id'] = $user_id;
        $this->data['permissions'] = $this->ion_auth_acl->permissions('full', 'perm_key');
        $this->data['group_permissions'] = $this->ion_auth_acl->get_group_permissions($user_groups);
        $this->data['users_permissions'] = $this->ion_auth_acl->build_acl($user_id);

        $this->data['content'] = 'permission/user_permissions';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Admin User
     */
    public function admin() {
        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/admin';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * New Admin
     */
    public function addadmin() {
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');

        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

        if ($this->form_validation->run() === TRUE) {
            $email = strtolower($this->input->post('email'));
            $identity = ($identity_column === 'email') ? $email : $this->input->post('identity');
            $password = $this->input->post('password');

            $additional_data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'company' => $this->input->post('company'),
                'phone' => $this->input->post('phone'),
            );
        }
        if ($this->form_validation->run() === TRUE && $this->ion_auth->register($identity, $password, $email, $additional_data)) {
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("superadmin/addadmin", 'refresh');
        } else {
            // display the create user form or set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['content'] = 'superadmin/addadmin';
            $this->load->view('superadmin/common/main', $this->data);
        }
    }

    /**
     * Edit Admin
     */
    public function editadmin($id) {
        $user = $this->ion_auth->user($id)->row();

        // validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
            }

            if ($this->form_validation->run() === TRUE) {
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'company' => $this->input->post('company'),
                    'phone' => $this->input->post('phone'),
                    'email' => strtolower($this->input->post('email'))
                );

                // update the password if it was posted
                if ($this->input->post('password')) {
                    $data['password'] = $this->input->post('password');
                }

                // check to see if we are updating the user
                if ($this->ion_auth->update($user->id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect('superadmin/admin', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('superadmin/admin', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['content'] = 'superadmin/editadmin';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Users
     */
    public function users() {
        $name = $this->uri->segment(3);

        $config['base_url'] = site_url('superadmin/users/' . $name);
        $config['total_rows'] = $this->backend->get_users(array('g.name' => $name));
        $config['per_page'] = CV_RECORD_PER_PAGE;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->data["count"] = $this->uri->segment(4) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        //list the users
        $this->data["users"] = $this->backend->get_users(array('g.name' => $name), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/users';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Search Users
     */
    public function search() {
        // get search string
        $search = ($this->input->post("search")) ? $this->input->post("search") : '';
        //$search = ($this->uri->segment(4)) ? $this->uri->segment(4) : $search;

        $replace = str_replace(' ', '%', $search);
        $this->data["users"] = $this->backend->get_all_users("(u.first_name LIKE '%$replace%' OR u.last_name LIKE '%$replace%' OR u.email LIKE '%$replace%' OR g.description LIKE '%$replace%') AND u.active = 1 AND g.name = '" . $this->uri->segment(3) . "' ");

        $this->data['content'] = 'superadmin/users';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * New Create
     */
    public function create_user() {
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('admin_id', 'Admin', 'trim|required', array('required' => 'Please select admin name.'));
        $this->form_validation->set_rules('group_id', 'User Group', 'trim|required', array('required' => 'Please select user group.'));
        $this->form_validation->set_rules('phone', 'Phone', 'trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
        $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');

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
        if ($this->form_validation->run() === TRUE) {
            $insertId = $this->ion_auth->register($identity, $password, $email, $additional_data, array($this->input->post('group_id')));
            if ($insertId) {
                // redirect them back to the admin page
                $this->backend->insert_data('users_admin', array('user_id' => $insertId, 'admin_id' => $this->input->post('admin_id')));
                $this->session->set_flashdata('message', $this->ion_auth->messages());
            }
            redirect("superadmin/create_user", 'refresh');
        } else {
            // display the create user form or set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['users'] = $this->ion_auth->users(array('admin'))->result();
            $this->data['content'] = 'superadmin/create_user';
            $this->load->view('superadmin/common/main', $this->data);
        }
    }

    /**
     * Edit User
     */
    public function edit_user($id) {
        $user = $this->backend->get_user_row(array('u.id' => $id));

        // validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email');
        $this->form_validation->set_rules('admin_id', 'Admin', 'trim|required', array('required' => 'Please select admin name.'));

        if (isset($_POST) && !empty($_POST)) {
            // do we have a valid request?
            if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                show_error($this->lang->line('error_csrf'));
            }

            // update the password if it was posted
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
                $this->form_validation->set_rules('password_confirm', 'Password Confirmation', 'required');
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
                    $this->backend->update_data('users_admin', array('user_id' => $user->id), array('admin_id' => $this->input->post('admin_id')));
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect($this->input->post('redirect'), 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect($this->input->post('redirect'), 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['redirect'] = $this->agent->referrer();
        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();
        $this->data['content'] = 'superadmin/edit_user';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Manage Squad
    public function managesquad($user_id = NULL) {
        if ($user_id) {
            $this->data['users'] = $this->backend->get_all_users("ua.admin_id = $user_id AND u.active = 1 AND g.name = 'squad' AND u.id NOT IN (SELECT user_id FROM squad_users WHERE status = 0)");
            $this->data['squads'] = $this->backend->get_table('squad_group', array('status' => 0, 'user_id' => $user_id));
        }

        $this->data['admins'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/manage_squad';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Deactivate the user
     */
    public function deactivate($user_id = NULL) {
        $id = (int) $user_id;

        $this->form_validation->set_rules('confirm', 'confirm', 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() === FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();
            $this->data['redirect'] = $this->agent->referrer();

            $this->data['content'] = 'superadmin/common/deactivate_user';
            $this->load->view('superadmin/common/main', $this->data);
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
            redirect($this->input->post('redirect'), 'refresh');
        }
    }

    /**
     * Activate the user
     */
    public function activate($id) {
        $activation = $this->ion_auth->activate($id);

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect($this->agent->referrer(), 'refresh');
        } else {
            // redirect them to the forgot password page
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
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
     * Change password
     */
    public function password() {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

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
            $this->data['content'] = 'superadmin/password';
            $this->load->view('superadmin/common/main', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('superadmin/password', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('superadmin/password', 'refresh');
            }
        }
    }

    // Levels
    public function levels() {
        $this->data['levels'] = $this->backend->get_table('levels', array('status' => 0));

        $this->data['content'] = 'superadmin/levels';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Create Level
    public function createlevel($level_id = NULL) {
        if ($level_id) {
            $this->data['row'] = $this->backend->get_table_row('levels', array('level_id' => $level_id));
        }

        // validate form input
        $this->form_validation->set_rules('user_id', 'Admin', 'trim|required');
        $this->form_validation->set_rules('level_name', 'Level Name', 'trim|required');
        $this->form_validation->set_rules('value', '%', 'numeric');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('level_id') == NULL) {
                $dataPost = array(
                    'level_name' => $this->input->post('level_name'),
                    'value' => $this->input->post('value'),
                    'user_id' => $this->input->post('user_id'),
                    'date' => date('Y-m-d')
                );

                $insertId = $this->backend->insert_data('levels', $dataPost);
                $this->after_submit($insertId, "superadmin/createlevel");
            } else {
                $dataPost = array(
                    'level_name' => $this->input->post('level_name'),
                    'value' => $this->input->post('value'),
                    'user_id' => $this->input->post('user_id'),
                    'date' => date('Y-m-d')
                );

                $status = $this->backend->update_data('levels', array('level_id' => $this->input->post('level_id')), $dataPost);
                $this->after_submit($status, $this->agent->referrer());
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/createlevel';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Delete Level
    public function delete_level($level_id = NULL) {
        if (!$level_id) {
            show_404();
        }

        $this->backend->update_data('levels', array('level_id' => $level_id), array('status' => 1));
        redirect('superadmin/levels');
    }

    // Sub Levels
    public function sub_levels() {
        $this->data['levels'] = $this->backend->get_table('sub_levels', array('status' => 0));

        $this->data['content'] = 'superadmin/sub_levels';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Create Level
    public function create_sub_level($sub_level_id = NULL) {
        if ($sub_level_id) {
            $this->data['row'] = $this->backend->get_table_row('sub_levels', array('sub_level_id' => $sub_level_id));
        }

        // validate form input
        $this->form_validation->set_rules('user_id', 'Admin', 'trim|required|numeric');
        $this->form_validation->set_rules('level_id', 'Level', 'trim|required|numeric');
        $this->form_validation->set_rules('sub_level_name', 'Level Name', 'trim|required');
        $this->form_validation->set_rules('value', '%', 'numeric');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('sub_level_id') == NULL) {
                $dataPost = array(
                    'user_id' => $this->input->post('user_id'),
                    'level_id' => $this->input->post('level_id'),
                    'sub_level_name' => $this->input->post('sub_level_name'),
                    'value' => $this->input->post('value'),
                    'created' => date('Y-m-d H:i:s')
                );

                $insertId = $this->backend->insert_data('sub_levels', $dataPost);
                $this->after_submit($insertId, "superadmin/create_sub_level");
            } else {
                $dataPost = array(
                    'user_id' => $this->input->post('user_id'),
                    'level_id' => $this->input->post('level_id'),
                    'sub_level_name' => $this->input->post('sub_level_name'),
                    'value' => $this->input->post('value'),
                    'created' => date('Y-m-d H:i:s')
                );

                $status = $this->backend->update_data('sub_levels', array('sub_level_id' => $this->input->post('sub_level_id')), $dataPost);
                $this->after_submit($status, $this->agent->referrer());
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/create_sub_level';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Delete Sub Level
    public function delete_sub_level($sub_level_id = NULL) {
        if (!$sub_level_id) {
            show_404();
        }

        $this->backend->update_data('sub_levels', array('sub_level_id' => $sub_level_id), array('status' => 1));
        redirect('superadmin/sub_levels');
    }

    // Squad Group
    public function squadgroup() {
        $this->data['list'] = $this->backend->get_squad_groups(array('status' => 0));

        $this->data['content'] = 'superadmin/squadgroup';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Add Squad Group
    public function addsgroup($squad_id = NULL) {
        if ($squad_id) {
            $this->data['row'] = $this->backend->get_table_row('squad_group', array('squad_id' => $squad_id));
        }

        // validate form input
        $this->form_validation->set_rules('squad_name', 'Squad Name', 'trim|required');
        $this->form_validation->set_rules('user_id', 'Admin User', 'required|numeric');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('squad_id') == NULL) {
                $dataPost = array(
                    'squad_name' => $this->input->post('squad_name'),
                    'user_id' => $this->input->post('user_id'),
                    'site' => $this->input->post('site'),
                    'created' => date('Y-m-d H:i:s')
                );

                $insertId = $this->backend->insert_data('squad_group', $dataPost);
                $this->after_submit($insertId, "superadmin/addsgroup");
            } else {
                $dataPost = array(
                    'squad_name' => $this->input->post('squad_name'),
                    'user_id' => $this->input->post('user_id'),
                    'site' => $this->input->post('site'),
                    'created' => date('Y-m-d H:i:s')
                );

                $status = $this->backend->update_data('squad_group', array('squad_id' => $this->input->post('squad_id')), $dataPost);
                $this->after_submit($status, $this->agent->referrer());
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/addsgroup';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // delete squad group
    public function delete_sgroup($squad_id = NULL) {
        if (!$squad_id) {
            show_404();
        }

        $this->backend->update_data('squad_group', array('squad_id' => $squad_id), array('status' => 1));
        redirect('superadmin/squadgroup');
    }

    // Schedule
    public function schedule() {
        $config['base_url'] = site_url('superadmin/schedule');
        $config['total_rows'] = $this->backend->get_schedule(array('s.status' => 0));
        $config['per_page'] = CV_RECORD_PER_PAGE;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["count"] = $this->uri->segment(3) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        //list the users
        $this->data["list"] = $this->backend->get_schedule(array('s.status' => 0), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/schedule';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Add Squad Group
    public function addschedule($schedule_id = NULL) {
        if ($schedule_id) {
            $this->data['row'] = $this->backend->get_table_row('scheduled', array('schedule_id' => $schedule_id));
        }

        // validate form input
        $this->form_validation->set_rules('admin_id', 'admin', 'trim|required|numeric');
        $this->form_validation->set_rules('squad_id', 'squad', 'trim|required|numeric');
        $this->form_validation->set_rules('user_id', 'squad member', 'trim|required|numeric');
        $this->form_validation->set_rules('schedule_date', 'date', 'trim|required');
        $this->form_validation->set_rules('from_time', 'from time', 'trim|required');
        $this->form_validation->set_rules('to_time', 'to time', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $this->data["week"] = $this->backend->get_current_week();

            if ($this->input->post('schedule_id') == NULL) {
                $dataPost = array(
                    'sender_id' => $this->logged_in_id,
                    'admin_id' => $this->input->post('admin_id'),
                    'squad_id' => $this->input->post('squad_id'),
                    'user_id' => $this->input->post('user_id'),
                    'schedule_date' => $this->input->post('schedule_date'),
                    'from_time' => $this->input->post('from_time'),
                    'to_time' => $this->input->post('to_time'),
                    'week' => $this->data["week"]->week,
                    'month' => $this->data["week"]->month,
                    'year' => $this->data["week"]->year,
                    'created' => date('Y-m-d H:i:s')
                );

                $insertId = $this->backend->insert_data('scheduled', $dataPost);
                $this->after_submit($insertId, "superadmin/addschedule");
            } else {
                $dataPost = array(
                    'sender_id' => $this->logged_in_id,
                    'admin_id' => $this->input->post('admin_id'),
                    'squad_id' => $this->input->post('squad_id'),
                    'user_id' => $this->input->post('user_id'),
                    'schedule_date' => $this->input->post('schedule_date'),
                    'from_time' => $this->input->post('from_time'),
                    'to_time' => $this->input->post('to_time'),
                    'created' => date('Y-m-d H:i:s')
                );

                $status = $this->backend->update_data('scheduled', array('schedule_id' => $this->input->post('schedule_id')), $dataPost);
                $this->after_submit($status, $this->agent->referrer());
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/addschedule';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Search Schedule
    public function searchschedule() {
        // get search string
        $search = ($this->input->post("search")) ? $this->input->post("search") : '';

        $replace = str_replace(' ', '%', $search);
        $this->data["list"] = $this->backend->get_all_schedule("(u.first_name LIKE '%$replace%' OR u.last_name LIKE '%$replace%' OR s.schedule_date LIKE '%$replace%' OR s.from_time LIKE '%$replace%' OR s.to_time LIKE '%$replace%') AND s.status = 0");

        $this->data['content'] = 'superadmin/schedule';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Delete Week
    public function delete_schedule($schedule_id = NULL) {
        if (!$schedule_id) {
            show_404();
        }

        $this->backend->update_data('scheduled', array('schedule_id' => $schedule_id), array('status' => 1));
        redirect('superadmin/schedule');
    }

    // Week Entry
    public function getweeks() {
        $config['base_url'] = site_url('superadmin/getweeks');
        $config['total_rows'] = $this->backend->get_weeks(array('w.status' => 0));
        $config['per_page'] = CV_RECORD_PER_PAGE;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["count"] = $this->uri->segment(3) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        //list the users
        $this->data["list"] = $this->backend->get_weeks(array('w.status' => 0), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/getweeks';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Search Weeks
    public function searchweek() {
        // get search string
        $search = ($this->input->post("search")) ? $this->input->post("search") : '';
        //$search = ($this->uri->segment(4)) ? $this->uri->segment(4) : $search;

        $replace = str_replace(' ', '%', $search);
        $this->data["list"] = $this->backend->get_all_week("(u.first_name LIKE '%$replace%' OR u.last_name LIKE '%$replace%' OR w.week LIKE '%$replace%' OR w.month LIKE '%$replace%' OR w.from_date LIKE '%$replace%' OR w.to_date LIKE '%$replace%') AND w.status = 0");

        $this->data['content'] = 'superadmin/getweeks';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Add Week
    public function addweek($id = NULL) {
        if ($id) {
            $this->data['row'] = $this->backend->get_table_row('week', array('id' => $id));
        }

        // validate form input
        $this->form_validation->set_rules('week', 'Week', 'required|numeric');
        $this->form_validation->set_rules('month', 'Month', 'required|numeric');
        $this->form_validation->set_rules('from_date', 'Start Date', 'required');
        $this->form_validation->set_rules('to_date', 'End Date', 'required');
        $this->form_validation->set_rules('user_id', 'Admin', 'required');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('id') == NULL) {
                $dataPost = array(
                    'week' => $this->input->post('week'),
                    'month' => $this->input->post('month'),
                    'year' => date('Y'),
                    'from_date' => $this->input->post('from_date'),
                    'to_date' => $this->input->post('to_date'),
                    'user_id' => $this->input->post('user_id')
                );

                $insertId = $this->backend->insert_data('week', $dataPost);
                $this->after_submit($insertId, "superadmin/addweek");
            } else {
                $dataPost = array(
                    'week' => $this->input->post('week'),
                    'month' => $this->input->post('month'),
                    'from_date' => $this->input->post('from_date'),
                    'to_date' => $this->input->post('to_date'),
                    'user_id' => $this->input->post('user_id')
                );

                $status = $this->backend->update_data('week', array('id' => $this->input->post('id')), $dataPost);
                $this->after_submit($status, $this->agent->referrer());
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/addweek';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Delete Week
    public function delete_week($id = NULL) {
        if (!$id) {
            show_404();
        }

        $this->backend->update_data('week', array('id' => $id), array('status' => 1));
        redirect('superadmin/getweeks');
    }

    // Sub Assessment
    public function sub_assessment() {
        $this->data['list'] = $this->backend->get_sub_assessment(array('s.status' => 0));

        $this->data['content'] = 'superadmin/sub_assessment';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Create Level
    public function add_sub_assessment($sub_ass_id = NULL) {
        if ($sub_ass_id) {
            $this->data['row'] = $this->backend->get_table_row('sub_assessment', array('sub_ass_id' => $sub_ass_id));
        }

        // validate form input
        $this->form_validation->set_rules('user_id', 'Admin', 'trim|required');
        $this->form_validation->set_rules('assessment_id', 'Assessment', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('sub_ass_id') == NULL) {
                $dataPost = array(
                    'user_id' => $this->input->post('user_id'),
                    'assessment_id' => $this->input->post('assessment_id'),
                    'name' => $this->input->post('name'),
                    'created' => date('Y-m-d H:i:s')
                );

                $insertId = $this->backend->insert_data('sub_assessment', $dataPost);
                $this->after_submit($insertId, "superadmin/add_sub_assessment");
            } else {
                $dataPost = array(
                    'user_id' => $this->input->post('user_id'),
                    'assessment_id' => $this->input->post('assessment_id'),
                    'name' => $this->input->post('name'),
                    'created' => date('Y-m-d H:i:s')
                );

                $status = $this->backend->update_data('sub_assessment', array('sub_ass_id' => $this->input->post('sub_ass_id')), $dataPost);
                $this->after_submit($status, $this->agent->referrer());
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();
        $this->data['assessment'] = $this->backend->get_table('assessment');

        $this->data['content'] = 'superadmin/add_sub_assessment';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Delete Sub Assessment
    public function delete_sub_assessment($sub_ass_id = NULL) {
        if (!$sub_ass_id) {
            show_404();
        }

        $this->backend->update_data('sub_assessment', array('sub_ass_id' => $sub_ass_id), array('status' => 1));
        redirect('superadmin/sub_assessment');
    }

    // Category
    public function category() {
        $this->data['list'] = $this->backend->get_category(array('c.status' => 0));

        $this->data['content'] = 'superadmin/category';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Add Category
    public function add_category($cat_id = NULL) {
        if ($cat_id) {
            $this->data['row'] = $this->backend->get_table_row('category', array('cat_id' => $cat_id));
        }

        // validate form input
        $this->form_validation->set_rules('user_id', 'Admin', 'trim|required');
        $this->form_validation->set_rules('sub_ass_id', 'sub_ass_id', 'trim|required');
        $this->form_validation->set_rules('cat_name', 'cat_name', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('cat_id') == NULL) {
                $dataPost = array(
                    'user_id' => $this->input->post('user_id'),
                    'sub_ass_id' => $this->input->post('sub_ass_id'),
                    'cat_name' => $this->input->post('cat_name'),
                    'weighting' => $this->input->post('weighting'),
                    'created' => date('Y-m-d H:i:s')
                );

                $insertId = $this->backend->insert_data('category', $dataPost);
                $this->after_submit($insertId, "superadmin/add_category");
            } else {
                $dataPost = array(
                    'user_id' => $this->input->post('user_id'),
                    'sub_ass_id' => $this->input->post('sub_ass_id'),
                    'cat_name' => $this->input->post('cat_name'),
                    'weighting' => $this->input->post('weighting'),
                    'created' => date('Y-m-d H:i:s')
                );

                $status = $this->backend->update_data('category', array('cat_id' => $this->input->post('cat_id')), $dataPost);
                $this->after_submit($status, $this->agent->referrer());
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/add_category';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Delete Category
    public function delete_cat($cat_id = NULL) {
        if (!$cat_id) {
            show_404();
        }

        $this->backend->update_data('category', array('cat_id' => $cat_id), array('status' => 1));
        redirect('superadmin/category');
    }

    // Questions
    public function question() {
        $config['base_url'] = site_url('superadmin/question');
        $config['total_rows'] = $this->backend->get_questions(array('q.status' => 0));
        $config['per_page'] = '20';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["count"] = $this->uri->segment(3) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        $this->data["questions"] = $this->backend->get_questions(array('q.status' => 0), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/question';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Add Question
    public function add_que($question_id = NULL) {
        if ($question_id) {
            $this->data["row"] = $this->backend->get_table_row('question', array("question_id" => $question_id));
        }

        // validation
        $this->form_validation->set_rules('user_id', 'Admin', 'trim|required');
        $this->form_validation->set_rules('cat_id', 'cat_id', 'trim|required');
        $this->form_validation->set_rules('description', 'Question', 'trim|required');
        $this->form_validation->set_rules('evaluation', 'evaluation', 'trim');
        $this->form_validation->set_rules('weight', 'Weight', 'required|numeric');

        if ($this->input->post('is_parent') == '1') {
            $this->form_validation->set_rules('parent_id', 'Parent Question', 'trim|required', array('required' => 'Please select parent question.'));
            $this->form_validation->set_rules('answer_id', 'Answer', 'trim|required', array('required' => 'Please select answer of question.'));
        }

        $this->form_validation->set_rules('no_answer', "No of Answer", "required|numeric");
        if ($this->form_validation->run() === TRUE) {
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
                    'user_id' => $this->input->post('user_id'),
                    'cat_id' => $this->input->post('cat_id'),
                    'description' => $this->input->post('description'),
                    'evaluation' => $this->input->post('evaluation'),
                    'is_parent' => $this->input->post('is_parent'),
                    'has_child' => $this->input->post('has_child'),
                    'parent_id' => $parent_id,
                    'answer_id' => $answer_id,
                    'count' => $count,
                    'weight' => $this->input->post('weight'),
                    'no_answer' => $this->input->post('no_answer'),
                    'created' => date('Y-m-d H:i:s')
                );

                $question_id = $this->backend->insert_data('question', $data);
                if ($question_id) {
                    $batchData = array();

                    for ($i = 0; $i < count($this->input->post('answer')); $i++) {
                        $batchData[$i]['answer'] = $this->input->post('answer')[$i];
                        $batchData[$i]['rating'] = $this->input->post('rating')[$i];
                        $batchData[$i]['weighting'] = $this->input->post('weighting')[$i];
                        $batchData[$i]['is_zero'] = $this->input->post('is_zero')[$i];
                        $batchData[$i]['section_zero'] = $this->input->post('section_zero')[$i];
                        $batchData[$i]['question_id'] = $question_id;
                        $batchData[$i]['created'] = date('Y-m-d H:i:s');
                    }

                    $status = $this->backend->insert_batch('answer', $batchData);
                }

                $this->after_submit($status, "superadmin/add_que");
            }
        }

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

        $this->data['content'] = 'superadmin/add_que';
        $this->load->view('superadmin/common/main', $this->data);
    }

    // Delete Question
    public function delete_question($question_id = NULL) {
        if (!$question_id) {
            show_404();
        }

        $this->backend->update_data('question', array('question_id' => $question_id), array('status' => 1));
        redirect('superadmin/question');
    }

    function after_submit($status, $redirect) {
        if ($status) {
            $this->session->set_flashdata('message', 'Your Data Has Been Successfully Saved.');
        } else {
            $this->session->set_flashdata('message', 'Failed! Please try after sometime.');
        }

        redirect($redirect, 'refresh');
    }

}
