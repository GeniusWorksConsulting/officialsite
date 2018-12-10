<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Qamember extends CI_Controller {

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

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');

        $this->load->model('backend_model', 'backend');
        $group = array('qamember');

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group($group)) {
            $this->ion_auth->logout();
            $this->session->set_flashdata('message', 'You must be an QA to view the user groups page.');
            redirect('auth/login');
        }

        $user = $this->ion_auth->user()->row();
        $this->logged_in_id = $user->id;
        $this->logged_in_name = $user->first_name . ' ' . $user->last_name;
        $this->groups = $this->ion_auth->get_users_groups($user->id)->row()->description;
    }

    /**
     * Dashboard
     */
    public function index() {
        $currentWeek = $this->backend->getcurrentweek();
        $user = $this->ion_auth->user()->row();

        $this->data['currentWeek'] = $currentWeek;
        $this->data['type'] = $this->backend->get_table('type');
        //$this->data['squadList'] = $this->backend->get_table('qa_squad', array('user_id' => $user->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
        $this->data['previous'] = $this->backend->previousAssessment('', 4);

        $this->data['otherSquad'] = $this->backend->remainingSquad(array('user_id' => $user->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));

        $this->data['squadList'] = $this->backend->get_table('squad_group');
        $this->data['title'] = 'Dashboard';
        $this->data['body'] = 'qamember/dashboard';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * claim squad for assessment
     */
    public function claimSquad($squad_group = NULL) {
        if ($squad_group == NULL) {
            show_404();
        }

        $user = $this->ion_auth->user()->row();
        $currentWeek = $this->backend->getcurrentweek();

        $isClaim = $this->backend->get_table_row('qa_squad', array('user_id' => $user->id, 'squad_group' => $squad_group, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year,));
        if ($isClaim) {
            $this->session->set_flashdata('message', 'You already cliamed this squad.');
            redirect('qamember/index');
        }

        $entries = array(
            'user_id' => $user->id,
            'squad_group' => $squad_group,
            'week' => $currentWeek->week,
            'month' => $currentWeek->month,
            'year' => $currentWeek->year,
            'created' => date('Y-m-d H:i:s')
        );

        $insertedID = $this->backend->insert_data('qa_squad', $entries);
        if ($insertedID) {
            $this->session->set_flashdata('message', 'You Claim Squad Successfully.');
        } else {
            $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
        }

        redirect('qamember/index');
    }

    /**
     * get squad list of QA
     */
    public function getSquadlist() {
        $currentWeek = $this->backend->getcurrentweek();
        $user = $this->ion_auth->user()->row();

        $list = $this->backend->get_table('qa_squad', array('user_id' => $user->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));

        if (sizeof($list) > 0) {
            $response = array('status' => 0, 'response' => $list);
        } else {
            $response = array('status' => 1, 'response' => $list);
        }

        echo json_encode($response);
    }

    /**
     * View Category
     */
    public function viewcategory() {
        $this->data["list"] = $this->backend->categories(array('c.status' => 0));

        $this->data['title'] = 'View Category';
        $this->data['body'] = 'qamember/viewcategory';
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
        $this->data['body'] = 'qamember/addcategory';
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
            redirect('qamember/addcategory');
        }

        $this->data["list"] = $this->backend->get_table('assessment');

        $this->data['title'] = 'Add Category';
        $this->data['body'] = 'qamember/addcategory';
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

        redirect('qamember/viewcategory');
    }

    /**
     * View Question
     */
    public function viewquestion() {
        $this->data["list"] = $this->backend->questions(array('q.status' => 0));

        $this->data['title'] = 'View Questions';
        $this->data['body'] = 'qamember/viewquestion';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Add Question
     */
    public function addquestion($question_id = null) {
        if ($question_id) {
            $this->data["row"] = $this->backend->get_table_row('question', array("question_id" => $question_id));
        }

        $this->data["list"] = $this->backend->get_table('category', array('status' => 0));

        $this->data['title'] = 'Add Question';
        $this->data['body'] = 'qamember/addquestion';
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

        redirect('qamember/viewquestion');
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
            // redirect them back to the qamember page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("qamember/addSquad", 'refresh');
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
            $this->data['body'] = 'qamember/addSquad';
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
        //$this->form_validation->set_rules('phone', $this->lang->line('edit_user_validation_phone_label'), 'trim|required');
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
                    // redirect them back to the qamember page if qamember, or to the base url if non qamember
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                    redirect('qamember/viewSquad', 'refresh');
                } else {
                    // redirect them back to the qamember page if qamember, or to the base url if non qamember
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('qamember/viewSquad', 'refresh');
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
        $this->data['body'] = 'qamember/editSquad';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Manage Squad Member
     */
    public function viewSquad() {
        $config['base_url'] = site_url('qamember/viewSquad');
        $config['total_rows'] = $this->ion_auth->users(array('members'))->num_rows();
        $config['per_page'] = '20';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["count"] = $this->uri->segment(3) + 1;
        $data["pagination"] = $this->pagination->create_links();

        //list the users
        $data["users"] = $this->ion_auth->offset($page)->limit($config['per_page'])->users(array('members'))->result();

        foreach ($data['users'] as $k => $user) {
            $data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
        }

        $data['title'] = 'Manage Squad';
        $data['body'] = 'qamember/viewSquad';
        $this->load->view('layouts/main', $data);
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
        } else if ($this->ion_auth->in_group('qamember')) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("qamember/index", 'refresh');
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
            $this->data['body'] = 'qamember/deactivate_user';
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
            redirect('qamember/index', 'refresh');
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
     * Check Assessment
     */
    public function assessment() {
        $dataPost = $this->input->post();

        if ($dataPost['user_id'] == NULL || $dataPost['type_id'] == NULL) {
            echo json_encode(array('status' => 1, 'response' => 'Sorry, Something Went Wrong.'));
        } else {
            $assessment = $this->backend->get_table('assessment', array('type_id' => $dataPost['type_id']));

            if (sizeof($assessment) > 0) {
                echo json_encode(array('status' => 0, 'response' => $assessment, 'user_id' => $dataPost['user_id']));
            } else {
                echo json_encode(array('status' => 1, 'response' => 'Sorry, Something Went Wrong.'));
            }
        }
    }

    /**
     * Add Rating
     */
    public function addrating() {
        $currentWeek = $this->backend->getcurrentweek();

        if (isset($_POST['btnSubmit'])) {
            $dataPost = $this->input->post();

            $rating = 0;
            $isZero = 0;

            for ($j = 0; $j < count($dataPost['cat_id']); $j++) {
                $section = 0;
                $sectionZero = 0;
                //var_dump($dataPost['cat_id'][$j]);

                for ($i = 0; $i < count($dataPost['question_id']); $i++) {
                    $questionRow = $this->backend->get_table_row('question', array('question_id' => $dataPost['question_id'][$i]));
                    if ($questionRow->cat_id == $dataPost['cat_id'][$j]) {

                        if ($dataPost['answer'][$i] != NULL) {
                            $answerRow = $this->backend->get_table_row('answer', array('answer_id' => $dataPost['answer'][$i]));

                            if ($answerRow->isZero == 0) {
                                if ($answerRow->sectionZero == 1) {
                                    $sectionZero = 1;
                                }
                                if ($dataPost['has_child'][$i] != 1) {
                                    $section = $section + $answerRow->weighting;
                                    //$rating = $rating + $answerRow->weighting;
                                    //var_dump($answerRow->weighting);
                                } else {
                                    if ($dataPost['count'][$i] == 1) {
                                        $section = $section + $answerRow->weighting;
                                        //$rating = $rating + $answerRow->weighting;
                                        //var_dump($answerRow->weighting);
                                    }
                                }
                            } else {
                                $isZero = 1;
                                break 2;
                            }
                        }
                    }
                }

                if ($sectionZero == 1) {
                    $section = 0;
                }

                $rating = $rating + $section;
                //var_dump($rating);
            }

            if ($isZero == 0) {
                $rating = $rating * 100;
                if ($rating > 100) {
                    $rating = 100;
                }
            } else {
                $rating = 0;
            }

            $dataInsert = array(
                'sender_id' => $dataPost['sender_id'],
                'receiver_id' => $dataPost['receiver_id'],
                'assessment' => $dataPost['assessment'],
                'squad_group' => $dataPost['squad_group'],
                'rating' => $rating,
                'week' => $currentWeek->week,
                'month' => $currentWeek->month,
                'year' => $currentWeek->year,
                'created' => date('Y-m-d H:i:s')
            );


            $insertId = $this->backend->insert_data('week_rating', $dataInsert);
            if ($insertId) {
                $this->session->set_flashdata('message', 'Your Rating Has Been Given Successfully Given.');
            } else {
                $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
            }

            redirect('qamember/index', 'refresh');
        }

        $user_id = $this->uri->segment(3);
        $assessment = $this->uri->segment(4);

        if ($user_id == NULL || $assessment == NULL) {
            show_404();
        }

        $assessmentType = $this->backend->get_table('type');
        foreach ($assessmentType as $value) {
            $value->rating = $this->backend->getuserRating(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $value->type_id, 'w.receiver_id' => $user_id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
        }
        $this->data['type'] = $assessmentType;

        $this->data['categories'] = $this->backend->categories(array('c.status' => 0, 'a.assessment' => $assessment));
        $this->data['row'] = $this->backend->get_table_row('assessment', array('assessment' => $assessment));

        $this->data['receiver_data'] = $this->backend->get_table_row('users', array('id' => $user_id));

        $this->data['title'] = 'Add Rating';
        $this->data['body'] = 'qamember/addrating';
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
            $this->data['body'] = 'qamember/change-password';
            $this->load->view('layouts/main', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('qamember/index', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('qamember/change_password', 'refresh');
            }
        }
    }

    /**
     * View Previous Assessment
     */
    public function viewPrevious() {
        $this->data['list'] = $this->backend->previousAssessment(array('user_id' => $this->logged_in_id));

        $this->data['title'] = 'Previous Assessment';
        $this->data['body'] = 'qamember/viewPrevious';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Dashboard
     */
    public function viewAssessment() {
        if (isset($_GET)) {
            $dataGet = $this->input->get();
            if ($dataGet['week'] != NULL & $dataGet['month'] != NULL & $dataGet['year'] != NULL) {
                $user = $this->ion_auth->user()->row();

                $this->data['currentWeek'] = $dataGet;
                $this->data['type'] = $this->backend->get_table('type');
                $this->data['squadList'] = $this->backend->get_table('qa_squad', array('user_id' => $user->id, 'week' => $dataGet['week'], 'month' => $dataGet['month'], 'year' => $dataGet['year']));

                $this->data['title'] = 'View Assessment';
                $this->data['body'] = 'qamember/viewAssessment';
                $this->load->view('layouts/main', $this->data);
            } else {
                show_404();
            }
        }
    }

}
