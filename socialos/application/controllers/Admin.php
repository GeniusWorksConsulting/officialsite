<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->lang->load('auth');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group('admin')) {
            $this->ion_auth->logout();
            $this->session->set_flashdata('message', 'You must be an admin to view the user groups page.');

            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();
        $this->user_id = $user->id;
        $this->logged_in_name = $user->first_name . ' ' . $user->last_name;
        $this->group_name = $this->ion_auth->get_users_groups($user->id)->row()->name;

        $this->user_acl = $this->ion_auth_acl->build_acl($user->id);
        $this->load->model('backend_model', 'backend');
    }

    // Index Page
    public function index() {
        $this->data['content'] = 'admin/welcome';
        $this->load->view('admin/common/main', $this->data);
    }

    // Users
    public function users() {
        $name = $this->uri->segment(3);
        if (!$this->ion_auth_acl->has_permission($name)) {
            $this->session->set_flashdata('access_alert', "You don't have permission to view this page.");
            redirect('admin/index', 'refresh');
        }

        $config['base_url'] = site_url('admin/users/' . $name);
        $config['total_rows'] = $this->backend->get_users(array('g.name' => $name));
        $config['per_page'] = CV_RECORD_PER_PAGE;

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $this->data["count"] = $this->uri->segment(4) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        //list the users
        $this->data["users"] = $this->backend->get_users(array('g.name' => $name, 'ua.admin_id' => $this->user_id), $config['per_page'], $page);

        $this->data['content'] = 'admin/users';
        $this->load->view('admin/common/main', $this->data);
    }

    // Search
    public function search() {
        // get search string
        $search = ($this->input->post("search")) ? $this->input->post("search") : '';
        //$search = ($this->uri->segment(4)) ? $this->uri->segment(4) : $search;

        $replace = str_replace(' ', '%', $search);
        $this->data["users"] = $this->backend->get_all_users("(u.first_name LIKE '%$replace%' OR u.last_name LIKE '%$replace%' OR u.email LIKE '%$replace%' OR g.description LIKE '%$replace%') AND ua.admin_id = " . $this->user_id . " AND u.active = 1 AND g.name = '" . $this->uri->segment(3) . "' ");

        $this->data['content'] = 'admin/users';
        $this->load->view('admin/common/main', $this->data);
    }

    // Category
    public function category() {
        $this->data['list'] = $this->backend->get_category(array('c.status' => 0));

        $this->data['content'] = 'superadmin/category';
        $this->load->view($this->group_name . '/common/main', $this->data);
    }

    // Add Category
    public function add_category($cat_id = NULL) {
        if ($cat_id) {
            $this->data['row'] = $this->backend->get_table_row('category', array('cat_id' => $cat_id));
        }

        // validate form input
        $this->form_validation->set_rules('sub_ass_id', 'sub_ass_id', 'trim|required');
        $this->form_validation->set_rules('cat_name', 'cat_name', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            if ($this->input->post('cat_id') == NULL) {
                $dataPost = array(
                    'user_id' => $this->user_id,
                    'sub_ass_id' => $this->input->post('sub_ass_id'),
                    'cat_name' => $this->input->post('cat_name'),
                    'weighting' => $this->input->post('weighting'),
                    'created' => date('Y-m-d H:i:s')
                );

                $insertId = $this->backend->insert_data('category', $dataPost);
                after_submit_helper($insertId, $this->agent->referrer());
            } else {
                $dataPost = array(
                    'user_id' => $this->user_id,
                    'sub_ass_id' => $this->input->post('sub_ass_id'),
                    'cat_name' => $this->input->post('cat_name'),
                    'weighting' => $this->input->post('weighting'),
                    'created' => date('Y-m-d H:i:s')
                );

                $status = $this->backend->update_data('category', array('cat_id' => $this->input->post('cat_id')), $dataPost);
                after_submit_helper($status, $this->agent->referrer());
            }
        }

        $this->data['assessments'] = $this->backend->get_table('sub_assessment', array('status' => 0, 'user_id' => $this->user_id));

        $this->data['content'] = 'superadmin/add_category';
        $this->load->view($this->group_name . '/common/main', $this->data);
    }

    // Delete Category
    public function delete_cat($cat_id = NULL) {
        if (!$cat_id) {
            show_404();
        }

        $this->backend->update_data('category', array('cat_id' => $cat_id), array('status' => 1));
        redirect($this->group_name . '/category');
    }

    // Questions
    public function question() {
        $config['base_url'] = site_url($this->group_name . '/question');
        $config['total_rows'] = $this->backend->get_questions(array('q.status' => 0));
        $config['per_page'] = '20';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $this->data["count"] = $this->uri->segment(3) + 1;
        $this->data["pagination"] = $this->pagination->create_links();

        $this->data["questions"] = $this->backend->get_questions(array('q.status' => 0), $config['per_page'], $page);

        $this->data['content'] = 'superadmin/question';
        $this->load->view($this->group_name . '/common/main', $this->data);
    }

    // Add Question
    public function add_que($question_id = NULL) {
        if ($question_id) {
            $this->data["row"] = $this->backend->get_table_row('question', array("question_id" => $question_id));
        }

        // validation
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
                    'user_id' => $this->user_id,
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

                after_submit_helper($status, $this->agent->referrer());
            }
        }

        $this->data['categories'] = $this->backend->get_table('category', array('status' => 0, 'user_id' => $this->user_id));

        $this->data['content'] = 'superadmin/add_que';
        $this->load->view($this->group_name . '/common/main', $this->data);
    }

    // Delete Question
    public function delete_question($question_id = NULL) {
        if (!$question_id) {
            show_404();
        }

        $this->backend->update_data('question', array('question_id' => $question_id), array('status' => 1));
        redirect($this->group_name . '/question');
    }

    // @return array A CSRF key-value pair
    public function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    // @return bool Whether the posted CSRF token matches
    public function _valid_csrf_nonce() {
        $csrfkey = $this->input->post($this->session->flashdata('csrfkey'));
        if ($csrfkey && $csrfkey === $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    // Change Password
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
            $this->load->view('admin/common/main', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect($this->agent->referrer(), 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect($this->agent->referrer(), 'refresh');
            }
        }
    }

}
