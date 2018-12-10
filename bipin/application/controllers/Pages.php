<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pages extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	 
	public function __construct(){
	  parent::__construct();
		$this->load->library('javascript');
		$this->load->library('form_validation');
		$this->load->library('email');
		$this->load->library('session');
		$this->load->helper('form');
		$this->load->helper('url');
		$this->load->model('Admin_model');
		$this->load->model('Page_model');
	}
	
	public function index()
	{
		$this->load->view('front_include/front_header');
		$this->load->view('index');
		$this->load->view('front_include/front_footer');
	}
	
	public function view()
	{
		$pagedetails = $this->Admin_model->getpage_by_slug($this->uri->segment(1));
		if(empty($pagedetails)){ 
			$pagedetails = new \stdClass();
			$pagedetails->page_title="No Data Found"; 
			$pagedetails->content=""; 
		}
		$this->load->view('pages_view',array('pagedetails'=>$pagedetails)); 
	}
	
	public function register()
	{
		
		if(isset($_POST['first_name'])){
			
			$this->form_validation->set_rules('user_name', 'Username', 'required|callback_check_username_exists');

			$this->form_validation->set_rules('first_name', 'First Name', 'required');

			$this->form_validation->set_rules('last_name', 'Last Name', 'required');
			
			$this->form_validation->set_rules('password', 'Password', 'required');

			$this->form_validation->set_rules('mobile', 'Mobile', 'required|callback_check_mobilenumber_exists');
			
			$this->form_validation->set_rules('email', 'Email Address', 'required|callback_check_emailaddress_exists');

			$this->form_validation->set_rules('password_confirmation', 'Confirm Password', 'matches[password]');
			
			 if ($this->form_validation->run() === FALSE) {
				 
				$this->load->view('front_include/front_header');
				$this->load->view('register',array('_POST',$_POST));
				$this->load->view('front_include/front_footer');
			 }else{
				$ref = "";
				if(isset($_GET['ref']) && $_GET['ref']!=""){
					$ref = $_GET['ref'];
				}
				$salt = md5(mt_rand());
				 
				 $data = array(

					'first_name' => $this->input->post('first_name'),

					'last_name' => $this->input->post('last_name'),

					'user_name' => $this->input->post('user_name'),
					
					'mobile' => $this->input->post('mobile'),
					
					'email' => $this->input->post('email'),
					
					'password' => $this->input->post('password'),

					'salt' => $salt,
					
					'referred_id' => $ref,

					'createdat' => date('Y-m-d H:i:s')
				);
				
				$register = $this->Page_model->register($data);
				
				$_POST=array();
				
				if($register==1){
					$_POST['message'] = "Data Inserted Successfully!";
					$this->load->view('front_include/front_header');
					$this->load->view('register',array('post',$_POST));
					$this->load->view('front_include/front_footer');
				}else{
					
					$this->load->view('user_include/front_header');
					$this->load->view('register',array('post',$_POST));
					$this->load->view('user_include/user_footer');
				}
				
				
			 }
			
		}else{
			$_POST=array();
			$this->load->view('front_include/front_header');
			$this->load->view('register',array('post',$_POST));
			$this->load->view('front_include/front_footer');
		}
	}
	
	public function checkloginuser()
	{
		$userarray = array(1,2,49);
		if(!in_array($this->session->role,$userarray)){
			redirect(base_url() . 'login');
		}
	}
	
	public function login()
	{
		//echo 'sanjay';
		//echo $this->session->role;
		//exit;
		if($this->session->role == 2){
			redirect(base_url() . 'newdashboard');
		}elseif($this->session->role == 1){
			redirect(base_url() . 'admin/dashboard');
		}elseif($this->session->role == 49){
			redirect(base_url() . 'qamember/dashboard');
		}
		if(!empty($_POST)){
			$result = $this->Page_model->login();
	
			if ($result == 1) {
				
				if($this->session->role == 2){
					redirect(base_url() . 'newdashboard');
				}elseif($this->session->role == 1){
					redirect(base_url() . 'admin/dashboard');
				}elseif($this->session->role == 49){
					redirect(base_url() . 'qamember/dashboard');
				}

			} else {

				redirect(base_url() . 'login');

			}
		}
		$this->load->view('front_include/front_header');
		$this->load->view('login');
		$this->load->view('front_include/front_footer');
	}
	public function newdashboard()
        {
            $this->checkloginuser();
            $currentweek = $this->Admin_model->getcurrentweek(); //currentweek
            //print_r($currentweek);
             if(!empty($_POST)){
                    //print_r($_POST);
                   // exit;
			if(isset($_POST['weeklygoalbutton'])){
				if($_POST['weeklygoal']==""){
					$this->session->set_flashdata('message_eror', 'Please Enter Valid Weekly Goal!', 5);
					redirect("newdashboard");
				}else{
					
					$data=array("user_id"=>$this->session->user_id,
								"weekly_goal"=>$_POST['weeklygoal'],
								"group_name"=>$this->session->group_name,
								"week"=>$currentweek->week,
								"month"=>$currentweek->month,
								"year"=>$currentweek->year,
								"createdat"=>date('Y-m-d h:i:s'));
					//print_r($data); print_r($_POST); exit;
					$this->Admin_model->insert("weekly_goal",$data);
					$this->session->set_flashdata('message_success', 'Weekly Goal Set Successfully!', 5);
					redirect("newdashboard");
				}
			}
			
			if(isset($_POST['monthlygoalbutton'])){
				if($_POST['monthlygoal']==""){
					$this->session->set_flashdata('message_eror', 'Please Enter Valid Monthly Goal!', 5);
					redirect("newdashboard");
				}else{
					$data=array("user_id"=>$this->session->user_id,
								"monthly_goal"=>$_POST['monthlygoal'],
								"group_name"=>$this->session->group_name,
								"month"=>$_POST['month'],
								"year"=>$_POST['year'],
								"createdat"=>date('Y-m-d h:i:s'));
					//print_r($data); print_r($_POST); exit;
					$this->Admin_model->insert("monthly_goal",$data);
					$this->session->set_flashdata('message_success', 'Monthly Goal Set Successfully!', 5);
					redirect("newdashboard");
				}
			}
		}
            
            if(empty($currentweek))
		{
				$permanent_paused_account = 0;
				$current_week_paused_account = 0;
		}
		else
		{
			$permanent_paused_account = $this->Admin_model->get_permanent_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
			$current_week_paused_account = $this->Admin_model->get_current_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
		
		}
            $current_im_acieving = 0;
            $current_im_acieving_count = 0;
            $groupmember = $this->Admin_model->getcurrentgroupmember("",$current_week_paused_account,$permanent_paused_account);
			$sender_id = $this->session->user_id;
			$given_rating_array_text = $this->Admin_model->GetGivenRatingArray($sender_id,$currentweek->week,$currentweek->month,$currentweek->year,'text');
			$given_rating_array_voice = $this->Admin_model->GetGivenRatingArray($sender_id,$currentweek->week,$currentweek->month,$currentweek->year,'inbound');
			$taken_rating_array_text = $this->Admin_model->GetTakenRatingArray($sender_id,$currentweek->week,$currentweek->month,$currentweek->year,'text');
			$taken_rating_array_voice = $this->Admin_model->GetTakenRatingArray($sender_id,$currentweek->week,$currentweek->month,$currentweek->year,'inbound');
            //print_r($given_rating_array_voice);
            
            /*for($i=0;$i<sizeof($groupmember);$i++){
                $groupmember[$i]['text_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"text");
		$groupmember[$i]['text_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"text");
			
			$groupmember[$i]['voice_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"voice");
			$groupmember[$i]['voice_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"voice");
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"text");
		
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"voice");
			
			$mainpercentage = 0;
			$count=0;
			if($gettextquestionpercentage!=""){
				$mainpercentage += $gettextquestionpercentage;
				$count++;
			}
			if($getvoicequestionpercentage!=""){
				$mainpercentage += $getvoicequestionpercentage;
				$count++;
			}
			if($count != 0){
				$groupmember[$i]['assessed_percentage'] = intval($mainpercentage/$count);
				$current_im_acieving += intval($mainpercentage/$count);
				$current_im_acieving_count++;
			}else{
				$groupmember[$i]['assessed_percentage'] = "NA";
			}
		}
		if($current_im_acieving_count!=0){
			$current_im_acieving = intval($current_im_acieving/$current_im_acieving_count);
		}*/
            $allweekdetails = $this->Admin_model->getallweekdetails($groupmember);
            //print_r($allweekdetails);
            $mytribe = $this->Admin_model->mytribe("",$current_week_paused_account,$permanent_paused_account);
            //$othertribe = "";
            $othertribe = $this->Admin_model->othertribe("",$current_week_paused_account,$permanent_paused_account);
            $squal_monthly_goal = $this->Admin_model->get_squad_monthly_goal();
            $squal_weekly_goal = $this->Admin_model->get_squad_weekly_goal($currentweek->week,$currentweek->month,$currentweek->year);
            $check_week_goal_entry = $this->Admin_model->check_week_goal_entry($currentweek->week,$currentweek->month,$currentweek->year);
            $check_month_goal_entry = $this->Admin_model->check_month_goal_entry();
            $this->load->view('admin_include/admin_header',array('header_title'=>'Dashboard'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'newdashboard'));
		$this->load->view('user/newdashboard',array('groupmember'=>$groupmember,
                                                            'current_im_acieving'=>$current_im_acieving,
                                                            'allweekdetails'=>$allweekdetails,
                                                            'mytribe'=>$mytribe,
                                                            'othertribe'=>$othertribe,
                                                            'squad_monthly_goal'=>$squal_monthly_goal,
                                                            'squad_weekly_goal'=>$squal_weekly_goal,
                                                            'check_week_goal_entry'=>$check_week_goal_entry,
                                                            'check_month_goal_entry'=>$check_month_goal_entry,
															'given_rating_array_text'=>$given_rating_array_text,
															'given_rating_array_voice'=>$given_rating_array_voice,
															'taken_rating_array_text'=>$taken_rating_array_text,
															'taken_rating_array_voice'=>$taken_rating_array_voice));
												
		$this->load->view('admin_include/admin_footer');
        }
	public function dashboard()
	{
		$this->checkloginuser();
		
		$this->Page_model->weeklyentry();
		$currentweek = $this->Admin_model->getcurrentweek(); //currentweek

               
                
		if(empty($currentweek))
		{
				$permanent_paused_account = 0;
				$current_week_paused_account = 0;
		}
		else
		{
			$permanent_paused_account = $this->Admin_model->get_permanent_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
			$current_week_paused_account = $this->Admin_model->get_current_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
		
		}
		print_r($currentweek);
		print_r($permanent_paused_account);
		print_r($current_week_paused_account);
		//exit;
		/*
		$groupmember = $this->Admin_model->getcurrentgroupmember("",$current_week_paused_account,$permanent_paused_account);
		$mytribe = $this->Admin_model->mytribe("3",$current_week_paused_account,$permanent_paused_account);
		$mycompleted = $this->Admin_model->getmycompleted($currentweek->week,$currentweek->month,$currentweek->year,$current_week_paused_account,$permanent_paused_account);
		$othertribe = $this->Admin_model->othertribe("",$current_week_paused_account,$permanent_paused_account);
		$allweekdetails = $this->Admin_model->getallweekdetails();

		for($i=0;$i<sizeof($groupmember);$i++){
			$groupmember[$i]['text_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"text");
			$groupmember[$i]['text_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"text");
			
			$groupmember[$i]['voice_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"voice");
			$groupmember[$i]['voice_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"voice");
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"text");
		
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"voice");
			
			$mainpercentage = 0;
			$count=0;
			if($gettextquestionpercentage!=""){
				$mainpercentage += $gettextquestionpercentage;
				$count++;
			}
			if($getvoicequestionpercentage!=""){
				$mainpercentage += $getvoicequestionpercentage;
				$count++;
			}
			if($count != 0){
				$groupmember[$i]['assessed_percentage'] = intval($mainpercentage/$count);
				$current_im_acieving += intval($mainpercentage/$count);
				$current_im_acieving_count++;
			}else{
				$groupmember[$i]['assessed_percentage'] = "NA";
			}
		}
		if($current_im_acieving_count!=0){
			$current_im_acieving = intval($current_im_acieving/$current_im_acieving_count);
		}
		/*$mycoin = "";
		$salt = "";
		if(!empty($_POST)){
			if(isset($_POST['weeklygoalbutton'])){
				if($_POST['weeklygoal']==""){
					$this->session->set_flashdata('message_eror', 'Please Enter Valid Weekly Goal!', 5);
					redirect("dashboard");
				}else{
					
					$data=array("user_id"=>$this->session->user_id,
								"weekly_goal"=>$_POST['weeklygoal'],
								"group_name"=>$this->session->group_name,
								"week"=>$_POST['week'],
								"month"=>$_POST['month'],
								"year"=>$_POST['year'],
								"createdat"=>date('Y-m-d h:i:s'));
					//print_r($data); print_r($_POST); exit;
					$this->Admin_model->insert("weekly_goal",$data);
					$this->session->set_flashdata('message_success', 'Weekly Goal Set Successfully!', 5);
					redirect("dashboard");
				}
			}
			
			if(isset($_POST['monthlygoalbutton'])){
				if($_POST['monthlygoal']==""){
					$this->session->set_flashdata('message_eror', 'Please Enter Valid Monthly Goal!', 5);
					redirect("dashboard");
				}else{
					$data=array("user_id"=>$this->session->user_id,
								"monthly_goal"=>$_POST['monthlygoal'],
								"group_name"=>$this->session->group_name,
								"month"=>$_POST['month'],
								"year"=>$_POST['year'],
								"createdat"=>date('Y-m-d h:i:s'));
					//print_r($data); print_r($_POST); exit;
					$this->Admin_model->insert("monthly_goal",$data);
					$this->session->set_flashdata('message_success', 'Monthly Goal Set Successfully!', 5);
					redirect("dashboard");
				}
			}
		}
		
		$currentweek = $this->Admin_model->getcurrentweek(); //currentweek
		//print_r($currentweek);
		//exit;
		if(isset($_GET['week']) && $_GET['week']!="" && isset($_GET['month']) && $_GET['month']!="" && isset($_GET['year']) && $_GET['year']!=""){
			$currentweek->week = $_GET['week'];
			$currentweek->month = $_GET['month'];
			$currentweek->year = $_GET['year'];
		}
		
		if(empty($currentweek))
		{
				$permanent_paused_account = 0;
				$current_week_paused_account = 0;
		}
		else
		{
			$permanent_paused_account = $this->Admin_model->get_permanent_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
			$current_week_paused_account = $this->Admin_model->get_current_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
		
		}
		$groupmember = $this->Admin_model->getcurrentgroupmember("",$current_week_paused_account,$permanent_paused_account);
		
		$current_im_acieving = 0;
		$current_im_acieving_count = 0;
		for($i=0;$i<sizeof($groupmember);$i++){
			$groupmember[$i]['text_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"text");
			$groupmember[$i]['text_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"text");
			
			$groupmember[$i]['voice_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"voice");
			$groupmember[$i]['voice_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"voice");
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"text");
		
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"voice");
			
			$mainpercentage = 0;
			$count=0;
			if($gettextquestionpercentage!=""){
				$mainpercentage += $gettextquestionpercentage;
				$count++;
			}
			if($getvoicequestionpercentage!=""){
				$mainpercentage += $getvoicequestionpercentage;
				$count++;
			}
			if($count != 0){
				$groupmember[$i]['assessed_percentage'] = intval($mainpercentage/$count);
				$current_im_acieving += intval($mainpercentage/$count);
				$current_im_acieving_count++;
			}else{
				$groupmember[$i]['assessed_percentage'] = "NA";
			}
		}
		if($current_im_acieving_count!=0){
			$current_im_acieving = intval($current_im_acieving/$current_im_acieving_count);
		}
		
		$sender_receiver_same = 0;
		$qa_id = 0;
		$getqamember = $this->Admin_model->getqamember($this->session->user_id);
		if(!empty($getqamember)){
			if(isset($getqamember['assessed_percentage'])){
				$qarating = $getqamember['assessed_percentage'];
				
				if(isset($getqamember['text_voice_complete']) && $getqamember['text_voice_complete']==1){
					$mainpercentage_user = 0;
					$count_user =0;
					$gettextquestionpercentage_user = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$this->session->user_id,"text");			
					// echo $this->db->last_query();
					// print_r($gettextquestionpercentage_user); exit;
					$getvoicequestionpercentage_user = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$this->session->user_id,"voice");
					
					if($gettextquestionpercentage_user!=""){
						$mainpercentage_user += $gettextquestionpercentage_user;
						$count_user++;
					}
					if($getvoicequestionpercentage_user!=""){
						$mainpercentage_user += $getvoicequestionpercentage_user;
						$count_user++;
					}
					if($count_user == 2){
						$sender_receiver_same = ceil($mainpercentage_user/2);
						$qa_id = $getqamember['qa_id'];
					}
				}
			}else{
				$qarating = "";
			}
		}else{
			$qarating = "";
		}
		*/
		$mytribe = $this->Admin_model->mytribe("",$current_week_paused_account,$permanent_paused_account);
		$othertribe = $this->Admin_model->othertribe("",$current_week_paused_account,$permanent_paused_account);
		$mypersonaltribe = $this->Admin_model->memberweeklyrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id);
		//print_r($mypersonaltribe);
		// $mytribeforqamember = $this->Admin_model->mytribeforqamember();
		// $othertribeforqamember = $this->Admin_model->mytribeforothermember();
		// if($mytribeforqamember['percentage']!=0){
			// $mytribe['percentage'] = intval(($mytribeforqamember['percentage'] + $mytribe['percentage']) /2);
		// }
		// if($othertribeforqamember['percentage']!=0){
			// $othertribe['percentage'] = intval(($othertribeforqamember['percentage'] + $othertribe['percentage']) /2);
		// }
		$allweekdetails = $this->Admin_model->getallweekdetails();
		
		$mycompleted = $this->Admin_model->getmycompleted($currentweek->week,$currentweek->month,$currentweek->year,$current_week_paused_account,$permanent_paused_account);
		//print_r($allweekdetails); exit;
		$squal_monthly_goal = $this->Admin_model->get_squad_monthly_goal();
		$squal_weekly_goal = $this->Admin_model->get_squad_weekly_goal($currentweek->week,$currentweek->month,$currentweek->year);
		
		//echo '<pre>'; print_r($groupmember); echo '</pre>'; exit;
		
		// check account paused
		$paused_account = $this->Admin_model->check_account_paused($currentweek->week,$currentweek->month,$currentweek->year);
		$check_account_paused_in_previous_month = $this->Admin_model->check_account_paused_in_previous_month();
		
		$check_week_goal_entry = $this->Admin_model->check_week_goal_entry($currentweek->week,$currentweek->month,$currentweek->year);
		$check_month_goal_entry = $this->Admin_model->check_month_goal_entry();
		$this->load->view('admin_include/admin_header',array('header_title'=>'Dashboard'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'dashboard'));
		$this->load->view('user/dashboard',array(
												//'data'=>$mycoin,
												//'salt'=>$salt,
												
												'mytribe'=>$mytribe,
												'mycompleted'=>$mycompleted,
												'othertribe'=>$othertribe,
												'allweekdetails'=>$allweekdetails,
												'current_im_acieving'=>$current_im_acieving,
												'currentweek'=>$currentweek,
                                                                                                'check_week_goal_entry'=>$check_week_goal_entry,
												'check_month_goal_entry'=>$check_month_goal_entry,
												'squal_monthly_goal'=>$squal_monthly_goal,
												'squal_weekly_goal'=>$squal_weekly_goal,
												'groupmember'=>$groupmember,
												'qarating'=>$qarating,
												'paused_account'=>$paused_account,
												'check_account_paused_in_previous_month'=>$check_account_paused_in_previous_month,
												"sender_receiver_same"=>$sender_receiver_same,
												"qa_id"=>$qa_id));
		$this->load->view('admin_include/admin_footer');
	}
	
	public function mynew()
	{
		$allweekdetails = $this->Admin_model->getstarttoendweekdetails_graph();
		$qarating = array();
		$_SESSION['user_id'] = 19;
		//print_R($_SESSION); 
		for($i=0;$i<sizeof($allweekdetails);$i++){
			$week = $allweekdetails[$i]['week'];
			$month = $allweekdetails[$i]['month'];
			$year = $allweekdetails[$i]['year'];
			
			$getqamember = $this->Admin_model->getqamember_graph($this->session->user_id,$week,$month,$year);
				if(!empty($getqamember)){
					if(isset($getqamember['assessed_percentage'])){
						$qarating[$year][$month][$week] = $getqamember['assessed_percentage'];
						
						if(isset($getqamember['text_voice_complete']) && $getqamember['text_voice_complete']==1){
							$mainpercentage_user = 0;
							$count_user =0;
							$gettextquestionpercentage_user = $this->Admin_model->getratingpercentage($week,$month,$year,$this->session->user_id,$this->session->user_id,"text");			
							// echo $this->db->last_query();
							// print_r($gettextquestionpercentage_user); exit;
							$getvoicequestionpercentage_user = $this->Admin_model->getratingpercentage($week,$month,$year,$this->session->user_id,$this->session->user_id,"voice");
							
							if($gettextquestionpercentage_user!=""){
								$mainpercentage_user += $gettextquestionpercentage_user;
								$count_user++;
							}
							if($getvoicequestionpercentage_user!=""){
								$mainpercentage_user += $getvoicequestionpercentage_user;
								$count_user++;
							}
							if($count_user == 2){
								$sender_receiver_same[$year][$month][$week] = ceil($mainpercentage_user/2);
								$qa_id = $getqamember['qa_id'];
							}else{
								$sender_receiver_same[$year][$month][$week] = 0;
							}
						}
					}else{
						$qarating[$year][$month][$week] = 0;
						$sender_receiver_same[$year][$month][$week] = 0;
					}
				}else{
					$qarating[$year][$month][$week] = 0;
					$sender_receiver_same[$year][$month][$week] = 0;
				}
		}
		// echo '<pre>';
		// echo "QARATING";
		// print_r($qarating);
		// echo "Sender Receiver Same";
		// print_R($sender_receiver_same);
		$this->load->view('graph',array('qarating'=>$qarating,'sender_receiver_same'=>$sender_receiver_same));
	}
	
	public function resume_account()
	{
		if(empty($_POST)){
			redirect("dashboard");
		}
		$this->Admin_model->update("pause",array("message"=>$_POST['message']),array("id"=>$_POST['id']));
		$this->session->set_flashdata('message_success', 'Your Message has been Sent.!', 5);
		redirect("dashboard");
	}
	
	public function changepassword()
	{
		$this->checkloginuser();
		
		if(!empty($_POST))
		{
			if($_POST['old_password']==""){
				$this->session->set_flashdata('message_eror', 'Please Enter Old Password!', 5);
				redirect("changepassword");
			}
			
			if($_POST['old_password']!=$_SESSION['password']){
				$this->session->set_flashdata('message_eror', 'Please Enter Correct Old Password!', 5);
				redirect("changepassword");
			}
			
			if($_POST['new_password'] != $_POST['confirm_password']){
				$this->session->set_flashdata('message_eror', 'New And Confirm Password is Not Match!', 5);
				redirect("changepassword");
			}
			
			$this->Page_model->changepassword();
		}
		
		$mycoin = "";
		$salt = "";
		$this->load->view('admin_include/admin_header',array('header_title'=>'Change Password'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'profile'));
		$this->load->view('user/changepassword',array('data'=>$mycoin,'salt'=>$salt));
		$this->load->view('admin_include/admin_footer');
	}
	public function optionvoicerating()
	{
		$this->load->view('admin_include/admin_header',array('header_title'=>'Add Assessment'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'rating'));
		//$this->load->view('user/addrating',array('getalltextquestions'=>$getalltextquestions,'userdata_get'=>$userdata_get,'getallvoicequestions'=>$getallvoicequestions,'getalltextquestionscomplete'=>$getalltextquestionscomplete,'getallvoicequestionscomplete'=>$getallvoicequestionscomplete,'gettextquestionpercentage'=>$gettextquestionpercentage,'getvoicequestionpercentage'=>$getvoicequestionpercentage,'getalltextareaquestion'=>$getalltextareaquestion,'igivethisman'=>$igivethisman,"file_name"=>$file_name,"percentage_gape_message"=>$percentage_gape_message));
		$this->load->view('user/optionvoicerating',array());
		$this->load->view('admin_include/admin_footer');
	}
	public function addrating()
	{
		$this->checkloginuser();
		
		$currentweek = $this->Admin_model->getcurrentweek();
		if(isset($_GET['week']) && $_GET['week']!="" && isset($_GET['month']) && $_GET['month']!="" && isset($_GET['year']) && $_GET['year']!=""){
			$currentweek->week = $_GET['week'];
			$currentweek->month = $_GET['month'];
			$currentweek->year = $_GET['year'];
		}
                
		//Submit Rating Start
		/*if(!empty($_POST)){
			//print_r($_POST); 
			$sender_id = $this->session->user_id;
			$receiver_id = $_GET['id'];
			$type=$_GET['type'];
			
			
			$category_id="";
			$j=0;
			for($i=0;$i<sizeof($_POST['question_id']);$i++)
			{
				$data = array('type'=>$type,
							'sender_id'=>$sender_id,
							'receiver_id'=>$receiver_id,
							'question_id'=>$_POST['question_id'][$i],
							'category_id'=>$_POST['category_id'][$i],
							'rating'=>$_POST['rating'][$i],
							'week'=>$currentweek->week,
							'month'=>$currentweek->month,
							'year'=>$currentweek->year,
							'createdat'=>date('Y-m-d h:i:s'));
				
				$this->Admin_model->insert("rating",$data);
				
				if($category_id!=$_POST['category_id'][$i]){
					$data2 = array('type'=>$type,
								'sender_id'=>$sender_id,
								'receiver_id'=>$receiver_id,
								'question_id'=>$_POST['question_id'][$i],
								'category_id'=>$_POST['category_id'][$i],
								'text_rating'=>$_POST['textarea'][$j],
								'week'=>$currentweek->week,
								'month'=>$currentweek->month,
								'year'=>$currentweek->year,
								'createdat'=>date('Y-m-d h:i:s'));
					$j++;
					$this->Admin_model->insert("rating",$data2);
					//print_r($data2);
				}
				
				$category_id=$_POST['category_id'][$i];
				
			}
			
			$data_file_name = array('type'=>$type,
							'sender_id'=>$sender_id,
							'receiver_id'=>$receiver_id,
							'file_name'=>$_POST['file_name'][$j],
							'week'=>$currentweek->week,
							'month'=>$currentweek->month,
							'year'=>$currentweek->year,
							'createdat'=>date('Y-m-d h:i:s'));
			$this->Admin_model->insert("rating",$data_file_name);
			
			$this->session->set_flashdata('message_success', 'Rating is saved Successfully!', 5);
			if($this->session->role == 49){
				redirect("qamember/dashboard");
			}
			redirect("dashboard");
		}*/

		//Submit Rating End
		$userdata_get = array();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$userdata_get = $this->Admin_model->getoneuserdata($_GET['id']);
		}
		if(empty($userdata_get)){
			$userdata_get[0]['first_name']="";
		}
		//$getalltextquestions = $this->Admin_model->getalltextquestions();
		//$getallvoicequestions = $this->Admin_model->getallvoicequestions();
		//$currentweek = $this->Admin_model->getcurrentweek(); //currentweek
		
		//$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type']);
		$getalltextareaquestion = array();
		$file_name = "";
		$percentage_gape_message = array();
		

		//For Geting Previous Rating Start
		if(isset($_GET['sender_receiver']) && $_GET['sender_receiver']=="same"){
			$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"text");
			$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"voice");
			$igivethisman = "yes";
			/*if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
				}
			}*/
			
			//$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$_GET['id'],$_GET['id']);
			
			//$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"text");
			
			//$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"voice");
			
			//$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$_GET['id']);
			
			//$percentage_gape_message = $this->Admin_model->get_percentage_gap_message($currentweek->week,$currentweek->month,$currentweek->year,$_GET['sender_id'],$_GET['receiver_id']);
		}elseif(isset($_GET['oww']) && $_GET['oww']=="yes"){
			$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"text");
			$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"voice");
			$igivethisman = "yes";
			if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
				}
			}
			
			$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$_GET['id'],$this->session->user_id);
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"text");
			
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"voice");
			
			$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$this->session->user_id);
			
		}
		elseif(isset($_GET['admin']) && $_GET['admin']=="yes")
		{
			//$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"text");
			//$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"voice");
			$igivethisman = "yes";
			if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
					
				}
			}
			
			$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$_GET['send_id'],$_GET['id']);
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"text");
			
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"voice");
			
			$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$_GET['send_id']);
		}
		else{
			if($_GET['type'] == "text")
			{
				$getallcatageory = $this->Admin_model->getnewallcategory("text");
			}
			elseif($_GET['type'] == "inbound")
			{
				$getallcatageory = $this->Admin_model->getnewallcategory("inbound");
			}
			elseif($_GET['type'] == "outbound")
			{
				$getallcatageory = $this->Admin_model->getnewallcategory("outbound");
			}
			//$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"text");
			//$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"voice");
			$igivethisman = "yes";
			if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
					
					if(!empty($getalltextquestionscomplete) || !empty($getallvoicequestionscomplete)){
						if($this->session->user_id!=$qamember[$i]['id']){
							$igivethisman="no";
						}
						break;
					}
				}
			}
			
			//$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$this->session->user_id,$_GET['id']);
			
			//$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"text");
			
			//$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"voice");
			
			//$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$this->session->user_id);
		}
		if(is_array($file_name) && isset($file_name[0]['file_name'])){
			$file_name = $file_name[0]['file_name'];
		}else{
			$file_name="";
		}
		
		if(!empty($_POST)){
			//print_r($_POST); 
                        //exit;
			$sender_id = $this->session->user_id;
			$receiver_id = $_GET['id'];
			$type=$_GET['type'];
                        $week = $currentweek->week;
                        $month = $currentweek->month;
                        $year = $currentweek->year;
                        $category_value = 0;
						$old_category = 0;
						$total_rating = 0;
                       for($i=0;$i<sizeof($_POST['question_ids']);$i++)
                       {
                           $result=array("sender_id"=>$sender_id,"receiver_id"=>$receiver_id,"type"=>$type,"question_id"=>$_POST['question_ids'][$i],"category_id"=>$_POST['cat_ids'][$i],
                            "rating"=>$_POST['ans_values'][$i],"text_rating"=>'',"file_name"=>'',
                            "week"=>$week,"month"=>$month,"year"=>$year,"createdat"=>date('Y-m-d h:i:s')
                            );
							$total_rating += $_POST['ans_values'][$i];
							$this->Page_model->addrating($result);
							$current_category = $_POST['cat_ids'][$i];
							
							if($current_category == $old_category || $old_category == 0)
							{
								if($i == (sizeof($_POST['question_ids'])-1))
								{
									$category_value += $_POST['ans_values'][$i];
									$result_category = array("sender_id"=>$sender_id,"receiver_id"=>$receiver_id,"type"=>$type,"category_id"=>$old_category,
									"rating"=>$category_value,"week"=>$week,"month"=>$month,"year"=>$year,"createdat"=>date('Y-m-d h:i:s'));
									$this->Page_model->addratingbycategory($result_category);
									
								}
								else
								{
									$category_value += $_POST['ans_values'][$i];
									$old_category = $current_category;
								}
							}
							else
							{
								//First Add Record In Database
								$result_category = array("sender_id"=>$sender_id,"receiver_id"=>$receiver_id,"type"=>$type,"category_id"=>$old_category,
								"rating"=>$category_value,"week"=>$week,"month"=>$month,"year"=>$year,"createdat"=>date('Y-m-d h:i:s'));
								$this->Page_model->addratingbycategory($result_category);
								$category_value = 0;
								$category_value += $_POST['ans_values'][$i];
								$old_category = $current_category;
								
							}
							
                       }
                    $result_week = array("sender_id"=>$sender_id,"receiver_id"=>$receiver_id,"type"=>$type,
					"rating"=>$total_rating,"week"=>$week,"month"=>$month,"year"=>$year,"createdat"=>date('Y-m-d h:i:s'));
					$this->Page_model->addratingbyweek($result_week);
								
                        
                }
		$this->load->view('admin_include/admin_header',array('header_title'=>'Add Assessment'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'rating'));
		//$this->load->view('user/addrating',array('getalltextquestions'=>$getalltextquestions,'userdata_get'=>$userdata_get,'getallvoicequestions'=>$getallvoicequestions,'getalltextquestionscomplete'=>$getalltextquestionscomplete,'getallvoicequestionscomplete'=>$getallvoicequestionscomplete,'gettextquestionpercentage'=>$gettextquestionpercentage,'getvoicequestionpercentage'=>$getvoicequestionpercentage,'getalltextareaquestion'=>$getalltextareaquestion,'igivethisman'=>$igivethisman,"file_name"=>$file_name,"percentage_gape_message"=>$percentage_gape_message));
		$this->load->view('user/addrating',array('getallcatageory'=>$getallcatageory,'userdata_get'=>$userdata_get));
		$this->load->view('admin_include/admin_footer');
	}
	
	public function addnewrating()
	{
		$this->checkloginuser();
		
		$currentweek = $this->Admin_model->getcurrentweek();
		if(isset($_GET['week']) && $_GET['week']!="" && isset($_GET['month']) && $_GET['month']!="" && isset($_GET['year']) && $_GET['year']!=""){
			$currentweek->week = $_GET['week'];
			$currentweek->month = $_GET['month'];
			$currentweek->year = $_GET['year'];
		}
		if(!empty($_POST)){
			//print_r($_POST); 
			$sender_id = $this->session->user_id;
			$receiver_id = $_GET['id'];
			$type=$_GET['type'];
			
			
			$category_id="";
			$j=0;
			for($i=0;$i<sizeof($_POST['question_id']);$i++)
			{
				$data = array('type'=>$type,
							'sender_id'=>$sender_id,
							'receiver_id'=>$receiver_id,
							'question_id'=>$_POST['question_id'][$i],
							'category_id'=>$_POST['category_id'][$i],
							'rating'=>$_POST['rating'][$i],
							'week'=>$currentweek->week,
							'month'=>$currentweek->month,
							'year'=>$currentweek->year,
							'createdat'=>date('Y-m-d h:i:s'));
				
				$this->Admin_model->insert("rating",$data);
				
				if($category_id!=$_POST['category_id'][$i]){
					$data2 = array('type'=>$type,
								'sender_id'=>$sender_id,
								'receiver_id'=>$receiver_id,
								'question_id'=>$_POST['question_id'][$i],
								'category_id'=>$_POST['category_id'][$i],
								'text_rating'=>$_POST['textarea'][$j],
								'week'=>$currentweek->week,
								'month'=>$currentweek->month,
								'year'=>$currentweek->year,
								'createdat'=>date('Y-m-d h:i:s'));
					$j++;
					$this->Admin_model->insert("rating",$data2);
					//print_r($data2);
				}
				
				$category_id=$_POST['category_id'][$i];
				
			}
			
			$data_file_name = array('type'=>$type,
							'sender_id'=>$sender_id,
							'receiver_id'=>$receiver_id,
							'file_name'=>$_POST['file_name'][$j],
							'week'=>$currentweek->week,
							'month'=>$currentweek->month,
							'year'=>$currentweek->year,
							'createdat'=>date('Y-m-d h:i:s'));
			$this->Admin_model->insert("rating",$data_file_name);
			
			$this->session->set_flashdata('message_success', 'Rating is saved Successfully!', 5);
			if($this->session->role == 49){
				redirect("qamember/dashboard");
			}
			redirect("dashboard");
		}
		$userdata_get = array();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$userdata_get = $this->Admin_model->getoneuserdata($_GET['id']);
		}
		if(empty($userdata_get)){
			$userdata_get[0]['first_name']="";
		}
		$getalltextquestions = $this->Admin_model->getalltextquestions();
		$getallvoicequestions = $this->Admin_model->getallvoicequestions();
		//$currentweek = $this->Admin_model->getcurrentweek(); //currentweek
		
		//$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type']);
		$getalltextareaquestion = array();
		$file_name = "";
		$percentage_gape_message = array();
		
		if(isset($_GET['sender_receiver']) && $_GET['sender_receiver']=="same"){
			$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"text");
			$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"voice");
			$igivethisman = "yes";
			/*if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
				}
			}*/
			
			$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$_GET['id'],$_GET['id']);
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"text");
			
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$_GET['id'],"voice");
			
			$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$_GET['id']);
			
			$percentage_gape_message = $this->Admin_model->get_percentage_gap_message($currentweek->week,$currentweek->month,$currentweek->year,$_GET['sender_id'],$_GET['receiver_id']);
		}elseif(isset($_GET['oww']) && $_GET['oww']=="yes"){
			$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"text");
			$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"voice");
			$igivethisman = "yes";
			if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
				}
			}
			
			$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$_GET['id'],$this->session->user_id);
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"text");
			
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['id'],$this->session->user_id,"voice");
			
			$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$this->session->user_id);
			
		}
		elseif(isset($_GET['admin']) && $_GET['admin']=="yes")
		{
			$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"text");
			$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"voice");
			$igivethisman = "yes";
			if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
					
				}
			}
			
			$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$_GET['send_id'],$_GET['id']);
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"text");
			
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$_GET['send_id'],$_GET['id'],"voice");
			
			$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$_GET['send_id']);
		}
		else{
			$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"text");
			$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"voice");
			$igivethisman = "yes";
			if($this->session->role == 49){
				$qamember = $this->Admin_model->getallqamember();
				for($i=0;$i<sizeof($qamember);$i++)
				{
					$getalltextquestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"text");
					$getallvoicequestionscomplete = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$qamember[$i]['id'],$_GET['id'],"voice");
					
					if(!empty($getalltextquestionscomplete) || !empty($getallvoicequestionscomplete)){
						if($this->session->user_id!=$qamember[$i]['id']){
							$igivethisman="no";
						}
						break;
					}
				}
			}
			
			$getalltextareaquestion = $this->Admin_model->getalltextareaquestion($_GET['type'],$this->session->user_id,$_GET['id']);
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"text");
			
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$_GET['id'],"voice");
			
			$file_name = $this->Admin_model->getfilename($currentweek->week,$currentweek->month,$currentweek->year,$_GET['type'],$_GET['id'],$this->session->user_id);
		}
		if(is_array($file_name) && isset($file_name[0]['file_name'])){
			$file_name = $file_name[0]['file_name'];
		}else{
			$file_name="";
		}
		
		
		$this->load->view('admin_include/admin_header',array('header_title'=>'Add Assessment'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'rating'));
		$this->load->view('user/addnewrating',array('getalltextquestions'=>$getalltextquestions,'userdata_get'=>$userdata_get,'getallvoicequestions'=>$getallvoicequestions,'getalltextquestionscomplete'=>$getalltextquestionscomplete,'getallvoicequestionscomplete'=>$getallvoicequestionscomplete,'gettextquestionpercentage'=>$gettextquestionpercentage,'getvoicequestionpercentage'=>$getvoicequestionpercentage,'getalltextareaquestion'=>$getalltextareaquestion,'igivethisman'=>$igivethisman,"file_name"=>$file_name,"percentage_gape_message"=>$percentage_gape_message));
		$this->load->view('admin_include/admin_footer');
	}

	public function view_previous()
	{
		$this->checkloginuser();
		$data = $this->Admin_model->get_all_week();
		$this->load->view('admin_include/admin_header',array('header_title'=>'View Previous'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'viewprevious'));
		$this->load->view('user/viewprevious',array('data'=>$data));
		$this->load->view('admin_include/admin_footer');
	}
	
	public function kmwallet()
	{
		$mycoin = $this->Admin_model->get_available_coins_by_userid($this->session->user_id);
		$this->load->view('user_include/user_header');
		$this->load->view('user_include/user_sidebar');
		$this->load->view('user_include/user_footer');
		$this->load->view('user/kmwallet',array('data'=>$mycoin));
	}
	
	public function pause_account()
	{
		$this->checkloginuser();
		$currentweek = $this->Admin_model->getcurrentweek();
		$paused_account = $this->Admin_model->check_account_paused($currentweek->week,$currentweek->month,$currentweek->year);
		if(empty($paused_account)){
			
			$data = array('user_id'=>$this->session->user_id,
							'week'=>$currentweek->week,
							'month'=>$currentweek->month,
							'year'=>$currentweek->year,
							'createdat'=>date("Y-m-d H:i:s"));
			$this->Admin_model->insert("pause",$data);
			$this->session->set_flashdata('message_success', 'Pause Account Successfully!', 5);
			redirect("dashboard");
		}else{
			$this->session->set_flashdata('message_success', 'Your Account is Already Paused!', 5);
			redirect("dashboard");
		}
	}
	
	public function sendmessage()
	{
		$data =array("message"=>$_POST['message'],
					 "sender_user_id"=>$_POST['sender_id'],
					 "receiver_user_id"=>$_POST['receiver_id'],
					 "week"=>$_POST['week'],
					 "month"=>$_POST['month'],
					 "year"=>$_POST['year'],
					 "createdat"=>date("Y:m:d H:i:s"));
					 
		$this->Admin_model->insert("message",$data);
		$this->session->set_flashdata('message_success', 'Message Sent Successfully!', 5);
		redirect(base_url()."addrating?id=".$_POST['id']."&sender_receiver=".$_POST['sender_receiver']."&type=".$_POST['type']."&sender_id=".$_POST['sender_id']."&receiver_id=".$_POST['receiver_id']."&week=".$_POST['week']."&month=".$_POST['month']."&year=".$_POST['year']);
	}
	
	public function forgot()
	{
		if(!empty($_POST)){
			$length=50;
			$salt2 = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
			 
			$salt = base_url()."pages/recover/".$salt2;
			
			$checkmail = $this->Page_model->check_email_exists($_POST['email']);
			
			if($checkmail=="1"){
				$this->session->set_flashdata('incorrect_info', "Email Address is not found with our system..!",5); 
				redirect(base_url()."forgot");
			}
			
			$msg = "For reset your password ";
			$msg .= "<a href='".$salt."'>Click Here...</a>";
			$headers = "MIME-Version: 1.0" . "\r\n";
			$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
			//$email = "devangfour@gmail.com";
			$email = $_POST['email'];
			if(mail($email,"GWStarter Recover Password",$msg,$headers)) {
				$this->Admin_model->update('users',array("salt" => $salt2),array("email" => $_POST['email']));
				$this->session->set_flashdata('inactive_error', "We send you password recover details from your email address..!",5); 
				redirect(base_url()."login");
			}else{
				$this->session->set_flashdata('incorrect_info', "Email Not Send Successfully..!",5); 
				redirect(base_url()."forgot");
			}
			
		}
		$this->load->view('front_include/front_header');
		$this->load->view('forgot');
		$this->load->view('front_include/front_footer');
	}
	
	public function recover($salt = null)
	{
		if($salt==""){
			redirect(base_url()."login");
		}
		$checksalt = $this->Page_model->check_user_by_salt($salt);
		
		if(empty($checksalt)){
			$this->session->set_flashdata('incorrect_info', "Your Link is expired..!",5); 
			redirect(base_url()."login");
		}
		if(!empty($_POST)){
			if($_POST['new_password'] != $_POST['confirm_password']){
				$this->session->set_flashdata('incorrect_info', "Password And Confirm Password is not match..!",5); 
				redirect(base_url()."pages/recover/".$salt);	
			}else{
				if($this->Admin_model->update("users",array("password"=>$_POST['new_password']),array("id"=>$checksalt['id']))){
					$this->session->set_flashdata('incorrect_info', "Password is changed successfully..!",5); 
					$this->Admin_model->update("users",array("salt"=>""),array("id"=>$checksalt['id']));
					redirect(base_url()."login");
				}else{
					$this->session->set_flashdata('incorrect_info', "Password is not changed successfully..!".$this->db->last_query(),5); 
					redirect(base_url()."pages/recover/".$salt);	
				}
			}
		}
		
		$this->load->view('front_include/front_header');
		$this->load->view('recover');
		$this->load->view('front_include/front_footer');
	}
	
	 public function logout() {

        $this->session->sess_destroy();

        redirect(base_url());

    }
	
	public function check_username_exists($username) {

        $this->form_validation->set_message('check_username_exists', 'That username is taken. Please choose a different one');

        if ($this->Page_model->check_username_exists($username)) {

            return true;

        } else {

            return false;

        }

    }
	
	public function check_mobilenumber_exists($mobile) {

        $this->form_validation->set_message('check_mobilenumber_exists', 'That mobile number alredy taken. Please choose a different one');

        if ($this->Page_model->check_mobilenumber_exists($mobile)) {

            return true;

        } else {

            return false;

        }

    }
	
	public function check_emailaddress_exists($email) {

        $this->form_validation->set_message('check_emailaddress_exists', 'That Email Address is alredy taken. Please choose a different one');

        if ($this->Page_model->check_email_exists($email)) {

            return true;

        } else {

            return false;

        }

    }
}
