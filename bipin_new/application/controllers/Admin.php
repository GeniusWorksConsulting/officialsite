<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        $this->load->library('javascript');
        $this->load->model('Admin_model');
        $this->load->model('Page_model');
    }

    public function index() {
        if ($this->session->userdata('user_id')) {
            redirect(base_url() . 'admin/dashboard');
        } else {
            $this->load->helper('url');
            $this->load->view('admin/login');
        }
    }

    public function checkloginadmin() {
        if (!$this->session->role == "1") {
            redirect(base_url() . 'login');
        }
    }

    public function login() {
        $result = $this->Admin_model->login();

        if ($result == 1) {
            if ($this->session->role == 2) {
                redirect(base_url() . 'dashboard');
            } elseif ($this->session->role == 1) {
                redirect(base_url() . 'admin/dashboard');
            } elseif ($this->session->role == 49) {
                redirect(base_url() . 'qamember/dashboard');
            }
        } else {
            redirect(base_url() . 'admin');
        }
    }

    public function dashboard() {
        $this->checkloginadmin();
        if ($_SESSION['role'] == 49) {
            redirect(base_url() . 'qamember/dashboard');
        }
        //print_r($_SESSION);
        //exit;
        $this->Page_model->weeklyentry();

        $currentweek = $this->Admin_model->getcurrentweek();

        if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
            $currentweek->week = $_GET['week'];
            $currentweek->month = $_GET['month'];
            $currentweek->year = $_GET['year'];
        }

        $permanent_paused_account = $this->Admin_model->get_permanent_paused_account($currentweek->week, $currentweek->month, $currentweek->year);

        $paused_account = $this->Admin_model->get_current_paused_account($currentweek->week, $currentweek->month, $currentweek->year);
        //print_r($paused_account); exit;
        $groupmember1 = $this->Admin_model->getcurrentgroupmember("1", $paused_account, $permanent_paused_account);
        $groupmember2 = $this->Admin_model->getcurrentgroupmember("2", $paused_account, $permanent_paused_account);

        $current_im_acieving = 0;
        $current_im_acieving_count = 0;

        for ($i = 0; $i < sizeof($groupmember1); $i++) {
            $sender_id = $groupmember1[$i]['id'];

            $getusercompleted = $this->Admin_model->getusercompleted($currentweek->week, $currentweek->month, $currentweek->year, "1", $sender_id);
            $groupmember1[$i]['mycompleted'] = $getusercompleted['complete_member'];
            $groupmember1[$i]['totalmember'] = sizeof($groupmember1);
            for ($j = 0; $j < sizeof($groupmember1); $j++) {
                $receiver_id = $groupmember1[$j]['id'];
                //echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";

                $groupmember1[$i][$j]['first_name'] = $groupmember1[$j]['first_name'];

                $groupmember1[$i][$j]['text_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "text");

                $groupmember1[$i][$j]['text_assessed_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $receiver_id, $sender_id, "text");

                $groupmember1[$i][$j]['voice_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "voice");

                $groupmember1[$i][$j]['voice_assessed_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $receiver_id, $sender_id, "voice");
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                //echo "</br>";
                $gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "text");

                $getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "voice");

                $getqamember = $this->Admin_model->getqamember($receiver_id);
                if (!empty($getqamember)) {
                    if (isset($getqamember['assessed_percentage'])) {
                        $groupmember1[$i][$j]['qarating'] = $getqamember['assessed_percentage'];
                    } else {
                        $groupmember1[$i][$j]['qarating'] = "";
                    }
                } else {
                    $groupmember1[$i][$j]['qarating'] = "";
                }


                $mainpercentage = 0;
                $count = 0;
                if ($gettextquestionpercentage != "") {
                    $mainpercentage += $gettextquestionpercentage;
                    $count++;
                }
                if ($getvoicequestionpercentage != "") {
                    $mainpercentage += $getvoicequestionpercentage;
                    $count++;
                }
                $groupmember1[$i][$j]['completed'] = '';
                if ($gettextquestionpercentage != "" && $getvoicequestionpercentage != "") {
                    $groupmember1[$i][$j]['completed'] = 'completed';
                }
                if ($count != 0) {
                    $groupmember1[$i][$j]['assessed_percentage'] = ceil($mainpercentage / 2);
                    $current_im_acieving += ceil($mainpercentage / $count);
                    $current_im_acieving_count++;
                } else {
                    $groupmember1[$i][$j]['assessed_percentage'] = "0";
                }
            }
        }

        $current_im_acieving = 0;
        $current_im_acieving_count = 0;
        for ($i = 0; $i < sizeof($groupmember2); $i++) {
            $sender_id = $groupmember2[$i]['id'];

            $getusercompleted = $this->Admin_model->getusercompleted($currentweek->week, $currentweek->month, $currentweek->year, "2", $sender_id);
            $groupmember2[$i]['mycompleted'] = $getusercompleted['complete_member'];
            $groupmember2[$i]['totalmember'] = sizeof($groupmember2);

            for ($j = 0; $j < sizeof($groupmember2); $j++) {
                $receiver_id = $groupmember2[$j]['id'];
                //echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";

                $groupmember2[$i][$j]['first_name'] = $groupmember2[$j]['first_name'];

                $groupmember2[$i][$j]['text_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "text");

                $groupmember2[$i][$j]['text_assessed_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $receiver_id, $sender_id, "text");

                $groupmember2[$i][$j]['voice_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "voice");

                $groupmember2[$i][$j]['voice_assessed_rating'] = $this->Admin_model->getrating($currentweek->week, $currentweek->month, $currentweek->year, $receiver_id, $sender_id, "voice");
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                //echo "</br>";
                $gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "text");

                $getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "voice");

                $getqamember = $this->Admin_model->getqamember($receiver_id);
                if (!empty($getqamember)) {
                    if (isset($getqamember['assessed_percentage'])) {
                        $groupmember2[$i][$j]['qarating'] = $getqamember['assessed_percentage'];
                    } else {
                        $groupmember2[$i][$j]['qarating'] = "";
                    }
                } else {
                    $groupmember2[$i][$j]['qarating'] = "";
                }

                $mainpercentage = 0;
                $count = 0;
                if ($gettextquestionpercentage != "") {
                    $mainpercentage += $gettextquestionpercentage;
                    $count++;
                }
                if ($getvoicequestionpercentage != "") {
                    $mainpercentage += $getvoicequestionpercentage;
                    $count++;
                }

                $groupmember2[$i][$j]['completed'] = '';
                if ($gettextquestionpercentage != "" && $getvoicequestionpercentage != "") {
                    $groupmember2[$i][$j]['completed'] = 'completed';
                }
                if ($count != 0) {
                    $groupmember2[$i][$j]['assessed_percentage'] = ceil($mainpercentage / 2);
                    $current_im_acieving += ceil($mainpercentage / $count);
                    $current_im_acieving_count++;
                } else {
                    $groupmember2[$i][$j]['assessed_percentage'] = "0";
                }
            }
        }
        //echo '<pre>'; print_r($groupmember2);

        $mytribe = $this->Admin_model->mytribe("1", $paused_account, $permanent_paused_account);
        $othertribe = $this->Admin_model->mytribe("2", $paused_account, $permanent_paused_account);

        // $mytribeforqamember = $this->Admin_model->mytribeforqamember("1");
        // $othertribeforqamember = $this->Admin_model->mytribeforqamember("2");
        // if($mytribeforqamember['percentage']!=0){
        // $mytribe['percentage'] = ceil(($mytribeforqamember['percentage'] + $mytribe['percentage']) /2);
        // }
        // if($othertribeforqamember['percentage']!=0){
        // $othertribe['percentage'] = ceil(($othertribeforqamember['percentage'] + $othertribe['percentage']) /2);
        // }

        $this->load->view('admin_include/admin_header', array('active_page' => 'dashboard', 'header_title' => 'Dashboard'));

        $this->load->view('admin_include/admin_sidebar');
        $this->load->view('admin/dashboard', array('groupmember2' => $groupmember2, 'groupmember1' => $groupmember1, 'mytribe' => $mytribe, 'othertribe' => $othertribe));
        $this->load->view('admin_include/admin_footer');
    }

    public function viewcategory() {
        $this->checkloginadmin();
        $category = $this->Admin_model->getallcategory();

        $this->load->view('admin_include/admin_header', array('active_page' => 'category', 'header_title' => 'Category'));
        $this->load->view('admin_include/admin_sidebar');
        $this->load->view('admin/viewcategory', array('category' => $category));
        $this->load->view('admin_include/admin_footer');
    }

    public function addcategory() {
        $this->checkloginadmin();
        $category = array();
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $category = $this->Admin_model->getcategoryfromid($_GET['id']);
            if (!empty($_POST)) {
                $this->Admin_model->update("category", array("name" => $_POST['name'], "type" => $_POST['type'], "weighting" => $_POST['weighting']), array('id' => $_GET['id']));
                $this->session->set_flashdata('message_success', 'Category Updated Successfully.!', 5);
                redirect("admin/viewcategory");
            }
        }
        if (!empty($_POST)) {
            $_POST['createdat'] = date("Y-m-d h:i:s");
            $this->Admin_model->insert("category", $_POST);

            $this->session->set_flashdata('message_success', 'Category add Successfully.!', 5);
            redirect("admin/addcategory");
        }
        $this->load->view('admin_include/admin_header', array('active_page' => 'category', 'header_title' => 'Category'));

        $this->load->view('admin_include/admin_sidebar');

        $this->load->view('admin/addcategory', array('category' => $category));
        $this->load->view('admin_include/admin_footer');
    }

    public function deletecategory() {
        $this->checkloginadmin();
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $this->Admin_model->update("category", array('isdeleted' => '1'), array('id' => $_GET['id']));
            $this->session->set_flashdata('message_success', 'Category Deleted Successfully.!', 5);
            redirect("admin/viewcategory");
        }
    }

    public function viewquestions() {
        $this->checkloginadmin();
        $getallquestions = $this->Admin_model->getallquestions();

        $this->load->view('admin_include/admin_header', array('active_page' => 'question', 'header_title' => 'question'));
        $this->load->view('admin_include/admin_sidebar');
        $this->load->view('admin/viewquestions', array('getallquestions' => $getallquestions));
        $this->load->view('admin_include/admin_footer');
    }

    public function viewnewquestions() {
        $this->checkloginadmin();

        print_r($_GET);
        $type = "";
        if (isset($_GET['type']) && ($_GET['type'] != "")) {
            $type = $_GET['type'];
        } else {
            $type = "text";
        }
        $getallcatageory = $this->Admin_model->getnewallcategory($type);

        $this->load->view('admin_include/admin_header', array('active_page' => 'addnewquestions', 'header_title' => 'addnewquestions'));
        $this->load->view('admin_include/admin_sidebar');
        $this->load->view('admin/viewnewquestions', array('getallcatageory' => $getallcatageory));
        $this->load->view('admin_include/admin_footer');
    }

    public function addquestions() {
        $this->checkloginadmin();
        $category = $this->Admin_model->getallcategory();
        $question = array();
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $question = $this->Admin_model->getquestionfromid($_GET['id']);
            if (!empty($_POST)) {

                $this->Admin_model->update("questions", $_POST, array('id' => $_GET['id']));
                $this->session->set_flashdata('message_success', 'Question Updated Successfully.!', 5);
                redirect("admin/viewquestions");
            }
        }
        if (!empty($_POST)) {
            $_POST['createdat'] = date("Y-m-d h:i:s");
            $this->Admin_model->insert("question", $_POST);

            $this->session->set_flashdata('message_success', 'Question add Successfully.!', 5);
            redirect("admin/addquestions");
        }
        $this->load->view('admin_include/admin_header', array('active_page' => 'question', 'header_title' => 'question'));

        $this->load->view('admin_include/admin_sidebar');

        $this->load->view('admin/addquestions', array('category' => $category, 'question' => $question));
        $this->load->view('admin_include/admin_footer');
    }

    public function addnewquestions() {
        $this->checkloginadmin();
        $category = $this->Admin_model->getallcategory();
        $question = array();
        $question_answer = array();
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $question = $this->Admin_model->getquestionfromid($_GET['id']);
            $question_answer = $this->Admin_model->GetAllNewAnswer($_GET['id']);
            if (!empty($_POST)) {

                //$this->Admin_model->update("questions", $_POST, array('id' => $_GET['id']));
                $questionarray = array();
                $questionarray['category_id'] = $_POST['category_id'];
                $questionarray['description'] = $_POST['description'];
                $questionarray['isparent'] = $_POST['isparent'];
                $questionarray['parent_id'] = $_POST['question'];
                $questionarray['type'] = $_POST['type'];
                $questionarray['weighting'] = $_POST['qweighting'];
                $questionarray['noofanswer'] = $_POST['noofanswer'];
                $questionarray['is_deleted'] = 0;
                $questionarray['createdat'] = date("Y-m-d h:i:s");

                $insertId = $this->Admin_model->update("questions", $questionarray, array('que_id' => $_GET['id']));

                //update parent
                if ($_POST['isparent'] == "child") {
                    $updateparent['has_child'] = 1;
                    $updateparent['child_id'] = $insertId;
                    $this->Admin_model->update("questions", $updateparent, array('que_id' => $_POST['question']));
                }
                $this->Admin_model->delete_data('answer', array('que_id' => $_GET['id']));
                if (!empty($_POST['answers'])) {

                    for ($j = 0; $j < sizeof($_POST['answers']); $j++) {
                        $ansarray['que_id'] = $insertId;
                        $ansarray['answers'] = $_POST['answers'][$j];
                        $ansarray['rating'] = $_POST['rating'][$j];
                        $ansarray['weighting'] = $_POST['weighting'][$j];
                        $ansarray['is_deleted'] = 0;
                        $ansarray['createdat'] = date("Y-m-d h:i:s");
                        $this->Admin_model->insert("answer", $ansarray);
                    }
                }
                $this->session->set_flashdata('message_success', 'Question Updated Successfully.!', 5);
                redirect("admin/viewnewquestions");
            }
        }
        $newquestion = $this->Admin_model->GetAllNewQuestions();
//                print_r($newquestion);
//                die();
        if (!empty($_POST) && ($_POST['btn_submit'] == "Submit")) {
            $questionarray = array();
            $questionarray['category_id'] = $_POST['category_id'];
            $questionarray['description'] = $_POST['description'];
            $questionarray['isparent'] = $_POST['isparent'];
            $questionarray['parent_id'] = $_POST['question'];
            $questionarray['type'] = $_POST['type'];
            $questionarray['weighting'] = $_POST['qweighting'];
            $questionarray['noofanswer'] = $_POST['noofanswer'];
            $questionarray['is_deleted'] = 0;
            $questionarray['createdat'] = date("Y-m-d h:i:s");

            $insertId = $this->Admin_model->insert("questions", $questionarray);

            //update parent
            if ($_POST['isparent'] == "child") {
                $updateparent['has_child'] = 1;
                $updateparent['child_id'] = $insertId;
                $this->Admin_model->update("questions", $updateparent, array('que_id' => $_POST['question']));
            }
            if (!empty($_POST['answers'])) {

                for ($j = 0; $j < sizeof($_POST['answers']); $j++) {
                    $ansarray['que_id'] = $insertId;
                    $ansarray['answers'] = $_POST['answers'][$j];
                    $ansarray['rating'] = $_POST['rating'][$j];
                    $ansarray['weighting'] = $_POST['weighting'][$j];
                    $ansarray['is_deleted'] = 0;
                    $ansarray['createdat'] = date("Y-m-d h:i:s");
                    $this->Admin_model->insert("answer", $ansarray);
                }
            }


            $this->session->set_flashdata('message_success', 'New Question add Successfully.!', 5);
            redirect("admin/addnewquestions");
        }
        $this->load->view('admin_include/admin_header', array('active_page' => 'addnewquestions', 'header_title' => 'addnewquestions'));

        $this->load->view('admin_include/admin_sidebar');

        $this->load->view('admin/addnewquestions', array('category' => $category, 'question' => $question, 'question_answer' => $question_answer, 'newquestion' => $newquestion));
        $this->load->view('admin_include/admin_footer');
    }

    public function deletequestion() {
        $this->checkloginadmin();
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $this->Admin_model->update("question", array('isdeleted' => '1'), array('id' => $_GET['id']));
            $this->session->set_flashdata('message_success', 'Question Deleted Successfully.!', 5);
            redirect("admin/viewquestions");
        }
    }

    public function adduser() {
        $this->checkloginadmin();
        $category = $this->Admin_model->getallcategory();
        $user = array();
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $user = $this->Admin_model->getuserfromid($_GET['id']);
            if (!empty($_POST)) {

                $this->Admin_model->update("users", $_POST, array('id' => $_GET['id']));
                $this->session->set_flashdata('message_success', 'User Updated Successfully.!', 5);
                redirect("admin/viewuser");
            }
        }
        if (!empty($_POST)) {
            $_POST['role'] = 2;
            $_POST['createdat'] = date("Y-m-d h:i:s");
            $this->Admin_model->insert("users", $_POST);

            $this->session->set_flashdata('message_success', 'User add Successfully.!', 5);
            redirect("admin/adduser");
        }
        $this->load->view('admin_include/admin_header', array('active_page' => 'user', 'header_title' => 'Users'));

        $this->load->view('admin_include/admin_sidebar');

        $this->load->view('admin/adduser', array('category' => $category, 'user' => $user));
        $this->load->view('admin_include/admin_footer');
    }

    public function viewuser() {
        $this->checkloginadmin();
        $getallusers = $this->Admin_model->getallusers();

        $this->load->view('admin_include/admin_header', array('active_page' => 'user', 'header_title' => 'Users'));
        $this->load->view('admin_include/admin_sidebar');
        $this->load->view('admin/viewuser', array('getallusers' => $getallusers));
        $this->load->view('admin_include/admin_footer');
    }

    public function viewqamember() {
        $this->checkloginadmin();
        $getallqamember = $this->Admin_model->getallqamember();

        $this->load->view('admin_include/admin_header', array('active_page' => 'qamember', 'header_title' => 'View QA Member'));
        $this->load->view('admin_include/admin_sidebar');
        $this->load->view('admin/viewqamember', array('getallqamember' => $getallqamember));
        $this->load->view('admin_include/admin_footer');
    }

    public function deleteuser() {
        $this->checkloginadmin();
        if (isset($_GET['id']) && $_GET['id'] != "") {
            $this->Admin_model->update("users", array('isdeleted' => '1', 'delete_date' => date("Y-m-d H:i:s")), array('id' => $_GET['id']));
            $this->session->set_flashdata('message_success', 'User Deleted Successfully.!', 5);
            redirect("admin/viewuser");
        }
    }

    public function paused_account() {
        $this->checkloginadmin();
        $data = $this->Admin_model->get_paused_account();
        $this->load->view('admin_include/admin_header', array('active_page' => 'paused_account', 'header_title' => 'View Paused Account'));
        $this->load->view('admin_include/admin_sidebar');
        $this->load->view('admin/paused_account', array('data' => $data));
        $this->load->view('admin_include/admin_footer');
    }

    public function resume_account($id = null) {
        if ($id == "") {
            redirect("admin/paused_account");
        }
        $this->Admin_model->resume_account($id);
        $this->session->set_flashdata('message_success', 'Account Resume Successfully.!', 5);
        redirect("admin/paused_account");
    }

    public function view_previous() {
        $this->checkloginadmin();
        $data = $this->Admin_model->get_all_week();
        $this->load->view('admin_include/admin_header', array('header_title' => 'View Previous'));
        $this->load->view('admin_include/admin_sidebar', array('active_page' => 'viewprevious'));
        $this->load->view('admin/viewprevious', array('data' => $data));
        $this->load->view('admin_include/admin_footer');
    }

    public function addpage() {
        if (isset($_POST['page_title'])) {
            $result = $this->Admin_model->addpage($_POST);
        }

        $status = $this->Admin_model->get_all_status();
        $language = $this->Admin_model->get_all_language();
        $this->load->view('admin/add_page', array('status' => $status, 'language' => $language));
    }

    public function update($id) {
        if (isset($_POST['page_title'])) {
            $result = $this->Admin_model->updatepage($_POST, $id);
        }
        $pagedetails = $this->Admin_model->getpage_by_id($id);
        $status = $this->Admin_model->get_all_status();
        $language = $this->Admin_model->get_all_language();
        $this->load->view('admin/add_page', array('status' => $status, 'language' => $language, 'pagedetails' => $pagedetails));
    }

    public function allpage() {
        $pages = $this->Admin_model->get_all_pages();
        $this->load->view('admin/all_page', array('pages' => $pages));
    }

    public function logout() {

        $this->session->sess_destroy();

        redirect(base_url());
    }

    public function users() {
        $activeuser = $this->Admin_model->get_user_by_where("status", "1");
        $pendinguser = $this->Admin_model->get_user_by_where("status", "0");

        $this->load->view('admin_include/admin_header');

        $this->load->view('admin_include/admin_sidebar');

        $this->load->view('admin/user/all_user', array('activeuser' => $activeuser, 'pendinguser' => $pendinguser));
    }

    public function sendcoin($id) {
        if (isset($_POST['coin'])) {

            if ($_POST['coin'] == "" || $_POST['coin'] <= 0) {

                $this->session->set_flashdata('message', 'Enter Valid Coins', 5);

                redirect("admin/sendcoin/" . $id);
            } else {

                $result = $this->Admin_model->sendcoin($id, $_POST['coin']);

                $this->session->set_flashdata('message_success', 'Coin Send Successfully!', 5);

                redirect("admin/sendcoin/" . $id);
            }
        }
        $userdetails = $this->Admin_model->get_user_by_where("user_id", $id);

        $this->load->view('admin_include/admin_header');

        $this->load->view('admin_include/admin_sidebar');

        $this->load->view('admin/user/sendcoin', array('userdetails' => $userdetails));
    }

    public function addnewratting() {
        print_r($_POST);
        die();
    }

}
