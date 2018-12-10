<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Qa extends CI_Controller {

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

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group('Qa')) {
            $this->ion_auth->logout();
            $this->session->set_flashdata('message', 'You must be an Qa to view the user groups page.');

            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();
        $this->logged_in_id = $user->id;
        $this->logged_in_name = $user->first_name . ' ' . $user->last_name;
        $this->profile = $user->profile;
        $this->group_name = $this->ion_auth->get_users_groups($user->id)->row()->name;
        // Admin Id
        $this->user_id = $this->backend->get_admin_id(array('user_id' => $user->id));
    }

    // Dashboard
    public function index() {
        $admin_id = $this->backend->get_admin_id(array('user_id' => $this->logged_in_id));

        $this->data['squad_list'] = $this->backend->get_table('squad_group', array('user_id' => $admin_id, 'status' => 0));
        $this->data['content'] = 'Qa/dashboard';
        $this->load->view('Qa/common/main', $this->data);
    }

    // setting
    public function setting() {
        if (isset($_POST['btn_submit'])) {
            $this->form_validation->set_rules('first_name', 'First Name', 'trim|required');
            $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required');

            if ($this->form_validation->run() === TRUE) {
                $dataPost = $this->input->post();

                $file_name = image_upload_helper($_FILES['thumb'], PROFILE_PATH, $dataPost['filename'], $this->agent->referrer());

                $data = array(
                    'first_name' => $dataPost['first_name'],
                    'last_name' => $dataPost['last_name'],
                    'profile' => $file_name
                );

                $this->ion_auth->update($this->logged_in_id, $data);
                redirect($this->agent->referrer(), 'refresh');
            }
        }

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
            $this->data['user'] = $user;

            $this->data['content'] = 'Qa/setting';
            $this->load->view('Qa/common/main', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message`', $this->ion_auth->messages());
                redirect($this->agent->referrer(), 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect($this->agent->referrer(), 'refresh');
            }
        }
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
        $this->load->view($this->group_name.'/common/main', $this->data);
    }

    // Delete Question
    public function delete_question($question_id = NULL) {
        if (!$question_id) {
            show_404();
        }

        $this->backend->update_data('question', array('question_id' => $question_id), array('status' => 1));
        redirect($this->group_name.'/question');
    }

    /** Old Source Code */

    /**
     * View Score
     */
    public function viewscore() {
        $this->data['week'] = $this->backend->getcurrentweek();
        $this->data['type'] = $this->backend->get_table('type');

        $this->data['squadList'] = $this->backend->get_table('squad_group');

        $this->data['title'] = 'View Score';
        $this->data['body'] = 'users/viewscore';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * User details
     */
    public function user_details($user_id = NULL) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

        if (!$user_id) {
            show_404();
        }

        $user = $this->ion_auth->user($user_id)->row();
        $week = $this->backend->getcurrentweek();

        $this->data['week'] = $week;
        $this->data['type'] = $this->backend->get_table('type');

        $this->data['qarating'] = get_checkassessment_helper(array('w.receiver_id' => $user->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.is_squad' => 1, 'w.year' => $week->year));
        $this->data['user'] = $user;

        $condition = array('u.squad_group' => $user->squad_group, 'u.active' => 1);
        $this->data['members'] = get_activeusers_heper($condition, $week->week, $week->month, $week->year);

        $this->data['title'] = 'User Assessments';
        $this->data['body'] = 'users/user_assessment';
        $this->load->view('layouts/main', $this->data);
    }

    public function user_details_new($user_id = NULL) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("you don't have permission the page.");
        }

        if (!$user_id) {
            show_404();
        }

        $user = $this->ion_auth->user($user_id)->row();
        $week = $this->backend->getCurrentweek();

        $condition1 = array('a.status' => 0, 'a.week' => $week->week, 'a.month' => $week->month, 'a.year' => $week->year);
        $condition2 = array('u.squad_group' => $user->squad_group, 'u.active' => 1);

        $this->data['week'] = $week;
        $this->data['type'] = $this->backend->get_table('type');

        $this->data['qarating'] = get_checkassessment_helper(array('w.receiver_id' => $user->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.is_squad' => 1, 'w.year' => $week->year));
        $this->data['user'] = $user;
        $this->data['users'] = get_activeusers_heper('id, first_name, squad_group, last_name, profile', $condition1, $condition2);
        $this->data['title'] = 'User Assessments';
        $this->data['body'] = 'users/user_assessment';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * claim squad for assessment
     */
    public function claimSquad($squad_group = NULL) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

        if ($squad_group == NULL) {
            show_404();
        }

        $user = $this->ion_auth->user()->row();
        $currentWeek = $this->backend->getcurrentweek();

        $isClaim = $this->backend->get_table_row('qa_squad', array('user_id' => $user->id, 'squad_group' => $squad_group, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year,));
        if ($isClaim) {
            $this->session->set_flashdata('message', 'You already cliamed this squad.');
            redirect('users/index');
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

        redirect('users/index');
    }

    /**
     * View Category
     */
    public function viewcategory() {
        $this->data["list"] = $this->backend->categories(array('c.status' => 0));

        $this->data['title'] = 'View Category';
        $this->data['body'] = 'users/viewcategory';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Add Category
     */
    public function addcategory($cat_id = null) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

        if ($cat_id) {
            $this->data["row"] = $this->backend->get_table_row('category', array("cat_id" => $cat_id));
        }

        $this->data["list"] = $this->backend->get_table('assessment');

        $this->data['title'] = 'Add Category';
        $this->data['body'] = 'users/addcategory';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Save Category
     */
    public function savecategory() {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

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
            redirect('users/addcategory');
        }

        $this->data["list"] = $this->backend->get_table('assessment');

        $this->data['title'] = 'Add Category';
        $this->data['body'] = 'users/addcategory';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Delete Category
     */
    public function deletecategory($cat_id = null) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

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

        redirect('users/viewcategory');
    }

    /**
     * View Question
     */
    public function viewquestion() {
        $config['base_url'] = site_url('users/viewquestion');
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
     * Add Question
     */
    public function addquestion($question_id = null) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

        if ($question_id) {
            $this->data["row"] = $this->backend->get_table_row('question', array("question_id" => $question_id));
        }

        $this->data["list"] = $this->backend->get_table('category', array('status' => 0));

        $this->data['title'] = 'Add Question';
        $this->data['body'] = 'users/addquestion';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Delete Question
     */
    public function deletequestion($question_id = null) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

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

        redirect('users/viewquestion');
    }

    /**
     * Save Question
     */
    public function saveQuestion() {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

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
                redirect('users/addquestion');
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
                redirect('users/addquestion');
            }
        }

        $this->data["list"] = $this->backend->get_table('category', array('status' => 0));

        $this->data['title'] = 'Add Question';
        $this->data['body'] = 'users/addquestion';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Create a new Squad Member
     */
    public function addSquad() {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

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
            redirect("users/addSquad", 'refresh');
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
            $this->data['body'] = 'users/addSquad';
            $this->load->view('layouts/main', $this->data);
        }
    }

    /**
     * Edit a QA
     *
     * @param int|string $id
     */
    public function editSquad($id) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

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
                    redirect('users/viewSquad', 'refresh');
                } else {
                    // redirect them back to the qamember page if qamember, or to the base url if non qamember
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                    redirect('users/viewSquad', 'refresh');
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
        $this->data['body'] = 'users/editSquad';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Manage Squad Member
     */
    public function viewSquad() {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

        $config['base_url'] = site_url('users/viewSquad');
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

        $data["currentWeek"] = $this->backend->getcurrentweek();
        $data['title'] = 'Manage Squad';
        $data['body'] = 'users/viewSquad';
        $this->load->view('layouts/main', $data);
    }

    /**
     * Activate the user
     *
     * @param int         $id   The user ID
     * @param string|bool $code The activation code
     */
    public function activate($id, $code = FALSE) {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

        if ($code !== FALSE) {
            $activation = $this->ion_auth->activate($id, $code);
        } else {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            // redirect them to the auth page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("users/index", 'refresh');
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
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

        $id = (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() === FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->data['title'] = lang('deactivate_heading');
            $this->data['body'] = 'users/deactivate_user';
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
            redirect('users/index', 'refresh');
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
     * Add Rating
     */
    public function addrating() {
        if (!$this->ion_auth->in_group('qamember')) {
            return show_error("You don't have permission to access this page.");
        }

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

                $rating = $rating + round($section, 2);
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
                'is_squad' => 1,
                'rating' => $rating,
                'week' => $currentWeek->week,
                'month' => $currentWeek->month,
                'year' => $currentWeek->year,
                'created' => date('Y-m-d H:i:s')
            );

            $condition = array('sender_id' => $dataPost['sender_id'], 'receiver_id' => $dataPost['receiver_id'], 'assessment' => $dataPost['assessment'], 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year);
            $row = $this->backend->get_table_row('week_rating', $condition);
            if ($row) {
                $status = $this->backend->update_data('week_rating', $condition, $dataInsert);
            } else {
                $status = $this->backend->insert_data('week_rating', $dataInsert);
            }

            if ($status) {
                redirect('users/index/' . base64_encode($this->encrypt->encode($dataInsert['receiver_id'])));
                //$this->session->set_flashdata('message', 'Your Rating Has Been Given Successfully Given.');
            } else {
                $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
            }

            redirect('users/index', 'refresh');
        }

        $user_id = $this->uri->segment(3);
        $assessment = $this->uri->segment(4);

        if ($user_id == NULL || $assessment == NULL) {
            show_404();
        }

        $ass_list = $this->backend->get_table('type');
        foreach ($ass_list as $value) {
            $value->rating = $this->backend->getuser_rating(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $value->type_id, 'w.receiver_id' => $user_id, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
        }

        $this->data['type'] = $ass_list;

        $this->data['categories'] = $this->backend->categories(array('c.status' => 0, 'a.assessment' => $assessment));
        $this->data['row'] = $this->backend->get_table_row('assessment', array('assessment' => $assessment));

        $this->data['receiver_data'] = $this->backend->get_table_row('users', array('id' => $user_id));

        $this->data['title'] = 'Add Rating';
        $this->data['body'] = 'users/addrating';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * one assessment form
     */
    public function one_assessment() {
        $user_id = $this->uri->segment(3);
        $type_id = $this->uri->segment(4);

        if ($user_id == NULL || $type_id == NULL) {
            show_404();
        }

        $week = $this->backend->getcurrentweek();

        $row = $this->backend->get_table_row('one_assessment', array('user_id' => $user_id, 'sender_id' => $this->logged_in_id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));

        if ($row) {
            $this->session->set_flashdata('message', 'You have already selected one assessment.');
            redirect('users/index');
        }

        $data = array(
            'user_id' => $user_id,
            'sender_id' => $this->logged_in_id,
            'type_id' => $type_id,
            'week' => $week->week,
            'month' => $week->month,
            'year' => $week->year
        );

        $this->backend->insert_data('one_assessment', $data);
        $this->session->set_flashdata('message', 'One assessment was removed.');
        redirect('users/index');
    }

    public function rollback() {
        $user_id = $this->uri->segment(3);
        $type_id = $this->uri->segment(4);

        if ($user_id == NULL || $type_id == NULL) {
            show_404();
        }

        $week = $this->backend->getcurrentweek();
        $row = $this->backend->get_table_row('one_assessment', array('user_id' => $user_id, 'sender_id' => $this->logged_in_id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));

        if (!$row) {
            $this->session->set_flashdata('message', 'Something wrong please try after sometime.');
            redirect('users/index');
        }

        $condition = array(
            'user_id' => $user_id,
            'sender_id' => $this->logged_in_id,
            'type_id' => $type_id,
            'week' => $week->week,
            'month' => $week->month,
            'year' => $week->year
        );

        $boolean = $this->backend->delete_data('one_assessment', $condition);
        if ($boolean) {
            $this->session->set_flashdata('message', 'Success.');
        } else {
            $this->session->set_flashdata('message', 'Failed.');
        }

        redirect('users/index');
    }

    /**
     * Assessment Comment
     */
    public function submit_comment() {
        $this->form_validation->set_rules('message', 'message', 'trim|required');

        if ($this->form_validation->run() === TRUE) {
            $dataPost = $this->input->post();
            $week = $this->backend->getcurrentweek();

            $data = array(
                'sender_id' => $this->logged_in_id,
                'user_id' => $dataPost['user_id'],
                'message' => $dataPost['message'],
                'group_id' => $this->ion_auth->get_users_groups()->row()->id,
                'title' => $dataPost['title'],
                'week' => $week->week,
                'month' => $week->month,
                'year' => $week->year,
                'created' => date('Y-m-d H:i:s')
            );

            $this->backend->insert_data('messages', $data);
            $this->session->set_flashdata('message', 'Your Rating Has Been Given Successfully Given.');
            redirect('users/index');
        }

        $this->session->set_flashdata('message', 'Something Wrong.');
        redirect('users/index');
    }

    /**
     * Change password
     */
    public function change_password() {
        $this->data['title'] = 'Change Password';
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

            // view
            $this->data['body'] = 'users/change-password';
            $this->load->view('layouts/main', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('users/index', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('users/change_password', 'refresh');
            }
        }
    }

    /**
     * View Previous Assessment
     */
    public function viewPrevious() {
        $this->data['list'] = $this->backend->previousAssessment('');

        $this->data['title'] = 'Previous Assessment';
        $this->data['body'] = 'users/viewPrevious';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * Dashboard
     */
    public function viewAssessment() {
        if (isset($_GET)) {
            $dataGet = $this->input->get();
            if ($dataGet['week'] != NULL & $dataGet['month'] != NULL & $dataGet['year'] != NULL) {
                //$user = $this->ion_auth->user()->row();

                $this->data['currentWeek'] = $dataGet;
                $this->data['type'] = $this->backend->get_table('type');
                $this->data['squadList'] = $this->backend->get_table('squad_group');

                $this->data['title'] = 'View Assessment';
                $this->data['body'] = 'users/viewAssessment';
                $this->load->view('layouts/main', $this->data);
            } else {
                show_404();
            }
        }
    }

    /**
     * Compliment or Complaint
     */
    public function compliment($user_id = NULL) {
        if (!$user_id) {
            show_404();
        }

        if (!$this->ion_auth->in_group(array('lead'))) {
            return show_error("You don't have permission to access this page.");
        }

        $this->data['title'] = 'Compliment or Complaint';
        $this->data['body'] = 'users/compliment';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * send compliment or complaint
     * @return type
     */
    public function send_compliment() {
        if (!$this->ion_auth->in_group(array('lead'))) {
            return show_error("You don't have permission to access this page.");
        }

        /* form
         * validation user_id, message
         */
        $this->form_validation->set_rules('title', 'title', 'required|trim');
        $this->form_validation->set_rules('sub_title', 'sub_title', 'required|trim');
        $this->form_validation->set_rules('message', 'message', 'required|trim');

        if ($this->form_validation->run() === TRUE) {
            $dataPost = $this->input->post();
            $week = $this->backend->getcurrentweek();
            $user = $this->ion_auth->user()->row();

            $data = array(
                'user_id' => $dataPost['user_id'],
                'sender_id' => $user->id,
                'message' => $dataPost['message'],
                'group_id' => $this->ion_auth->get_users_groups()->row()->id,
                'title' => $dataPost['title'],
                'sub_title' => $dataPost['sub_title'],
                'week' => $week->week,
                'month' => $week->month,
                'year' => $week->year,
                'created' => date('Y-m-d H:i:s')
            );

            $this->backend->insert_data('messages', $data);
            $this->session->set_flashdata('message', 'Your comment sent successfully.');
            redirect('users/index');
        }

        $this->data['title'] = 'Compliment or Complaint';
        $this->data['body'] = 'users/compliment';
        $this->load->view('layouts/main', $this->data);
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
     * assessment reports of squad member
     */
    public function reports() {
        $this->data['squad_group'] = $this->backend->get_table('squad_group');

        $this->data['title'] = 'Assessments Reports';
        $this->data['body'] = 'users/reports';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * assessment reports of squad member
     */
    public function getreports() {
        $dataPost = $this->input->post();

        $start_date = $dataPost['startdate'];
        $end_Date = $dataPost['enddate'];

        $startTime = strtotime($start_date);
        $endTime = strtotime($end_Date);

        $weeks = array();
        $date = new DateTime();
        $i = 0;

        while ($startTime < $endTime) {
            //$weeks[$i]['week'] = date('W', $startTime);
            //$weeks[$i]['year'] = date('Y', $startTime);
            $date->setISODate(date('Y', $startTime), date('W', $startTime));
            $weeks[$i]['startdate'] = $date->format('Y-m-d');
            $weeks[$i]['enddate'] = date('Y-m-d', strtotime($weeks[$i]['startdate'] . "+6 days"));
            $startTime += strtotime('+1 week', 0);
            $i++;
        }

        //var_dump($weeks);
        $weekdata = array();
        $users = $this->backend->get_selected_table('users', 'id, cast(0 as decimal(10,2)) as textSum, cast(0 as decimal(10,2)) as voiceSum, cast(0 as decimal(10,2)) as text, cast(0 as decimal(10,2)) as voice, first_name, last_name', array('active' => 1, 'squad_group' => $dataPost['squad_group']));
        $count = $this->backend->count_table_rows('type');

        foreach ($weeks as $w) {
            $week = $this->backend->getWeek(array('from_date' => $w['startdate'], 'to_date' => $w['enddate']));
            if ($week) {
                //$weekData = $this->backend->assessment_report(array('w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year, 'is_squad' => 0, 'w.squad_group' => $dataPost['squad_group']));
                $return = $this->checkWeek($users, $week, $dataPost['squad_group'], $count);
                if ($return) {
                    $weekdata[] = $week;
                }
            }
        }

        echo json_encode(array('users' => $users, 'weeks' => $weekdata));
    }

    function checkWeek($users, $week, $group, $count) {
        $weekData = $this->backend->assessment_report(array('w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year, 'is_squad' => 0, 'w.squad_group' => $group), array("w.receiver_id", "w.sender_id"));

        if (sizeof($weekData) > 0) {
            foreach ($users as $receiver) {
                $this->senderRating($receiver, $users, $week, $count);
                $receiver->text = $receiver->text + ($receiver->textSum / sizeof($users));
                $receiver->voice = $receiver->voice + ($receiver->voiceSum / sizeof($users));

                $receiver->textSum = 0;
                $receiver->voiceSum = 0;
            }

            return $week;
        }
    }

    function senderRating($receiver, $users, $week, $count) {
        foreach ($users as $sender) {
            $isRating = $this->backend->assessment_report(array('w.receiver_id' => $receiver->id, 'w.sender_id' => $sender->id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year, 'is_squad' => 0));
            if (sizeof($isRating) == $count) {
                //var_dump($isRating);
                $this->sumRating($receiver, $isRating);
            }
        }
    }

    function sumRating($receiver, $isRating) {
        foreach ($isRating as $row) {
            if ($row->type_id == 1) {
                $receiver->textSum = $receiver->textSum + $row->rating;
            } else if ($row->type_id == 2) {
                $receiver->voiceSum = $receiver->voiceSum + $row->rating;
            }
        }
    }

}
