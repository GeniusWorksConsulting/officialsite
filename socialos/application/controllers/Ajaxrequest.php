<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajaxrequest extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('backend_model', 'backend');
    }

    // Update Squad (Drap and Drop)
    public function update_squad() {
        $this->form_validation->set_rules('user_id', 'user_id', 'required|numeric');
        $this->form_validation->set_rules('squad_id', 'squad_id', 'required|numeric');
        if($this->form_validation->run() == FALSE) {
            
        }
        if ($this->form_validation->run() === TRUE) {
            $Post = $this->input->post();

            if (isset($Post['attrid']) && $Post['attrid'] != NULL) {
                $condition = array('id' => $Post['attrid']);

                $dataPost = array(
                    'squad_id' => $Post['squad_id']
                );

                $status = $this->backend->update_data('squad_users', $condition, $dataPost);
            } else {
                $dataPost = array(
                    'user_id' => $Post['user_id'],
                    'squad_id' => $Post['squad_id']
                );

                $status = $this->backend->insert_data('squad_users', $dataPost);
            }

            if ($status) {
                echo json_encode(array('status' => TRUE));
            } else {
                echo json_encode(array('status' => FALSE, 'message' => 'Something Wrong! Please try again later.'));
            }

            return;
        }

        echo json_encode(array('status' => FALSE, 'message' => 'Something Wrong! Please try again later.'));
    }

    // Update Squad Member from Squad Group (Drag and Drop)
    public function delete_squaduser() {
        $this->form_validation->set_rules('id', 'id', 'required|numeric');

        if ($this->form_validation->run() === TRUE) {
            $dataPost = array(
                'status' => 1
            );

            $status = $this->backend->update_data('squad_users', array('id' => $this->input->post('id')), $dataPost);
            if ($status) {
                echo json_encode(array('status' => TRUE));
            } else {
                echo json_encode(array('status' => FALSE, 'message' => 'Something Wrong! Please try again later.'));
            }

            return;
        }

        echo json_encode(array('status' => FALSE, 'message' => 'Something Wrong! Please try again later.'));
        return;
    }

    // Get Squad from admin id
    public function getsquads() {
        $this->data['list'] = $this->backend->get_squad_groups(array('status' => 0, 'user_id' => $this->input->post('admin_id')));
        if (count($this->data['list']) > 0) {
            echo json_encode(array('status' => TRUE, 'data' => $this->data['list']));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Please add squad group.'));
        }

        return;
    }

    // Get squad users
    public function getsquadusers() {
        $this->data['list'] = $this->backend->get_squad_users(array('s.status' => 0, 's.squad_id' => $this->input->post('squad_id')));

        if (count($this->data['list']) > 0) {
            echo json_encode(array('status' => TRUE, 'data' => $this->data['list']));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Please add member into squad.'));
        }

        return;
    }

    // Get Levels
    public function getlevels() {
        $this->data['list'] = $this->backend->get_table('levels', array('status' => 0, 'user_id' => $this->input->post('user_id')));

        if (count($this->data['list']) > 0) {
            echo json_encode(array('status' => TRUE, 'data' => $this->data['list']));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Please first of all create level.'));
        }

        return;
    }

    // Get Sub Assessment
    public function getsub_assessment() {
        $this->data['list'] = $this->backend->get_table('sub_assessment', array('status' => 0, 'user_id' => $this->input->post('user_id')));

        if (count($this->data['list']) > 0) {
            echo json_encode(array('status' => TRUE, 'data' => $this->data['list']));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Please first of all add sub assessment.'));
        }

        return;
    }

    // Get Category
    public function get_category() {
        $this->data['list'] = $this->backend->get_table('category', array('status' => 0, 'user_id' => $this->input->post('user_id')));

        if (count($this->data['list']) > 0) {
            echo json_encode(array('status' => TRUE, 'data' => $this->data['list']));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Please first of all add sub assessment.'));
        }

        return;
    }

    // Get Question
    public function get_question() {
        $this->data['Quelist'] = $this->backend->get_table('question', array('status' => 0));
        $this->load->view('superadmin/common/question_list', $this->data);
    }

    // Get Answer
    public function get_answer() {
        $this->data['list'] = $this->backend->get_table('answer', array('status' => 0, 'question_id' => $this->input->post('question_id')));
        if (count($this->data['list']) > 0) {
            echo json_encode(array('status' => TRUE, 'data' => $this->data['list']));
        } else {
            echo json_encode(array('status' => FALSE, 'message' => 'Answer is null.'));
        }
    }

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

    public function getChild() {
        $dataPost = $this->input->post();
        $nextQuestion = $this->backend->child_question(array('q.parent_id' => $dataPost['question_id'], 'q.answer_id' => $dataPost['answer_id']));
        $this->data['response'] = $nextQuestion;
        $this->load->view('squad/get_child', $this->data);
    }

}
