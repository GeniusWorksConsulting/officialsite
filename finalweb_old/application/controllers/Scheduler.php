<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Scheduler extends CI_Controller {

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
        $group = 'scheduler';

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        }

        if (!$this->ion_auth->in_group($group)) {
            $this->ion_auth->logout();
            $this->session->set_flashdata('message', 'You must be an Scheduler Member to view the user groups page.');
            redirect('auth/login');
        }

        $user = $this->ion_auth->user()->row();
        $this->logged_in_id = $user->id;
        $this->profile = $user->profile;
        $this->logged_in_name = $user->first_name . ' ' . $user->last_name;
        $this->groups = $this->ion_auth->get_users_groups($user->id)->row()->description;
    }

    /**
     * Scheduler Dashboard
     */
    public function index() {
        $week = $this->backend->getcurrentweek();

        $config['base_url'] = site_url('scheduler/index');
        $config['total_rows'] = $this->backend->scheduled(array('s.week' => $week->week, 's.month' => $week->month, 's.year' => $week->year));
        $config['per_page'] = '20';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data["count"] = $this->uri->segment(3) + 1;
        $data["pagination"] = $this->pagination->create_links();

        //list the users
        $data["list"] = $this->backend->scheduled(array('s.week' => $week->week, 's.month' => $week->month, 's.year' => $week->year), $config['per_page'], $page);

        $data['title'] = 'Dashboard';
        $data['body'] = 'scheduler/dashboard';
        $this->load->view('layouts/main', $data);
    }

    /*
     * add schedule for squad assess
     */

    public function addSchedule() {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('squad_group', 'squad_group', 'required', array('required' => 'Please select squad group.'));
            $this->form_validation->set_rules('user_id', 'user_id', 'required');
            $this->form_validation->set_rules('date', 'date', 'required|trim');
            $this->form_validation->set_rules('from_time', 'from_time', 'required|trim', array('required' => 'Select schedule start time.'));
            $this->form_validation->set_rules('to_time', 'to_time', 'required|trim', array('required' => 'Select schedule end time.'));

            if ($this->form_validation->run() === TRUE) {
                $dataPost = $this->input->post();
                $week = $this->backend->getcurrentweek();

                $data = array(
                    'user_id' => $dataPost['user_id'],
                    'sender_id' => $this->logged_in_id,
                    'date' => date("Y-m-d", strtotime($dataPost['date'])),
                    'from_time' => $dataPost['from_time'],
                    'to_time' => $dataPost['to_time'],
                    'week' => $week->week,
                    'month' => $week->month,
                    'year' => $week->year
                );

                $this->backend->insert_data('scheduled', $data);
                $this->session->set_flashdata('message', 'Success.');
                redirect('scheduler/addSchedule');
            }
        }

        $data['list'] = $this->backend->get_table('squad_group');
        $data['title'] = 'Add Schedule';
        $data['body'] = 'scheduler/addSchedule';
        $this->load->view('layouts/main', $data);
    }

    /**
     * member list from squad group
     */
    public function getmembers() {
        $members = $this->backend->get_table('users', array('squad_group' => $this->input->post('squad_group')));
        echo json_encode($members);
    }

    /**
     * Reflection Comment
     */
    public function comment() {
        if (isset($_POST['submit'])) {
            $this->form_validation->set_rules('sub_title', 'sub_title', 'trim|required');
            $this->form_validation->set_rules('message', 'message', 'trim|required');

            if ($this->form_validation->run() === TRUE) {
                $dataPost = $this->input->post();
                $week = $this->backend->getcurrentweek();

                $data = array(
                    'sender_id' => $this->logged_in_id,
                    'message' => $dataPost['message'],
                    'title' => $dataPost['title'],
                    'sub_title' => $dataPost['sub_title'],
                    'group_id' => $this->ion_auth->get_users_groups()->row()->id,
                    'week' => $week->week,
                    'month' => $week->month,
                    'year' => $week->year,
                    'created' => date('Y-m-d H:i:s')
                );

                $this->backend->insert_data('messages', $data);
                $this->session->set_flashdata('message', 'Reflection comment send successfully.');
                redirect('scheduler/comment');
            }
        }

        $data['title'] = 'Reflection Comment';
        $data['body'] = 'scheduler/comment';
        $this->load->view('layouts/main', $data);
    }

}
