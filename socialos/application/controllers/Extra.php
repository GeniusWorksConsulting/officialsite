<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Extra
 *
 * @author StepUp - Chirag
 */
class Extra {
    //put your code here
    /**
     * QA User
     */
    public function Qa() {
        $config['base_url'] = site_url('superadmin/Qa');
        $config['total_rows'] = $this->backend->get_users(array('g.name' => 'qamember'));
        $config['per_page'] = CV_RECORD_PER_PAGE;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["count"] = $this->uri->segment(3) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        //list the users
        $this->data["users"] = $this->backend->get_users(array('g.name' => 'qamember'), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/Qa';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Search Qa
     */
    public function searchQa() {
        // get search string
        $search = ($this->input->post("search")) ? $this->input->post("search") : '';
        $search = ($this->uri->segment(4)) ? $this->uri->segment(4) : $search;

        $replace = str_replace(' ', '%', $search);
        $this->data["users"] = $this->backend->get_all_users("(u.first_name LIKE '%$replace%' OR u.last_name LIKE '%$replace%' OR u.email LIKE '%$replace%' OR g.description LIKE '%$replace%') AND u.active = 1 AND g.name = 'qamember' ");

        $this->data['content'] = 'superadmin/Qa';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * New Qa
     */
    public function addQa() {
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('admin_id', 'Admin', 'trim|required', array('required' => 'Please select admin name.'));
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
            $insertId = $this->ion_auth->register($identity, $password, $email, $additional_data, array(3));
            if ($insertId) {
                // redirect them back to the admin page
                $this->backend->insert_data('users_admin', array('user_id' => $insertId, 'admin_id' => $this->input->post('admin_id')));
                $this->session->set_flashdata('message', $this->ion_auth->messages());
            }
            redirect("superadmin/addQa", 'refresh');
        } else {
            // display the create user form or set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

            $this->data['content'] = 'superadmin/addQa';
            $this->load->view('superadmin/common/main', $this->data);
        }
    }

    /**
     * Edit Qa
     */
    public function editQa($id) {
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
                    redirect('superadmin/Qa', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('superadmin/Qa', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();
        $this->data['content'] = 'superadmin/editQa';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Squad
     */
    public function squads() {
        $config['base_url'] = site_url('superadmin/squad');
        $config['total_rows'] = $this->backend->get_users(array('g.name' => 'squad'));
        $config['per_page'] = CV_RECORD_PER_PAGE;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["count"] = $this->uri->segment(3) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        //list the users
        $this->data["users"] = $this->backend->get_users(array('g.name' => 'squad'), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/squad';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Search Squad
     */
    public function searchsquad() {
        // get search string
        $search = ($this->input->post("search")) ? $this->input->post("search") : '';
        $search = ($this->uri->segment(4)) ? $this->uri->segment(4) : $search;

        $replace = str_replace(' ', '%', $search);
        $this->data["users"] = $this->backend->get_all_users("(u.first_name LIKE '%$replace%' OR u.last_name LIKE '%$replace%' OR u.email LIKE '%$replace%' OR g.description LIKE '%$replace%') AND u.active = 1 AND g.name = 'squad' ");

        $this->data['content'] = 'superadmin/squad';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * New Squad
     */
    public function addsquad() {
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('admin_id', 'Admin', 'trim|required', array('required' => 'Please select admin name.'));
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
            $insertId = $this->ion_auth->register($identity, $password, $email, $additional_data, array(4));
            if ($insertId) {
                // redirect them back to the admin page
                $this->backend->insert_data('users_admin', array('user_id' => $insertId, 'admin_id' => $this->input->post('admin_id')));
                $this->session->set_flashdata('message', $this->ion_auth->messages());
            }
            redirect("superadmin/addsquad", 'refresh');
        } else {
            // display the create user form or set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

            $this->data['content'] = 'superadmin/addsquad';
            $this->load->view('superadmin/common/main', $this->data);
        }
    }

    /**
     * Edit Squad
     */
    public function editsquad($id) {
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
                    redirect('superadmin/squads', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('superadmin/squads', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();
        $this->data['content'] = 'superadmin/editsquad';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Lead User
     */
    public function lead() {
        $config['base_url'] = site_url('superadmin/lead');
        $config['total_rows'] = $this->backend->get_users(array('g.name' => 'lead'));
        $config['per_page'] = CV_RECORD_PER_PAGE;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["count"] = $this->uri->segment(3) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        //list the users
        $this->data["users"] = $this->backend->get_users(array('g.name' => 'lead'), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/lead';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * Search Lead
     */
    public function searchlead() {
        // get search string
        $search = ($this->input->post("search")) ? $this->input->post("search") : '';
        $search = ($this->uri->segment(4)) ? $this->uri->segment(4) : $search;

        $replace = str_replace(' ', '%', $search);
        $this->data["users"] = $this->backend->get_all_users("(u.first_name LIKE '%$replace%' OR u.last_name LIKE '%$replace%' OR u.email LIKE '%$replace%' OR g.description LIKE '%$replace%') AND u.active = 1 AND g.name = 'lead' ");

        $this->data['content'] = 'superadmin/lead';
        $this->load->view('superadmin/common/main', $this->data);
    }

    /**
     * New Lead Member
     */
    public function addlead() {
        $tables = $this->config->item('tables', 'ion_auth');
        $identity_column = $this->config->item('identity', 'ion_auth');
        $this->data['identity_column'] = $identity_column;

        // validate form input
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

        $this->form_validation->set_rules('email', 'Email Address', 'trim|required|valid_email|is_unique[' . $tables['users'] . '.email]');
        $this->form_validation->set_rules('admin_id', 'Admin', 'trim|required', array('required' => 'Please select admin name.'));
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
            $insertId = $this->ion_auth->register($identity, $password, $email, $additional_data, array(5));
            if ($insertId) {
                // redirect them back to the admin page
                $this->backend->insert_data('users_admin', array('user_id' => $insertId, 'admin_id' => $this->input->post('admin_id')));
                $this->session->set_flashdata('message', $this->ion_auth->messages());
            }
            redirect("superadmin/addlead", 'refresh');
        } else {
            // display the create user form or set the flash data error message if there is one
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['users'] = $this->ion_auth->users(array('admin'))->result();

            $this->data['content'] = 'superadmin/addlead';
            $this->load->view('superadmin/common/main', $this->data);
        }
    }

    /**
     * Edit Squad
     */
    public function editlead($id) {
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
                    redirect('superadmin/lead', 'refresh');
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('superadmin/lead', 'refresh');
                }
            }
        }

        // display the edit user form
        $this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['user'] = $user;

        $this->data['users'] = $this->ion_auth->users(array('admin'))->result();
        $this->data['content'] = 'superadmin/editlead';
        $this->load->view('superadmin/common/main', $this->data);
    }
}
