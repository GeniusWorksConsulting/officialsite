<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Qamember extends CI_Controller {

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
		if($this->session->userdata('user_id')){
			redirect(base_url() . 'admin/dashboard');
		}else{
			$this->load->helper('url');
			$this->load->view('admin/login');
		}
	}
	
	public function checkloginadmin()
	{
		if(!$this->session->role == "49"){
			redirect(base_url() . 'login');
		}
	}
	
	public function login()
	{
		$result = $this->Admin_model->login();
	
        if ($result == 1) {
			
			if($this->session->user_role == 0){
				redirect(base_url() . 'dashboard');
			}elseif($this->session->user_role == 1){
				redirect(base_url() . 'admin/dashboard');
			}elseif($this->session->user_role == 49){
				redirect(base_url() . 'qamember/dashboard');
			}

        } else {

            redirect(base_url() . 'admin');

        }
	}
	
	public function dashboard()
	{ 
		$this->checkloginadmin();
		$this->Page_model->weeklyentry();
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
		if(isset($_GET['week']) && $_GET['week']!="" && isset($_GET['month']) && $_GET['month']!="" && isset($_GET['year']) && $_GET['year']!=""){
			$currentweek->week = $_GET['week'];
			$currentweek->month = $_GET['month'];
			$currentweek->year = $_GET['year'];
		}
		
		$permanent_paused_account = $this->Admin_model->get_permanent_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
		$paused_account = $this->Admin_model->get_current_paused_account($currentweek->week,$currentweek->month,$currentweek->year);
		$groupmember = $this->Admin_model->getcurrentgroupmember('1',$paused_account,$permanent_paused_account);
		//print_r($currentweek);
		//exit;
		$current_im_acieving = 0;
		$current_im_acieving_count = 0;
		$igiveto3man = 0;
		for($i=0;$i<sizeof($groupmember);$i++){
			$groupmember[$i]['text_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"text");
			
			$groupmember[$i]['text_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"text");						
			
			$groupmember[$i]['voice_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"voice");
			
			$groupmember[$i]['voice_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$this->session->user_id,"voice");
			
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"text");			
			
		
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember[$i]['id'],"voice");
			
			
			
			$getqamember = $this->Admin_model->getqamember($groupmember[$i]['id']);
			if(!empty($getqamember)){
				if(isset($getqamember['assessed_percentage'])){
					$groupmember[$i]['qarating'] = $getqamember['assessed_percentage'];
				}else{
					$groupmember[$i]['qarating'] = "";
				}
				
			}else{
				$groupmember[$i]['qarating'] = "";
			}
			$igiveto3man = $igiveto3man + $getqamember['igiveto3man'];
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
				$groupmember[$i]['percentage'] = ceil($mainpercentage/2);
				$current_im_acieving += ceil($mainpercentage/$count);
				$current_im_acieving_count++;
				
				if($count == 2){
					$mainpercentage_user = 0;
					$count_user =0;
					$gettextquestionpercentage_user = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$groupmember[$i]['id'],"text");			
					// echo $this->db->last_query();
					// print_r($gettextquestionpercentage_user); exit;
					$getvoicequestionpercentage_user = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember[$i]['id'],$groupmember[$i]['id'],"voice");
					
					if($gettextquestionpercentage_user!=""){
						$mainpercentage_user += $gettextquestionpercentage_user;
						$count_user++;
					}
					if($getvoicequestionpercentage_user!=""){
						$mainpercentage_user += $getvoicequestionpercentage_user;
						$count_user++;
					}
					if($count_user == 2){
						$groupmember[$i]['user_percentage'] = ceil($mainpercentage_user/2);
					}
				}
				
			}else{
				$groupmember[$i]['percentage'] = "0";
			}
		}
		
		$groupmember2 = $this->Admin_model->getcurrentgroupmember('2',$paused_account,$permanent_paused_account);
		//$currentweek = $this->Admin_model->getcurrentweek(); //currentweek
		$current_im_acieving = 0;
		$current_im_acieving_count = 0;
		
		for($i=0;$i<sizeof($groupmember2);$i++){
			$groupmember2[$i]['text_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember2[$i]['id'],"text");
			$groupmember2[$i]['text_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember2[$i]['id'],$this->session->user_id,"text");
			
			$groupmember2[$i]['voice_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember2[$i]['id'],"voice");
			$groupmember2[$i]['voice_assessed_rating'] = $this->Admin_model->getrating($currentweek->week,$currentweek->month,$currentweek->year,$groupmember2[$i]['id'],$this->session->user_id,"voice");
			
			$gettextquestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember2[$i]['id'],"text");
		
			$getvoicequestionpercentage = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$this->session->user_id,$groupmember2[$i]['id'],"voice");
			
			$getqamember = $this->Admin_model->getqamember($groupmember2[$i]['id']);
			if(!empty($getqamember)){
				if(isset($getqamember['assessed_percentage'])){
					$groupmember2[$i]['qarating'] = $getqamember['assessed_percentage'];
				}else{
					$groupmember2[$i]['qarating'] = "";
				}
			}else{
				$groupmember2[$i]['qarating'] = "";
			}
			$igiveto3man = $igiveto3man + $getqamember['igiveto3man'];
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
				
				$groupmember2[$i]['percentage'] = ceil($mainpercentage/2);
				$current_im_acieving += ceil($mainpercentage/$count);
				$current_im_acieving_count++;
				
				if($count == 2){
					$mainpercentage_user = 0;
					$count_user =0;
					$gettextquestionpercentage_user = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember2[$i]['id'],$groupmember2[$i]['id'],"text");			
					// echo $this->db->last_query();
					// print_r($gettextquestionpercentage_user); exit;
					$getvoicequestionpercentage_user = $this->Admin_model->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$groupmember2[$i]['id'],$groupmember2[$i]['id'],"voice");
					
					if($gettextquestionpercentage_user!=""){
						$mainpercentage_user += $gettextquestionpercentage_user;
						$count_user++;
					}
					if($getvoicequestionpercentage_user!=""){
						$mainpercentage_user += $getvoicequestionpercentage_user;
						$count_user++;
					}
					if($count_user == 2){
						$groupmember2[$i]['user_percentage'] = ceil($mainpercentage_user/2);
					}
				}
			}else{
				$groupmember2[$i]['percentage'] = "0";
			}
		}
		if($current_im_acieving_count!=0){
			$current_im_acieving = ceil($current_im_acieving/$current_im_acieving_count);
		}
		
		$mytribe = $this->Admin_model->mytribe('1',$paused_account,$permanent_paused_account);
		$othertribe = $this->Admin_model->mytribe('2',$paused_account,$permanent_paused_account);
		
		// $mytribeforqamember = $this->Admin_model->mytribeforqamember("1");
		// $othertribeforqamember = $this->Admin_model->mytribeforqamember("2");
		// if($mytribeforqamember['percentage']!=0){
			// $mytribe['percentage'] = ceil(($mytribeforqamember['percentage'] + $mytribe['percentage']) /2);
		// }
		// if($othertribeforqamember['percentage']!=0){
			// $othertribe['percentage'] = ceil(($othertribeforqamember['percentage'] + $othertribe['percentage']) /2);
		// }
		
		$allweekdetails = array();
		// print_r($groupmember);
		$mycompleted = $this->Admin_model->getmycompleted($currentweek->week,$currentweek->month,$currentweek->year);
		//print_r($allweekdetails); exit;
		$squal_monthly_goal = $this->Admin_model->get_squad_monthly_goal();
		$squal_weekly_goal = $this->Admin_model->get_squad_weekly_goal($currentweek->week,$currentweek->month,$currentweek->year);
		
		//echo '<pre>'; print_r($groupmember); echo '</pre>'; exit;
		$check_week_goal_entry = $this->Admin_model->check_week_goal_entry($currentweek->week,$currentweek->month,$currentweek->year);
		$check_month_goal_entry = $this->Admin_model->check_month_goal_entry();
		$this->load->view('admin_include/admin_header',array('header_title'=>'Dashboard'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'dashboard'));
		$this->load->view('qamember/dashboard',array('check_week_goal_entry'=>$check_week_goal_entry,
												'mytribe'=>$mytribe,
												'mycompleted'=>$mycompleted,
												'othertribe'=>$othertribe,
												'allweekdetails'=>$allweekdetails,
												'current_im_acieving'=>$current_im_acieving,
												'currentweek'=>$currentweek,
												'check_month_goal_entry'=>$check_month_goal_entry,
												'squal_monthly_goal'=>$squal_monthly_goal,
												'squal_weekly_goal'=>$squal_weekly_goal,
												'groupmember'=>$groupmember,
												'groupmember2'=>$groupmember2));
		$this->load->view('admin_include/admin_footer');
	}
	
	public function view_previous()
	{
		$this->checkloginadmin();
		$data = $this->Admin_model->get_all_week();
		$this->load->view('admin_include/admin_header',array('header_title'=>'View Previous'));
		$this->load->view('admin_include/admin_sidebar',array('active_page'=>'viewprevious'));
		$this->load->view('qamember/viewprevious',array('data'=>$data));
		$this->load->view('admin_include/admin_footer');
	}
	
	public function viewcategory()
	{
		$this->checkloginadmin();
		$category = $this->Admin_model->getallcategory();
		
		$this->load->view('admin_include/admin_header',array('active_page'=>'category','header_title'=>'Category')); 
		$this->load->view('admin_include/admin_sidebar'); 
		$this->load->view('admin/viewcategory',array('category'=>$category));
		$this->load->view('admin_include/admin_footer'); 
	}
	
	public function addcategory()
	{
		$this->checkloginadmin();
		$category=array();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$category = $this->Admin_model->getcategoryfromid($_GET['id']); 
			if(!empty($_POST)){
				$this->Admin_model->update("category",array("name"=>$_POST['name']),array('id'=>$_GET['id']));
				 $this->session->set_flashdata('message_success', 'Category Updated Successfully.!', 5);
				redirect("admin/viewcategory");
			}
		}
		if(!empty($_POST)){
			$_POST['createdat'] = date("Y-m-d h:i:s");
			$this->Admin_model->insert("category",$_POST);
			
			 $this->session->set_flashdata('message_success', 'Category add Successfully.!', 5);
			redirect("admin/addcategory");
		}
		$this->load->view('admin_include/admin_header',array('active_page'=>'category','header_title'=>'Category')); 
		
		$this->load->view('admin_include/admin_sidebar'); 
		
		$this->load->view('admin/addcategory',array('category'=>$category));
		$this->load->view('admin_include/admin_footer'); 
	}
	
	public function deletecategory()
	{
		$this->checkloginadmin();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$this->Admin_model->update("category",array('isdeleted'=>'1'),array('id'=>$_GET['id']));
			$this->session->set_flashdata('message_success', 'Category Deleted Successfully.!', 5);
			redirect("admin/viewcategory");
		}
	}
	
	
	public function viewquestions()
	{
		$this->checkloginadmin();
		$getallquestions = $this->Admin_model->getallquestions();
		
		$this->load->view('admin_include/admin_header',array('active_page'=>'question','header_title'=>'question')); 
		$this->load->view('admin_include/admin_sidebar'); 
		$this->load->view('admin/viewquestions',array('getallquestions'=>$getallquestions));
		$this->load->view('admin_include/admin_footer'); 
	}
	
	public function addquestions()
	{
		$this->checkloginadmin();
		$category=$this->Admin_model->getallcategory();
		$question = array();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$question = $this->Admin_model->getquestionfromid($_GET['id']); 
			if(!empty($_POST)){
				
				$this->Admin_model->update("question",$_POST,array('id'=>$_GET['id']));
				 $this->session->set_flashdata('message_success', 'Question Updated Successfully.!', 5);
				redirect("admin/viewquestions");
			}
		}
		if(!empty($_POST)){
			$_POST['createdat'] = date("Y-m-d h:i:s");
			$this->Admin_model->insert("question",$_POST);
			
			 $this->session->set_flashdata('message_success', 'Question add Successfully.!', 5);
			redirect("admin/addquestions");
		}
		$this->load->view('admin_include/admin_header',array('active_page'=>'question','header_title'=>'question')); 
		
		$this->load->view('admin_include/admin_sidebar'); 
		
		$this->load->view('admin/addquestions',array('category'=>$category,'question'=>$question));
		$this->load->view('admin_include/admin_footer'); 
	}
	
	public function deletequestion()
	{
		$this->checkloginadmin();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$this->Admin_model->update("question",array('isdeleted'=>'1'),array('id'=>$_GET['id']));
			$this->session->set_flashdata('message_success', 'Question Deleted Successfully.!', 5);
			redirect("admin/viewquestions");
		}
	}
	
	public function adduser()
	{
		$this->checkloginadmin();
		$category=$this->Admin_model->getallcategory();
		$user = array();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$user = $this->Admin_model->getuserfromid($_GET['id']); 
			if(!empty($_POST)){
				
				$this->Admin_model->update("users",$_POST,array('id'=>$_GET['id']));
				 $this->session->set_flashdata('message_success', 'User Updated Successfully.!', 5);
				redirect("admin/viewuser");
			}
		}
		if(!empty($_POST)){
			$_POST['role']=2;
			$_POST['createdat'] = date("Y-m-d h:i:s");
			$this->Admin_model->insert("users",$_POST);
			
			 $this->session->set_flashdata('message_success', 'User add Successfully.!', 5);
			redirect("admin/adduser");
		}
		$this->load->view('admin_include/admin_header',array('active_page'=>'user','header_title'=>'Users')); 
		
		$this->load->view('admin_include/admin_sidebar'); 
		
		$this->load->view('admin/adduser',array('category'=>$category,'user'=>$user));
		$this->load->view('admin_include/admin_footer'); 
	}
	
	public function viewuser()
	{
		$this->checkloginadmin();
		$getallusers = $this->Admin_model->getallusers();
		
		$this->load->view('admin_include/admin_header',array('active_page'=>'user','header_title'=>'Users')); 
		$this->load->view('admin_include/admin_sidebar'); 
		$this->load->view('admin/viewuser',array('getallusers'=>$getallusers));
		$this->load->view('admin_include/admin_footer'); 
	}
	
	public function deleteuser()
	{
		$this->checkloginadmin();
		if(isset($_GET['id']) && $_GET['id']!=""){
			$this->Admin_model->update("users",array('isdeleted'=>'1'),array('id'=>$_GET['id']));
			$this->session->set_flashdata('message_success', 'User Deleted Successfully.!', 5);
			redirect("admin/viewuser");
		}
	}
	
	public function addpage()
	{
		if(isset($_POST['page_title'])){
			$result = $this->Admin_model->addpage($_POST);
		}
		
		$status = $this->Admin_model->get_all_status();
		$language = $this->Admin_model->get_all_language();
		$this->load->view('admin/add_page',array('status'=>$status,'language'=>$language));
	}
	
	public function update($id){
		if(isset($_POST['page_title'])){
			$result = $this->Admin_model->updatepage($_POST,$id);
		}
		$pagedetails = $this->Admin_model->getpage_by_id($id);
		$status = $this->Admin_model->get_all_status();
		$language = $this->Admin_model->get_all_language();
		$this->load->view('admin/add_page',array('status'=>$status,'language'=>$language,'pagedetails'=>$pagedetails));
	}
	
	public function allpage()
	{
		$pages = $this->Admin_model->get_all_pages();
		$this->load->view('admin/all_page',array('pages'=>$pages));
	}
	
	 public function logout() {

        $this->session->sess_destroy();

        redirect(base_url());

    }
	
	public function users(){
		$activeuser = $this->Admin_model->get_user_by_where("status","1");
		$pendinguser = $this->Admin_model->get_user_by_where("status","0");
		
		$this->load->view('admin_include/admin_header'); 
		
		$this->load->view('admin_include/admin_sidebar'); 
		
		$this->load->view('admin/user/all_user',array('activeuser'=>$activeuser,'pendinguser'=>$pendinguser));
	}
	
	public function sendcoin($id)
	{
		if(isset($_POST['coin'])){
			
			if($_POST['coin'] == "" || $_POST['coin'] <= 0){
				
				$this->session->set_flashdata('message', 'Enter Valid Coins', 5);

                redirect("admin/sendcoin/".$id);
			}else{
				
				$result = $this->Admin_model->sendcoin($id,$_POST['coin']);
				
				$this->session->set_flashdata('message_success', 'Coin Send Successfully!', 5);

                redirect("admin/sendcoin/".$id);
			}
		}
		$userdetails = $this->Admin_model->get_user_by_where("user_id",$id);
		
		$this->load->view('admin_include/admin_header'); 
		
		$this->load->view('admin_include/admin_sidebar'); 
		
		$this->load->view('admin/user/sendcoin',array('userdetails'=>$userdetails));
	}
}
