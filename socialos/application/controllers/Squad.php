<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Squad extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->lang->load('auth');

        $this->load->model('backend_model', 'backend');

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group('squad')) {
            $this->ion_auth->logout();
            $this->session->set_flashdata('message', 'You must be an squad user to view the user groups page.');

            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        $this->logged_in_id = $user->id;
        $this->profile = $user->profile;
        $this->logged_in_name = $user->first_name . ' ' . $user->last_name;
        $this->groups = $this->ion_auth->get_users_groups($user->id)->row()->description;

        $week = $this->backend->get_current_week();
        //var_dump($week);
        //$this->is_paused = $this->backend->check_row_exist('pause_account', array('user_id' => $this->logged_in_id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year, 'status' => 0));
    }

    // Dashboard
    public function index() {
        $this->data['members'] = array();

        $this->data['content'] = 'squad/dashboard';
        $this->load->view('squad/common/main', $this->data);
    }

    function calculate_love($receiver_id, $members, $type, $week) {
        $user_love = 0;
        $paused = 1;

        foreach ($members as $u) {
            // if not paused
            if ($u->is_paused == 0) {
                if ($u->id == $receiver_id) {
                    continue;
                }
                $ratings = get_assessment_helper(array('w.receiver_id' => $receiver_id, 'w.sender_id' => $u->id, 'w.is_squad' => 0, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
                $user_love = $user_love + $this->sum_rating($ratings, $receiver_id, $u->id, $type, $week);
            }

            // if paused
            else {
                $paused++;
            }
        }

        return $user_love / (count($members) - $paused);
    }

    function sum_rating($ratings, $receiver_id, $sender_id, $type, $week) {
        if (sizeof($ratings) > 0) {
            $t_rating = 0;

            foreach ($ratings as $row) {
                $t_rating = $t_rating + $row->rating;
            }

            $one_ass = check_oneassessment(array('sender_id' => $sender_id, 'user_id' => $receiver_id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));
            if ($one_ass) {
                return $t_rating / (count($type) - count($one_ass));
            } else {
                return $t_rating / count($type);
            }
        }

        return 0;
    }

    function calculate_qa_love($user, $type, $week) {
        $qa_love = 0;
        $ci = & get_instance();
        $qa_list = $ci->backend->get_table('qa_squad', array('squad_group' => $user->squad_group, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));

        foreach ($qa_list as $qa) {
            $ratings = get_assessment_helper(array('w.receiver_id' => $user->id, 'w.sender_id' => $qa->user_id, 'w.is_squad' => 1, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
            $qa_love = $qa_love + $this->sum_rating($ratings, $user->id, $qa->user_id, $type, $week);
        }

        if ($qa_love > 0) {
            return $qa_love / count($qa_list);
        }

        return 0;
    }

    function calculate_self_love($user_id, $type, $week) {
        $ratings = get_assessment_helper(array('w.receiver_id' => $user_id, 'w.sender_id' => $user_id, 'w.is_squad' => 0, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
        $self_love = $this->sum_rating($ratings, $user_id, $user_id, $type, $week);

        return $self_love;
    }

    /**
     * calculate customer love for graph
     * @param type $week
     * @param type $user
     * @return type
     */
    function customer_love($week, $user) {
        $total = 0;
        $ratings = get_getallassessment_helper(array('w.receiver_id' => $user->id, 'w.is_squad' => 0, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
        //var_dump($ratings);
        if (sizeof($ratings) > 0) {
            foreach ($ratings as $row) {
                $total = $total + $row->rating;
            }
        }

        $count = $this->backend->count_table_rows('users', array('squad_group' => $user->squad_group));
        //var_dump($total);
        return $total / $count;
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
            redirect('squad/assessment');
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
        redirect('squad/assessment');
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
            redirect('squad/assessment');
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

        redirect('squad/assessment');
    }

    /**
     * Assessment
     */
    public function assessment($user_id = NULL) {
        $user = $this->ion_auth->user()->row();
        $week = $this->backend->getcurrentweek();

        if (isset($_POST['btnWeek'])) {
            $this->form_validation->set_rules('weekgoal', 'weekgoal', 'trim|required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $dataPost = array(
                    'weekgoal' => $this->input->post('weekgoal'),
                    'user_id' => $user->id,
                    'week' => $week->week,
                    'month' => $week->month,
                    'year' => $week->year,
                    'created' => date('Y-m-d H:i:s')
                );

                $this->backend->insert_data('weekgoal', $dataPost);
                redirect('squad/assessment', 'refresh');
            }
        }

        if (isset($_POST['btnMonth'])) {
            $this->form_validation->set_rules('monthgoal', 'monthgoal', 'trim|required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $dataPost = array(
                    'monthgoal' => $this->input->post('monthgoal'),
                    'user_id' => $user->id,
                    'month' => $week->month,
                    'year' => $week->year,
                    'created' => date('Y-m-d H:i:s')
                );

                $this->backend->insert_data('monthgoal', $dataPost);
                redirect('squad/assessment', 'refresh');
            }
        }

        $condition = array('u.squad_group' => $user->squad_group, 'u.active' => 1);
        $this->data['week_goal'] = $this->backend->get_table_row('weekgoal', array('user_id' => $user->id, 'week' => $week->week, 'month' => $week->month, 'year' => $week->year));
        $this->data['month_goal'] = $this->backend->get_table_row('monthgoal', array('user_id' => $user->id, 'month' => $week->month, 'year' => $week->year));

        $this->data['members'] = get_activeusers_heper($condition, $week->week, $week->month, $week->year);
        $this->data['week'] = $week;

        $this->data['type'] = $this->backend->get_table('type');
        $this->data['user_id'] = $this->encrypt->decode(base64_decode($user_id));

        $this->data['title'] = 'Assessment';
        $this->data['body'] = 'squad/assessment';
        $this->load->view('layouts/main_new', $this->data);
    }

    /**
     * Add Rating
     */
    public function addrating() {
        $week = $this->backend->getcurrentweek();
        $user = $this->ion_auth->user()->row();

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
                                    //echo $questionRow->description;
                                    //var_dump($answerRow->weighting);
                                    //$rating = $rating + $answerRow->weighting;
                                } else {
                                    if ($dataPost['count'][$i] == 1) {
                                        $section = $section + $answerRow->weighting;
                                        //echo $questionRow->description;
                                        //var_dump($answerRow->weighting);
                                        //$rating = $rating + $answerRow->weighting;
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
                //var_dump(round($section, 2));
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
                'squad_group' => $user->squad_group,
                'rating' => $rating,
                'week' => $week->week,
                'month' => $week->month,
                'year' => $week->year,
                'created' => date('Y-m-d H:i:s')
            );

            $insertId = $this->backend->insert_data('week_rating', $dataInsert);
            if ($insertId) {
                //$this->session->set_flashdata('user_id', $dataPost['receiver_id']);
                redirect('squad/assessment/' . base64_encode($this->encrypt->encode($dataInsert['receiver_id'])));
            } else {
                $this->session->set_flashdata('message', 'Sorry, Something Went Wrong.');
            }

            redirect('squad/assessment');
        }

        $user_id = $this->uri->segment(3);
        $assessment = $this->uri->segment(4);

        if ($user_id == NULL || $assessment == NULL) {
            show_404();
        }

        $type = $this->backend->get_table('type');
        foreach ($type as $value) {
            $value->rating = $this->backend->getuser_rating(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $value->type_id, 'w.receiver_id' => $user_id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
        }

        $row = $this->backend->get_table_row('assessment', array('assessment' => $assessment));

        // rating is give or not
        $this->data['is_given'] = $this->backend->isRating(array('w.sender_id' => $this->logged_in_id, 't.type_id' => $row->type_id, 'w.receiver_id' => $user_id, 'w.week' => $week->week, 'w.month' => $week->month, 'w.year' => $week->year));
        // total number of assessment
        $this->data['type'] = $type;

        // assessment category
        $this->data['categories'] = $this->backend->categories(array('c.status' => 0, 'a.assessment' => $assessment));
        // assessment row
        $this->data['row'] = $this->backend->get_table_row('assessment', array('assessment' => $assessment));

        // receiver
        $this->data['receiver_data'] = $this->backend->get_table_row('users', array('id' => $user_id));

        $this->data['title'] = 'Rating';
        $this->data['body'] = 'squad/addrating';
        $this->load->view('layouts/main_new', $this->data);
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
            redirect('squad/assessment');
        }

        $this->session->set_flashdata('message', 'Something Wrong.');
        redirect('squad/assessment');
    }

    /**
     * Setting
     */
    public function settings() {
        $this->data['title'] = 'Profile Setting';

        if (isset($_POST['btnProfile'])) {
            $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'trim|required');
            $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'trim|required');

            if ($this->form_validation->run() === TRUE) {
                if (!is_dir(PROFILE_PATH)) {
                    //Directory does not exist, so lets create it.
                    mkdir(PROFILE_PATH, 0755);
                }

                $dataPost = $this->input->post();
                $file_name = $dataPost['filename'];

                if (!empty($_FILES['profile']['name'])) {
                    $ext = pathinfo($_FILES['profile']['name'], PATHINFO_EXTENSION);

                    if ($dataPost['filename'] == NULL) {
                        $file_name = time() . '.' . $ext;
                    } else {
                        $file = $dataPost['filename'];
                        $oldname = substr($file, 0, strrpos($file, '.'));
                        $file_name = $oldname . '.' . $ext;
                    }

                    $config['upload_path'] = PROFILE_PATH;
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['file_name'] = $file_name;
                    $config['overwrite'] = TRUE;

                    //Load upload library and initialize configuration
                    $this->load->library('upload', $config);
                    $this->load->initialize($config);

                    if (!$this->upload->do_upload('profile')) {
                        $this->session->set_flashdata('message', $this->upload->display_errors());
                        redirect('squad/settings');
                    }
                }

                $data = array(
                    'first_name' => $dataPost['first_name'],
                    'last_name' => $dataPost['last_name'],
                    'profile' => $file_name
                );

                if ($this->ion_auth->update($this->logged_in_id, $data)) {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());
                } else {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());
                }

                redirect('squad/settings');
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
            $this->data['body'] = 'squad/settings';
            $this->load->view('layouts/main_new', $this->data);
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('message', $this->ion_auth->messages());
                redirect('squad/settings', 'refresh');
            } else {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
                redirect('squad/settings', 'refresh');
            }
        }
    }

    /**
     * Learning and Reflection
     */
    public function learning() {
        $this->data['week'] = $this->backend->getcurrentweek();
        $this->data['groups'] = $this->backend->get_table('groups');
        //$this->data['newsfeed'] = $this->backend->messages(array('week' => $week->week, 'month' => $week->month, 'year' => $week->year), $this->logged_in_id);

        $this->data['title'] = 'Learning and Reflection';
        $this->data['body'] = 'squad/learning';
        $this->load->view('layouts/main_new', $this->data);
    }

    /**
     * Squad Session
     */
    public function session() {
        $this->data['session'] = $this->backend->get_table_row('sessions', array('user_id' => $this->logged_in_id, 'end_time' => NULL));

        $this->data['title'] = 'Squad Session';
        $this->data['body'] = 'squad/sessions';
        $this->load->view('layouts/main_new', $this->data);
    }

    public function start_session() {
        $session = $this->backend->get_table_row('sessions', array('user_id' => $this->logged_in_id, 'end_time' => NULL));
        if ($session) {
            echo json_encode(array('status' => 2));
            return;
        }

        $insertId = $this->backend->insert_data('sessions', array('user_id' => $this->logged_in_id, 'start_time' => date('Y-m-d H:i:s')));
        if ($insertId) {
            echo json_encode(array('status' => 0, 'message' => $insertId));
            return;
        }

        echo json_encode(array('status' => 1, 'message' => 'Error.'));
    }

    public function stop_session() {
        $session = $this->backend->get_table_row('sessions', array('user_id' => $this->logged_in_id, 'end_time' => NULL));
        if ($session) {
            $status = $this->backend->update_data('sessions', array('user_id' => $this->logged_in_id), array('end_time' => date('Y-m-d H:i:s')));
            if ($status) {
                echo json_encode(array('status' => 0));
                return;
            }
        }

        echo json_encode(array('status' => 1, 'message' => 'Error.'));
    }

    /**
     * Pause Account
     */
    public function pause_account() {
        $week = $this->backend->getcurrentweek();

        $data = array(
            'user_id' => $this->logged_in_id,
            'week' => $week->week,
            'month' => $week->month,
            'year' => $week->year,
            'created' => date('Y-m-d H:i:s')
        );

        $insertedID = $this->backend->insert_data('pause_account', $data);
        if ($insertedID) {
            $this->session->set_flashdata('message', 'Your account has been paused.');
            redirect('squad/index', 'refresh');
        }

        $this->session->set_flashdata('message', 'Something wrong! Please try again.');
        redirect('squad/index', 'refresh');
    }

    /**
     * Un-Pause Account
     */
    public function unpause_account() {
        $week = $this->backend->getcurrentweek();

        $data = array(
            'status' => 1,
            'unpaused' => $this->logged_in_id,
            'created' => date('Y-m-d H:i:s')
        );

        $condition = array(
            'user_id' => $this->logged_in_id,
            'week' => $week->week,
            'month' => $week->month,
            'year' => $week->year
        );

        $status = $this->backend->update_data('pause_account', $condition, $data);
        if ($status) {
            $this->session->set_flashdata('message', 'Your account has been unpaused.');
            redirect('squad/index', 'refresh');
        }

        $this->session->set_flashdata('message', 'Something wrong! Please try again.');
        redirect('squad/index', 'refresh');
    }

    /**
     * Old Source Code
     */

    /**
     * Dashboard
     */
    public function index_old() {
        $user = $this->ion_auth->user()->row();
        $currentWeek = $this->backend->getcurrentweek();

        if (isset($_POST['btnWeek'])) {
            $this->form_validation->set_rules('weekgoal', 'weekgoal', 'trim|required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $dataPost = array(
                    'weekgoal' => $this->input->post('weekgoal'),
                    'user_id' => $user->id,
                    'week' => $currentWeek->week,
                    'month' => $currentWeek->month,
                    'year' => $currentWeek->year,
                    'created' => date('Y-m-d H:i:s')
                );

                $this->backend->insert_data('weekgoal', $dataPost);
                redirect('squad/index', 'refresh');
            }
        }

        if (isset($_POST['btnMonth'])) {
            $this->form_validation->set_rules('monthgoal', 'monthgoal', 'trim|required|numeric');

            if ($this->form_validation->run() === TRUE) {
                $dataPost = array(
                    'monthgoal' => $this->input->post('monthgoal'),
                    'user_id' => $user->id,
                    'month' => $currentWeek->month,
                    'year' => $currentWeek->year,
                    'created' => date('Y-m-d H:i:s')
                );

                $this->backend->insert_data('monthgoal', $dataPost);
                redirect('squad/index', 'refresh');
            }
        }

        $this->data['squadGoal'] = $this->backend->squadGoal(array('u.squad_group' => $user->squad_group, 'w.is_squad' => 0, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year));
        $this->data['achieving'] = 0;
        $this->data['first'] = $this->backend->squadRank(array('w.is_squad' => 0, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year), array('column' => 'rating', 'order' => 'desc'));
        $this->data['last'] = $this->backend->squadRank(array('w.is_squad' => 0, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year), array('column' => 'rating', 'order' => 'asc'));

        $this->data['topMember'] = $this->backend->user_rank(array('w.is_squad' => 0, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year), array('column' => 'rating', 'order' => 'desc'));
        $this->data['bottomMember'] = $this->backend->user_rank(array('w.is_squad' => 0, 'w.week' => $currentWeek->week, 'w.month' => $currentWeek->month, 'w.year' => $currentWeek->year), array('column' => 'rating', 'order' => 'asc'));

        $this->data['type'] = $this->backend->get_table('type');
        $this->data['currentWeek'] = $currentWeek;
        $this->data['week_goal'] = $this->backend->get_table_row('weekgoal', array('user_id' => $user->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
        $this->data['month_goal'] = $this->backend->get_table_row('monthgoal', array('user_id' => $user->id, 'month' => $currentWeek->month, 'year' => $currentWeek->year));
        $isPaused = $this->backend->get_table_row('pause_account', array('user_id' => $user->id, 'week' => $currentWeek->week, 'month' => $currentWeek->month, 'year' => $currentWeek->year, 'status' => 0));
        if ($isPaused) {
            $this->data['isPaused'] = $isPaused;
        }

        $condition = array('u.squad_group' => $user->squad_group, 'u.active' => 1);
        $this->data['members'] = get_activeusers_heper($condition, $currentWeek->week, $currentWeek->month, $currentWeek->year);

        $this->data['squad_group'] = get_squadgroup_helper(array('squad_group' => $user->squad_group));
        $this->data['title'] = 'Dashboard';
        $this->data['body'] = 'squad/dashboard';
        $this->load->view('layouts/main_new', $this->data);
    }

    /**
     * Perception Graph
     */
    public function perception_graph() {
        $past_weeks = 7;
        $relative_time = time();
        $weeks = array();

        for ($week_count = 0; $week_count < $past_weeks; $week_count++) {
            $monday = strtotime("last Monday", $relative_time);
            $sunday = strtotime("Sunday", $monday);

            $getWeek = $this->backend->getWeek(array('from_date' => date("Y-m-d", $monday), 'to_date' => date("Y-m-d", $sunday)));
            if ($getWeek) {
                //$squadAverage = $this->backend->achievingRating(array('receiver_id' => $this->logged_in_id, 'is_squad' => 0, 'week' => $getWeek->week, 'month' => $getWeek->month, 'year' => $getWeek->year));
                //$qaAverage = $this->backend->achievingRating(array('receiver_id' => $this->logged_in_id, 'is_squad' => 1, 'week' => $getWeek->week, 'month' => $getWeek->month, 'year' => $getWeek->year));

                if ($qaAverage > 0 && $squadAverage > 0) {
                    $weeks[$week_count]['y'] = floatval(number_format((float) ($qaAverage - $squadAverage), 2, '.', ''));
                } else {
                    $weeks[$week_count]['y'] = 0;
                }

                $weeks[$week_count]['label'] = 'Week ' . $getWeek->week . '(' . date("F", mktime(0, 0, 0, $getWeek->month, 1, 2011)) . ')';
            }
            $relative_time = $monday;
        }

        $this->data['currentWeek'] = $weeks;
        $this->data['title'] = 'Perception Graph';
        $this->data['body'] = 'squad/perception_graph';
        $this->load->view('layouts/main', $this->data);
    }

    /**
     * View Previous Assessment
     */
    public function viewPrevious() {
        $currentWeek = $this->backend->getcurrentweek();

        $this->data['currentWeek'] = $currentWeek;
        $this->data['list'] = $this->backend->previousAssessment('');

        $this->data['title'] = 'Previous Assessment';
        $this->data['body'] = 'squad/viewPrevious';
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
                $this->data['user'] = $user;

                $this->data['title'] = 'View Assessment';
                $this->data['body'] = 'squad/viewAssessment';
                $this->load->view('layouts/main', $this->data);
            } else {
                show_404();
            }
        }
    }

}
