<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ajaxrequest extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->model('backend_model', 'backend');
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
