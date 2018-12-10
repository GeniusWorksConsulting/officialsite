<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Page_model extends CI_Model
{
    public function login()
    {
        $user_name = $this->input->post('email');
        $password  = $this->input->post('password');
		
        $this->db->where('email', $user_name);
        $data = $this->db->get('users');
        $data = $data->num_rows();
		
        if ($data != 0) {
            $this->db->where('email', $user_name);
            $this->db->where('password', $password);
            $data   = $this->db->get('users');
            $result = $data->result();
            $data   = $data->num_rows();
			//print_r($data);
		//exit;
            if ($data != 0) {
                
                $user_id = $result[0]->id;
                $role_id = $result[0]->role;
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('role', $role_id);
                $this->session->set_userdata('user_name', $result[0]->email);
				$this->session->set_userdata('password', $result[0]->password);
				$this->session->set_userdata('group_name', $result[0]->group_name);
				$this->session->set_userdata('first_name', $result[0]->first_name);
                return 1;
            } else {
                $this->session->set_flashdata('incorrect_info', 'You have provided invalid username or password!', 5);
                redirect("login");
            }
        } else {
            $this->session->set_flashdata('incorrect_info', 'You have provided invalid username or password!', 5);
            redirect("login");
        }
    }
    
    public function register($post)
    {
        $result    = $this->db->insert("users", $post);
        $insert_id = $this->db->insert_id();
        $result2   = $this->db->insert("user_coins", array(
            "user_id" => $insert_id
        ));
        return $result;
    }
	
	public function changepassword()
	{
		$this->db->where('id',$_SESSION['user_id']);
		$this->db->update('users',array('password'=>$_POST['new_password'])); 
		
		$this->session->set_flashdata('message_success', 'Password is successfully Changed!', 5);
		$this->session->set_userdata('password', $_POST['new_password']);
		redirect("changepassword");
	}
	
    public function check_username_exists($username)
    {
        $query  = $this->db->get_where('users', array(
            'user_name' => $username
        ));
        $result = $query->row_array();
        if (empty($result)) {
            return true;
        } else {
            return false;
        }
    }
    public function check_mobilenumber_exists($mobile)
    {
        $query  = $this->db->get_where('users', array(
            'mobile' => $mobile
        ));
        $result = $query->row_array();
        if (empty($result)) {
            return true;
        } else {
            return false;
        }
    }
    public function check_email_exists($email)
    {
        $query  = $this->db->get_where('users', array(
            'email' => $email
        ));
        $result = $query->row_array();
        if (empty($result)) {
            return 1;
        } else {
            return 0;
        }
    }
	
	public function check_user_by_salt($salt)
	{
		$query  = $this->db->get_where('users', array(
            'salt' => $salt
        ));
        $result = $query->row_array();
		return $result;
	}
	
	public function weeklyentry()
	{
		
		//$this->db->where('month', "01");
		//$this->db->delete('week_entry'); 
		
		//  Start of weekly entry 
		//$mondaydate = date("d-m-Y", strtotime("first monday ".date('Y-m').""));
		$mondaydate = date("d-m-Y", strtotime("first monday of this month"));
		//echo $mondaydate;
		//exit;
		//$mondaydate = date("d-m-Y", strtotime("first monday 2018-03"));
		//echo $mondaydate;
		//exit;
		//$mondaydate = "01-01-2018";
		$currentmonth = date("m",strtotime($mondaydate));
		//echo $currentmonth; exit;
		$nextmonth="";
		$j=1;
		for($i=1;$i<7;$i++){
			$fromdate = $mondaydate;
			$mondaydate = date("d-m-Y",strtotime("+7 day",strtotime($mondaydate)));
			$nextmonth = date("m",strtotime($mondaydate));
			$insert_month = date("m",strtotime($mondaydate));
			$insert_year = date("Y",strtotime($mondaydate));
			if($currentmonth!=$nextmonth){
				//echo $mondaydate;
				$mondaydate = date("d-m-Y",strtotime("-7 day",strtotime($mondaydate)));
				$remainingdays = strtotime($mondaydate);
				//echo $remainingdays;
				$remainingdays = (int)date('t', $remainingdays) - (int)date('j', $remainingdays)+1;
				//echo "Remaining: ".$remainingdays;
				if($remainingdays>3){
					
					$insert_month = date("m",strtotime($mondaydate));
					$insert_year = date("Y",strtotime($mondaydate));
					$mondaydate = date("d-m-Y",strtotime("+7 day",strtotime($mondaydate)));
					
					$i=6;
				}else{
					//echo 'this is next month:';
					$mondaydate = date("d-m-Y",strtotime("+7 day",strtotime($mondaydate)));
					
					$insert_month = date("m",strtotime($mondaydate));
					$insert_year = date("Y",strtotime($mondaydate));
					$j=1;
					$i=6;
				}
			}
			if($j==1 && $i==1){
				$previous_day = date("d", strtotime("first monday of this month"));
				//$previous_day = date("d", strtotime("first monday 2018-03"));
				if($previous_day == 5){
					$j=2;
				}
			}
			//echo $j." ".$insert_month."$insert_year";
			$week_entry_check = $this->Admin_model->check_week_entry($j,$insert_month,$insert_year);
			//print_R($week_entry_check);
			if(empty($week_entry_check)){
				$todate = date("d-m-Y",strtotime("-1 day",strtotime($mondaydate)));
				$data=array("week"=>$j,
							"month"=>$insert_month,
							"year"=>$insert_year,
							"from_date"=>date("Y-m-d",strtotime($fromdate)),
							"to_date"=>date("Y-m-d",strtotime($todate)));
				//print_r($data);
				$this->Admin_model->insert("week_entry",$data);
			}
			else{
				break;
			}
			$j++;
		}
		//exit;
		// End of weekly entry	
	}
}