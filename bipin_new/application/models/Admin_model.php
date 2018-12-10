<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function login() {
        $user_name = $this->input->post('user_name');
        $password = $this->input->post('password');
        $this->db->where('email', $user_name);
        $data = $this->db->get('users');
        $data = $data->num_rows();
        if ($data != 0) {
            $this->db->where('email', $user_name);
            $this->db->where('password', $password);
            $data = $this->db->get('users');
            $result = $data->result();
            $data = $data->num_rows();

            if ($data != 0) {
                $user_id = $result[0]->id;
                $user_email = $result[0]->email;
                $user_phoneno = $result[0]->phoneno;
                $role_id = $result[0]->role;
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('user_email', $user_email);
                $this->session->set_userdata('user_phoneno', $user_phoneno);
                $this->session->set_userdata('user_role', $role_id);
                $this->session->set_userdata('user_name', $result[0]->user_name);
                $this->session->set_userdata('group_name', $result[0]->group_name);
                $this->session->set_userdata('first_name', $result[0]->first_name);
                return 1;
            } else {
                $this->session->set_flashdata('incorrect_info', 'You have provided invalid username or password!', 5);
                redirect("admin");
            }
        } else {
            $this->session->set_flashdata('incorrect_info', 'You have provided invalid username or password!', 5);
            redirect("admin");
        }
    }

    public function insert($table, $value) {
        $data = $this->db->insert($table, $value);
        $insertId = $this->db->insert_id();
        return $insertId;
    }

    public function update($table, $data, $where) {
        foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
        $data = $this->db->update($table, $data);
        return $data;
    }
	public function delete_data($table,$where)
	{
		foreach ($where as $key => $value) {
            $this->db->where($key, $value);
        }
		
		$this->db->delete($table);
	}
    public function getallcategory() {
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where("isdeleted=0");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getnewallcategory($type) {
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where("isdeleted=0");
        $this->db->where("type= '" . $type . "'");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getcategoryfromid($id) {
        $this->db->select('*');
        $this->db->from('category');
        $this->db->where("id = '" . $id . "'");
        $this->db->where("isdeleted=0");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getcategoryfromquestion($id) {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where("id = '" . $id . "'");
        $this->db->where("isdeleted=0");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function check_week_entry($week, $month, $year) {
        $this->db->select('*');
        $this->db->from('week_entry');
        $this->db->where("week='" . $week . "'");
        $this->db->where("month='" . $month . "'");
        $this->db->where("year='" . $year . "'");
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }

    public function getcurrentweek() {
        $this->db->select('*');
        $this->db->from('week_entry');
        $this->db->where("from_date<='" . date("Y-m-d") . "'");
        $this->db->where("to_date>='" . date("Y-m-d") . "'");
        $query = $this->db->get();
        $row = $query->row();
        //echo 'sanjay';
        if (!empty($row)) {
            //return $row;
        } else {
            $row = array();
        }
        //print_r($row);
        //exit;
        return $row;
    }

    public function get_all_week() {
        $this->db->select('*');
        $this->db->from('week_entry');
        $this->db->order_by('id', "asc");
        $query = $this->db->get();
        $row = $query->result_array();
        //echo 'sanjay';
        if (!empty($row)) {
            //return $row;
        } else {
            $row = array();
        }
        // print_r($row);
        // exit;
        return $row;
    }

    public function check_week_goal_entry($week, $month, $year) {
        $this->db->select('*');
        $this->db->from('weekly_goal');
        $this->db->where("week='" . $week . "'");
        $this->db->where("month='" . $month . "'");
        $this->db->where("year='" . $year . "'");
        $this->db->where("user_id='" . $this->session->user_id . "'");
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }

    public function check_month_goal_entry() {
        $this->db->select('*');
        $this->db->from('monthly_goal');
        $this->db->where("month='" . date("m") . "'");
        $this->db->where("year='" . date("Y") . "'");
        $this->db->where("user_id='" . $this->session->user_id . "'");
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }

    public function get_squad_monthly_goal() {
        $this->db->select('*');
        $this->db->from('monthly_goal');
        $this->db->where("month='" . date("m") . "'");
        $this->db->where("year='" . date("Y") . "'");
        $this->db->where("group_name='" . $this->session->group_name . "'");
        $query = $this->db->get();
        $row = $query->result_array();
        $value = 0;
        for ($i = 0; $i < sizeof($row); $i++) {
            $value+=$row[$i]['monthly_goal'];
        }
        if ($value == 0) {
            return 0;
        }
        return ceil($value / sizeof($row));
    }

    public function getallweekdetails() {
        $currentweek = $this->getcurrentweek();
        if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
            $currentweek->week = $_GET['week'];
            $currentweek->month = $_GET['month'];
            $currentweek->year = $_GET['year'];
        }
        $this->db->select('*');
        $this->db->from('week_entry');
        $this->db->where("month='" . $currentweek->month . "'");
        $this->db->where("year='" . $currentweek->year . "'");
        $query = $this->db->get();
        $row = $query->result_array();
        //print_r($row);
        
        for ($i = 0; $i < sizeof($row); $i++) {
            $week = $row[$i]['week'];
            $month = $row[$i]['month'];
            $year = $row[$i]['year'];
            $current_week_paused_account = $this->Admin_model->get_current_paused_account($week, $month, $year);
            //$mygroup = $this->getcurrentgroupmember("", $current_week_paused_account);
            // $currentweek = $this->getcurrentweek();
            // $qamember = $this->getallqamember();
            // $groupmember = array();
            $mygroup =0;    
            $row[$i]['details'] = 0;
            for ($j = 0; $j < sizeof($mygroup); $j++) {
                $receiver_id = $mygroup[$j]['id'];
                $row[$i]['details'] += $this->memberweeklyrating($week, $month, $year, $receiver_id);
            }
            $row[$i]['details'] = ceil($row[$i]['details'] / sizeof($mygroup));
        }
        echo '<br/>';
        echo '<br/>';
        //print_r($row);
        //exit;
        return $row;
    }

    public function getallqaweekdetails() {
        $this->db->select('*');
        $this->db->from('week_entry');
        $this->db->where("month='" . date("m") . "'");
        $this->db->where("year='" . date("Y") . "'");
        $query = $this->db->get();
        $row = $query->result_array();

        for ($i = 0; $i < sizeof($row); $i++) {

            $row[$i]['details'] = $this->mytribeforoneweekqa($row[$i]['week'], $row[$i]['month'], $row[$i]['year']);
        }
        return $row;
    }

    public function getstarttoendweekdetails_graph() {

        $query = $this->db->query("SELECT * FROM (
    SELECT * FROM week_entry ORDER BY id DESC LIMIT 8
) sub
ORDER BY id ASC");
        $row = $query->result_array();

        return $row;
    }

    public function mytribeforoneweekqa($week, $month, $year) {
        $mygroup = $this->getcurrentgroupmember();
        $currentweek = $this->getcurrentweek();
        $qamember = $this->getallqamember();
        $groupmember = array();
        for ($i = 0; $i < sizeof($mygroup); $i++) {
            $receiver_id = $mygroup[$i]['id'];

            for ($j = 0; $j < sizeof($qamember); $j++) {
                $sender_id = $qamember[$j]['id'];
                // echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                $text = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "text");
                $voice = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "voice");
                $mainpercentage = 0;
                $count = 0;
                if ($text != "") {
                    $mainpercentage += $text;
                    $count++;
                }
                if ($voice != "") {
                    $mainpercentage += $voice;
                    $count++;
                }
                if ($count != 0) {
                    //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
                    //echo $groupmember[$i][$j]['assessed_percentage'];
                    array_push($groupmember, ceil($mainpercentage / $count));
                }
                //echo "</br>";
            }
        }

        $returnmember = array();
        if (empty($groupmember)) {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
            $returnmember['percentage'] = 0;
        } else {
            $returnmember['percentage'] = ceil(array_sum($groupmember) / sizeof($groupmember));
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
        }
        return $returnmember;
    }

    public function get_squad_weekly_goal($week, $month, $year) {
        $this->db->select('*');
        $this->db->from('weekly_goal');
        $this->db->where("week='" . $week . "'");
        $this->db->where("month='" . $month . "'");
        $this->db->where("year='" . $year . "'");
        $this->db->where("group_name='" . $this->session->group_name . "'");
        $query = $this->db->get();
        $row = $query->result_array();
        $value = 0;
        for ($i = 0; $i < sizeof($row); $i++) {
            $value+=$row[$i]['weekly_goal'];
        }
        if ($value == 0) {
            return 0;
        }
        return ceil($value / sizeof($row));
    }

    public function getcurrentgroupmember($groupname = null, $paused_account = array(), $permanent_paused_account = array()) {
        if ($groupname == "") {
            $groupname = $this->session->group_name;
        }
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("group_name='" . $groupname . "'");
        $this->db->where("role=2");
        if (!empty($paused_account)) {
            for ($i = 0; $i < sizeof($paused_account); $i++) {
                $this->db->where("id!='" . $paused_account[$i]['user_id'] . "'");
            }
        }
        if (!empty($permanent_paused_account)) {
            for ($i = 0; $i < sizeof($permanent_paused_account); $i++) {
                $this->db->where("id!='" . $permanent_paused_account[$i]['id'] . "'");
            }
        }
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getothergroupmember($paused_account = array(), $permanent_paused_account = array()) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("group_name!='" . $this->session->group_name . "'");
        $this->db->where("group_name!=3");
        $this->db->where("role=2");
        if (!empty($paused_account)) {
            for ($i = 0; $i < sizeof($paused_account); $i++) {
                $this->db->where("id!='" . $paused_account[$i]['user_id'] . "'");
            }
        }
        if (!empty($permanent_paused_account)) {
            for ($i = 0; $i < sizeof($permanent_paused_account); $i++) {
                $this->db->where("id!='" . $permanent_paused_account[$i]['id'] . "'");
            }
        }
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getalltextareaquestion($type, $sender_id, $receiver_id) {
        $this->db->select('*');
        $this->db->from('category');
        $query = $this->db->get();
        $row = $query->result_array();
        $myarray = array();
        for ($i = 0; $i < sizeof($row); $i++) {
            $this->db->select('*');
            $this->db->from('rating');
            $this->db->where("category_id='" . $row[$i]['id'] . "'");
            $this->db->where("type='" . $type . "'");
            $this->db->where("sender_id='" . $sender_id . "'");
            $this->db->where("receiver_id='" . $receiver_id . "'");
            $this->db->where("text_rating!=''");
            $query = $this->db->get();
            $row2 = $query->result_array();

            if (!empty($row2)) {
                //print_r($row2);
                $myarray[$row2[0]['category_id']] = $row2[0]['text_rating'];
            }
        }
        return $myarray;
    }

    public function getfilename($week, $month, $year, $type, $receiver_id, $sender_id) {
        $this->db->select('*');
        $this->db->from('rating');
        $this->db->where("type='" . $type . "'");
        $this->db->where("receiver_id='" . $receiver_id . "'");
        $this->db->where("sender_id='" . $sender_id . "'");
        $this->db->where("file_name!=''");
        $query = $this->db->get();
        //echo $this->db->last_query();
        $row2 = $query->result_array();
        return $row2;
    }

    public function check_account_paused($week, $month, $year) {
        $this->db->select('*');
        $this->db->from('pause');
        $this->db->where("week='" . $week . "'");
        $this->db->where("month='" . $month . "'");
        $this->db->where("year='" . $year . "'");
        $this->db->where("user_id='" . $this->session->user_id . "'");
        $this->db->where("status=0");
        $query = $this->db->get();
        //echo $this->db->last_query();
        $row2 = $query->result_array();
        return $row2;
    }

    public function check_account_paused_in_previous_month() {
        $this->db->select('*');
        $this->db->from('pause');
        $this->db->where("user_id='" . $this->session->user_id . "'");
        $this->db->where("createdat>='" . date("Y-m-d H:i:s", strtotime("-14 days")) . "'");
        $this->db->where("status=0");
        $query = $this->db->get();
        //echo $this->db->last_query();
        $row2 = $query->result_array();
        return $row2;
    }

    public function getrating($week, $month, $year, $sender_id, $receiver_id, $type) {
        $this->db->select('rating.*,
							question.description as description,
							category.name as category_name');
        $this->db->from('rating');
        $this->db->join("question", "rating.question_id = question.id");
        $this->db->join("category", "rating.category_id = category.id");
        $this->db->where("rating.week", $week);
        $this->db->where("rating.month", $month);
        $this->db->where("rating.year", $year);
        $this->db->where("rating.type", $type);
        $this->db->where("rating.sender_id", $sender_id);
        $this->db->where("rating.receiver_id", $receiver_id);
        $this->db->where("rating.file_name is null");
        $query = $this->db->get();
        $row = $query->result_array();
        /* for($i=0;$i<sizeof($row);$i++)
          {
          $question = $question = $this->getcategoryfromquestion($row[$i]['question_id']);
          //if(!isset($question[0])){ print_r($question); print_r($row); exit; }
          $row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id'])[0]['name'];
          $row[$i]['description'] = $question[0]['description'];
          } */
        // if(!empty($row)){
        // echo '<pre>';
        // print_r($row);
        // exit;
        // }
        return $row;
    }

    public function getratingpercentage($week, $month, $year, $sender_id, $receiver_id, $type) {
        //echo $week."=".$month."=".$year."=".$sender_id."=".$receiver_id."=".$type."</br>";
        $this->db->select('rating.id,
							rating.sender_id,
							rating.receiver_id,
							rating.type,
							rating.question_id,
							rating.category_id,
							AVG(rating.rating) as rating,
							rating.text_rating,
							rating.file_name', 'rating.week', 'rating.month', 'rating.year', 'rating.createdat');
        $this->db->from('rating');
        $this->db->where("week=" . $week);
        $this->db->where("month=" . $month);
        $this->db->where("year=" . $year);
        $this->db->where("type='" . $type . "'");
        $this->db->where("sender_id=" . $sender_id);
        $this->db->where("receiver_id=" . $receiver_id);
        $this->db->where("file_name is null");
        $this->db->group_by(array('rating.category_id'));
        $query = $this->db->get();
        $row = $query->result_array();
        // if($receiver_id==15){
        // echo $this->db->last_query(); exit;
        // }
        $heading = "";
        $j = 0;
        $total_percent = 0;
        $total_percent_array = array();

        /* for($i=0;$i<sizeof($row);$i++)
          {
          if($heading!=$row[$i]['category_id']){
          $heading = $row[$i]['category_id'];
          if($i!=0){
          //echo "per==".$total_percent/$j."==</br>";
          array_push($total_percent_array,ceil($total_percent/$j));
          }
          $j=0;
          //echo $j."===";
          $total_percent=0;
          }
          if($row[$i]['rating']!=""){ $j++; }
          //$j++; comment this line and add above line.
          if(sizeof($row)-1 == $i){
          $total_percent+=$row[$i]['rating'];

          array_push($total_percent_array,ceil($total_percent/$j));
          }
          if($row[$i]['rating']!=""){
          $total_percent+=$row[$i]['rating'];
          }
          //echo $total_percent."</br>";
          }
          if(empty($total_percent_array)){
          return 0;
          }

          return ceil(array_sum($total_percent_array)/sizeof($total_percent_array)); */
        if (!empty($row)) {
            $total_percent_array = 0;
            for ($i = 0; $i < sizeof($row); $i++) {
                $total_percent_array += $row[$i]['rating'];
            }

            return ceil($total_percent_array / sizeof($row));
        } else {
            return 0;
        }
        // print_r($total_percent_array);
        // exit;
        // return $row;
    }

    public function memberweeklyrating($week, $month, $year, $receiver_id, $mygroupname = null) {
        if ($mygroupname == "") {
            $mygroup = $this->getcurrentgroupmember();
        } else {
            $mygroup = $this->getcurrentgroupmember($mygroupname);
        }
        $currentweek = $this->getcurrentweek();
        $groupmember = array();
        for ($i = 0; $i < sizeof($mygroup); $i++) {
            $sender_id = $mygroup[$i]['id'];

            $text = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "text");
            $voice = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "voice");

            $mainpercentage = 0;
            $count = 0;
            if ($text != "") {
                $mainpercentage += $text;
                $count++;
            }
            if ($voice != "") {
                $mainpercentage += $voice;
                $count++;
            }
            if ($count != 0) {
                //echo 'text='.$text.' mainpercentage='.$mainpercentage/$count.'</br>';
                array_push($groupmember, ceil($mainpercentage / 2));
            }
        }

        $returnmember = array();
        if (empty($groupmember)) {
            $returnmemberpercentage = 0;
        } else {
            $returnmemberpercentage = ceil(array_sum($groupmember) / sizeof($mygroup));
        }
        $getqarating = $this->getqarating($receiver_id, $week, $month, $year, '49');
        if ($getqarating != 0) {
            if ($returnmemberpercentage != 0) {
                $returnmemberpercentage = ceil(($returnmemberpercentage + $getqarating) / 2);
            } else {
                $returnmemberpercentage = ceil($returnmemberpercentage + $getqarating);
            }
        }
        // echo $returnmemberpercentage;
        // echo '</br>';
        return $returnmemberpercentage;
    }

    public function memberweeklycompleted($week, $month, $year, $receiver_id, $mygroupname = null) {
        if ($mygroupname == "") {
            $mygroup = $this->getcurrentgroupmember();
        } else {
            $mygroup = $this->getcurrentgroupmember($mygroupname);
        }
        $currentweek = $this->getcurrentweek();
        $groupmember = array();
        for ($i = 0; $i < sizeof($mygroup); $i++) {
            $sender_id = $mygroup[$i]['id'];

            $text = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "text");
            $voice = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "voice");

            $mainpercentage = 0;
            $count = 0;
            if ($text != "") {
                $mainpercentage += $text;
                $count++;
            }
            if ($voice != "") {
                $mainpercentage += $voice;
                $count++;
            }
            if ($count == 2) {
                //echo 'text='.$text.' mainpercentage='.$mainpercentage/$count.'</br>';
                array_push($groupmember, ceil($mainpercentage / 2));
            }
        }

        return sizeof($groupmember);
    }

    public function mytribeforoneweek($week, $month, $year) {
        $mygroup = $this->getcurrentgroupmember();
        $currentweek = $this->getcurrentweek();
        $groupmember = array();
        for ($i = 0; $i < sizeof($mygroup); $i++) {
            $sender_id = $mygroup[$i]['id'];

            for ($j = 0; $j < sizeof($mygroup); $j++) {
                $receiver_id = $mygroup[$j]['id'];
                // echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                $text = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "text");
                $voice = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "voice");
                $mainpercentage = 0;
                $count = 0;
                if ($text != "") {
                    $mainpercentage += $text;
                    $count++;
                }
                if ($voice != "") {
                    $mainpercentage += $voice;
                    $count++;
                }
                if ($count != 0) {
                    //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
                    //echo $groupmember[$i][$j]['assessed_percentage'];
                    array_push($groupmember, ceil($mainpercentage / $count));
                }
                //echo "</br>";
            }
        }

        $returnmember = array();
        if (empty($groupmember)) {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
            $returnmember['percentage'] = 0;
        } else {
            $returnmember['percentage'] = ceil(array_sum($groupmember) / sizeof($groupmember));
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
        }
        return $returnmember;
    }

    public function getmycompleted($week, $month, $year, $paused_account = array(), $getmycompleted = array()) {
        $mygroup = $this->getcurrentgroupmember("", $paused_account, $getmycompleted);
        $currentweek = $this->getcurrentweek();
        $groupmember = array();
        for ($i = 0; $i < 1; $i++) {
            $sender_id = $this->session->user_id;

            for ($j = 0; $j < sizeof($mygroup); $j++) {
                $receiver_id = $mygroup[$j]['id'];
                // echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                $text = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "text");
                $voice = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "voice");
                $mainpercentage = 0;
                $count = 0;
                if ($text != "") {
                    $mainpercentage += $text;
                    $count++;
                }
                if ($voice != "") {
                    $mainpercentage += $voice;
                    $count++;
                }
                if ($count != 0) {
                    //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
                    //echo $groupmember[$i][$j]['assessed_percentage'];
                    array_push($groupmember, ceil($mainpercentage / 2));
                }
                //echo "</br>";
            }
        }

        $returnmember = array();
        if (empty($groupmember)) {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
            $returnmember['percentage'] = 0;
        } else {
            $returnmember['percentage'] = ceil(array_sum($groupmember) / sizeof($groupmember));
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
        }
        return $returnmember;
    }

    public function getusercompleted($week, $month, $year, $mygroup, $sender_id) {
        $mygroup = $this->getcurrentgroupmember($mygroup);
        $currentweek = $this->getcurrentweek();
        $groupmember = array();
        for ($i = 0; $i < 1; $i++) {
            $sender_id = $sender_id;

            for ($j = 0; $j < sizeof($mygroup); $j++) {
                $receiver_id = $mygroup[$j]['id'];
                // echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                $text = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "text");
                $voice = $this->getratingpercentage($week, $month, $year, $sender_id, $receiver_id, "voice");
                $mainpercentage = 0;
                $count = 0;

                if ($text != "") {
                    $mainpercentage += $text;
                    $count++;
                }
                if ($voice != "") {
                    $mainpercentage += $voice;
                    $count++;
                }
                if ($count != 0) {
                    //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
                    //echo $groupmember[$i][$j]['assessed_percentage'];
                    array_push($groupmember, ceil($mainpercentage / $count));
                }
                //echo "</br>";
            }
        }

        $returnmember = array();
        if (empty($groupmember)) {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
            $returnmember['percentage'] = 0;
        } else {
            $returnmember['percentage'] = ceil(array_sum($groupmember) / sizeof($groupmember));
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
            //print_r($returnmember); exit;
        }
        return $returnmember;
    }

    public function mytribe($mygroupname = null, $paused_account = array(), $permanent_paused_account = array()) {
        if ($mygroupname == "") {
            $mygroup = $this->getcurrentgroupmember($mygroupname, $paused_account, $permanent_paused_account);
        } else {
            $mygroup = $this->getcurrentgroupmember($mygroupname, $paused_account, $permanent_paused_account);
        }
		//print_r($mygroup);
        
        $currentweek = $this->getcurrentweek();
        if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
            $currentweek->week = $_GET['week'];
            $currentweek->month = $_GET['month'];
            $currentweek->year = $_GET['year'];
        }
        $groupmember = array();
        $qamember = $this->getallqamember();
        $whichmember = array();
        $completemember = 0;
        $percent = 0;

		
        //print_r($mygroup);
		
        /*for ($i = 0; $i < sizeof($mygroup); $i++) {
            $receiver_id = $mygroup[$i]['id'];
            $rating = $this->memberweeklyrating($currentweek->week, $currentweek->month, $currentweek->year, $receiver_id, $mygroup[$i]['group_name']);
            //echo $rating.'</br>';
            $percent += $rating;
            if ($rating != 0) {
                $completemember++;
            }
            // $mynull = 0; //for calculate total member and add one in complete member
            // $totalpercent=0;
            // for($j=0;$j<sizeof($mygroup);$j++){
            // $receiver_id = $mygroup[$j]['id'];
            // // echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
            // // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
            // // echo "</br>";
            // // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
            // $text =$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
            // $voice = $this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
            // $mainpercentage=0;
            // $count=0;
            // if($text!=""){
            // $mainpercentage += $text;
            // $count++;
            // }else{
            // $mynull = 1;
            // }
            // if($voice!=""){
            // $mainpercentage += $voice;
            // $count++;
            // }else{
            // $mynull = 1;
            // }
            // if($count != 0){
            // //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
            // //echo $groupmember[$i][$j]['assessed_percentage'];
            // //echo ceil($mainpercentage/$count);
            // $totalpercent = ceil($mainpercentage/$count);
            // }
            // $getqarating = $this->getqarating($receiver_id,$currentweek->week,$currentweek->month,$currentweek->year,'49');
            // if($getqarating!=0){
            // $totalpercent = ceil(($totalpercent + $getqarating)/2);
            // }
            // if($totalpercent!=0){
            // array_push($groupmember,$totalpercent);	
            // }
            // }
            // // $groupmember2 = array();
            // // for($k=0;$k<sizeof($qamember);$k++){
            // // $sender_id2 = $qamember[$k]['id'];
            // // //echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
            // // // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
            // // // echo "</br>";
            // // // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
            // // $text2 =$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id2,$sender_id,"text");
            // // $voice2 = $this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id2,$sender_id,"voice");
            // // $mainpercentage2=0;
            // // $count2=0;
            // // if($text2!=""){
            // // $mainpercentage2 += $text2;
            // // $count2++;
            // // }
            // // if($voice2!=""){
            // // $mainpercentage2 += $voice2;
            // // $count2++;
            // // }
            // // if($count2 != 0){
            // // //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
            // // //echo $groupmember[$i][$j]['assessed_percentage'];
            // // $mainpercentage2 = ceil($mainpercentage2/$count2);
            // // $position = sizeof($groupmember)-1;
            // // //echo 'text='.$text2.'voice'.$voice2."__id=".$sender_id2;
            // // print_r($groupmember);
            // // echo 'position='.$groupmember[$position].'**'.$mainpercentage2;
            // // $percentage = $groupmember[$position] + $mainpercentage2;
            // // $groupmember[$position] = ceil($percentage/2);
            // // break;
            // // }
            // // //echo "</br>";
            // // }
            // //echo "</br>";
            // if($mynull==0){ $completemember++; }
        }*/
        $completemember = 0;
        /*for ($i = 0; $i < sizeof($mygroup); $i++) {
            $receiver_id = $mygroup[$i]['id'];
            $rating = $this->memberweeklycompleted($currentweek->week, $currentweek->month, $currentweek->year, $receiver_id, $mygroup[$i]['group_name']);
            //echo $rating.'</br>';
            //$percent += $rating;
            //echo $rating.'</br>';
            if ($rating != 0) {
                $completemember++;
            }
        }*/

        //echo '<pre>'; print_r($mygroup); echo '</pre>'; 
        $returnmember = array();
        // if(empty($groupmember)){ 
        // $returnmember['total_member'] = sizeof($mygroup);
        // $returnmember['complete_member'] = sizeof($groupmember);
        // $returnmember['percentage'] = 0;
        // }else{
        // echo ceil(array_sum($groupmember)/sizeof($groupmember));
        // $returnmember['percentage'] = ceil(array_sum($groupmember)/sizeof($groupmember));
        // $returnmember['total_member'] = sizeof($mygroup);
        // $returnmember['complete_member'] = $completemember;
        // }
        if ($percent != 0) {
            //echo $percent.' '.sizeof($mygroup);
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = $completemember;
            $returnmember['percentage'] = ceil($percent / sizeof($mygroup));
        } else {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = 0;
            $returnmember['percentage'] = 0;
        }
        //print_r($returnmember);
         $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = 0;
            $returnmember['percentage'] = 0;
        return $returnmember;
    }

    public function getqarating($receiver_id, $week, $month, $year, $sender_role) {
        $this->db->select('*');
        $this->db->from('rating');
        $this->db->where("week=" . $week);
        $this->db->where("month=" . $month);
        $this->db->where("year=" . $year);
        $this->db->where("receiver_id=" . $receiver_id);
        $this->db->where("file_name is null");
        $this->db->join('users', 'users.id = rating.sender_id');
        $this->db->where("users.role=" . $sender_role);
        $query = $this->db->get();
        $row = $query->result_array();
        for ($i = 0; $i < sizeof($row); $i++) {
            $question = $question = $this->getcategoryfromquestion($row[$i]['question_id']);

            $row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id'])[0]['name'];
            $row[$i]['description'] = $question[0]['description'];
        }
        $mainpercentage2 = 0;
        if (!empty($row)) {
            $text = $this->getratingpercentage($week, $month, $year, $row[0]['sender_id'], $receiver_id, "text");
            echo 'voice';
            $voice = $this->getratingpercentage($week, $month, $year, $row[0]['sender_id'], $receiver_id, "voice");

            $count2 = 0;
            if ($text != "") {
                $mainpercentage2 += $text;
                $count2++;
            }
            if ($voice != "") {
                $mainpercentage2 += $voice;
                $count2++;
            }

            if ($count2 != 0) {
                $mainpercentage2 = ceil($mainpercentage2 / $count2);
            }
        } else {
            $mainpercentage2 = 0;
        }
        return $mainpercentage2;
    }

    public function mytribeforqamember($mygroup = null) {
        if ($mygroup == "") {
            $mygroup = $this->getcurrentgroupmember();
        } else {
            $mygroup = $this->getcurrentgroupmember($mygroup);
        }
        $currentweek = $this->getcurrentweek();
        $groupmember = array();
        $qamember = $this->getallqamember();
        //print_r($qamember); exit;
        $completemember = 0;
        for ($i = 0; $i < sizeof($mygroup); $i++) {
            $receiver_id = $mygroup[$i]['id'];
            $mynull = 0; //for calculate total member and add one in complete member
            for ($j = 0; $j < sizeof($qamember); $j++) {
                $sender_id = $qamember[$j]['id'];
                // echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                $text = $this->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "text");
                $voice = $this->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "voice");
                $mainpercentage = 0;
                $count = 0;
                if ($text != "") {
                    $mainpercentage += $text;
                    $count++;
                } else {
                    $mynull = 1;
                }
                if ($voice != "") {
                    $mainpercentage += $voice;
                    $count++;
                } else {
                    $mynull = 1;
                }
                if ($count != 0) {
                    //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
                    //echo $groupmember[$i][$j]['assessed_percentage'];
                    array_push($groupmember, ceil($mainpercentage / $count));
                }

                //echo "</br>";
            }
            if ($mynull == 0) {
                $completemember++;
            }
        }

        $returnmember = array();
        if (empty($groupmember)) {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
            $returnmember['percentage'] = 0;
        } else {
            $returnmember['percentage'] = ceil(array_sum($groupmember) / sizeof($groupmember));
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = $completemember;
        }
        return $returnmember;
    }

    public function mytribeforothermember($mygroup = null) {
        if ($mygroup == "") {
            $mygroup = $this->getothergroupmember();
        } else {
            $mygroup = $this->getothergroupmember($mygroup);
        }
        $currentweek = $this->getcurrentweek();
        $groupmember = array();
        $qamember = $this->getallqamember();
        //print_r($qamember); exit;
        $completemember = 0;
        for ($i = 0; $i < sizeof($mygroup); $i++) {
            $receiver_id = $mygroup[$i]['id'];
            $mynull = 0; //for calculate total member and add one in complete member
            for ($j = 0; $j < sizeof($qamember); $j++) {
                $sender_id = $qamember[$j]['id'];
                // echo "sender_id= ".$sender_id." receiver_id =".$receiver_id."</br>";
                // echo "text=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"text");
                // echo "</br>";
                // echo "voice=".$this->getratingpercentage($currentweek->week,$currentweek->month,$currentweek->year,$sender_id,$receiver_id,"voice");
                $text = $this->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "text");
                $voice = $this->getratingpercentage($currentweek->week, $currentweek->month, $currentweek->year, $sender_id, $receiver_id, "voice");
                $mainpercentage = 0;
                $count = 0;
                if ($text != "") {
                    $mainpercentage += $text;
                    $count++;
                } else {
                    $mynull = 1;
                }
                if ($voice != "") {
                    $mainpercentage += $voice;
                    $count++;
                } else {
                    $mynull = 1;
                }
                if ($count != 0) {
                    //$groupmember[$i][$j]['assessed_percentage'] = ceil($mainpercentage/$count);
                    //echo $groupmember[$i][$j]['assessed_percentage'];
                    array_push($groupmember, ceil($mainpercentage / $count));
                }
                //echo "</br>";
            }
            if ($mynull == 0) {
                $completemember++;
            }
        }

        $returnmember = array();
        if (empty($groupmember)) {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = sizeof($groupmember);
            $returnmember['percentage'] = 0;
        } else {
            $returnmember['percentage'] = ceil(array_sum($groupmember) / sizeof($groupmember));
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = $completemember;
        }
        return $returnmember;
    }

    public function othertribe($null, $paused_account = array(), $permanent_paused_account = array()) {
        $mygroup = $this->getothergroupmember($paused_account, $permanent_paused_account);
        $currentweek = $this->getcurrentweek();
        if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
            $currentweek->week = $_GET['week'];
            $currentweek->month = $_GET['month'];
            $currentweek->year = $_GET['year'];
        }
        $qamember = $this->getallqamember();
        $groupmember = array();
        $completemember = 0;
        $percent = 0;

//        for ($i = 0; $i < sizeof($mygroup); $i++) {
//            $receiver_id = $mygroup[$i]['id'];
//            $rating = $this->memberweeklyrating($currentweek->week, $currentweek->month, $currentweek->year, $receiver_id, $mygroup[$i]['group_name']);
//            $percent += $rating;
//            if ($rating != 0) {
//                $completemember++;
//            }
//        }
        $returnmember = array();
        if ($percent != 0) {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = $completemember;
            $returnmember['percentage'] = ceil($percent / sizeof($mygroup));
        } else {
            $returnmember['total_member'] = sizeof($mygroup);
            $returnmember['complete_member'] = 0;
            $returnmember['percentage'] = 0;
        }
        $returnmember['total_member'] = sizeof($mygroup);
        $returnmember['complete_member'] = 0;
        $returnmember['percentage'] = 0;
        return $returnmember;
    }

    public function getqamember($receiver_id) {
        $currentweek = $this->Admin_model->getcurrentweek();
        if (isset($_GET['week']) && $_GET['week'] != "" && isset($_GET['month']) && $_GET['month'] != "" && isset($_GET['year']) && $_GET['year'] != "") {
            $currentweek->week = $_GET['week'];
            $currentweek->month = $_GET['month'];
            $currentweek->year = $_GET['year'];
        }
        $week = $currentweek->week;
        $month = $currentweek->month;
        $year = $currentweek->year;
        $myarray = array();
        $qamember = $this->getallqamember();
        $igiveto3man = 0;
        for ($i = 0; $i < sizeof($qamember); $i++) {
            $gettextquestionpercentage = $this->Admin_model->getratingpercentage($week, $month, $year, $qamember[$i]['id'], $receiver_id, "text");

            $getvoicequestionpercentage = $this->Admin_model->getratingpercentage($week, $month, $year, $qamember[$i]['id'], $receiver_id, "voice");

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
            if ($qamember[$i]['id'] == $this->session->user_id) {
                if ($gettextquestionpercentage != "" && $getvoicequestionpercentage != "") {
                    $igiveto3man = $igiveto3man + 1;
                }
            }

            if ($count != 0) {
                $myarray['assessed_percentage'] = ceil($mainpercentage / $count);
                if ($count == 2) {
                    $myarray['text_voice_complete'] = "1";
                    $myarray['qa_id'] = $qamember[$i]['id'];
                }
            }
        }
        $myarray['igiveto3man'] = $igiveto3man;
        return $myarray;
    }

    public function getqamember_graph($receiver_id, $week, $month, $year) {
        $myarray = array();
        $qamember = $this->getallqamember();
        $igiveto3man = 0;
        for ($i = 0; $i < sizeof($qamember); $i++) {
            $gettextquestionpercentage = $this->Admin_model->getratingpercentage($week, $month, $year, $qamember[$i]['id'], $receiver_id, "text");

            $getvoicequestionpercentage = $this->Admin_model->getratingpercentage($week, $month, $year, $qamember[$i]['id'], $receiver_id, "voice");

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
            if ($qamember[$i]['id'] == $this->session->user_id) {
                if ($gettextquestionpercentage != "" && $getvoicequestionpercentage != "") {
                    $igiveto3man = $igiveto3man + 1;
                }
            }

            if ($count != 0) {
                $myarray['assessed_percentage'] = ceil($mainpercentage / $count);
                if ($count == 2) {
                    $myarray['text_voice_complete'] = "1";
                    $myarray['qa_id'] = $qamember[$i]['id'];
                }
            }
        }
        $myarray['igiveto3man'] = $igiveto3man;
        return $myarray;
    }

    public function getallqamember() {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("role=49");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getoneuserdata($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("id=" . $id);
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getallquestions() {
        $this->db->select('*');
        $this->db->from('question');
        $this->db->where("isdeleted=0");
        $this->db->order_by("category_id");
        $query = $this->db->get();
        $row = $query->result_array();
		print_r($row);
        for ($i = 0; $i < sizeof($row); $i++) {

            $row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id'])[0]['name'];
        }

        return $row;
    }

    public function getalltextquestions() {
        $this->db->select('*');
        $this->db->from('questions');
        $this->db->where("is_deleted=0");
        $this->db->where("type='text'");
        $this->db->order_by("category_id");
        $query = $this->db->get();
        $row = $query->result_array();
		
        for ($i = 0; $i < sizeof($row); $i++) {
            $row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id'])[0]['name'];
        }

        return $row;
    }

    public function getallvoicequestions() {
        $this->db->select('*');
        $this->db->from('questions');
        $this->db->where("is_deleted=0");
        $this->db->where("type='voice'");
        $this->db->order_by("category_id");
        $query = $this->db->get();
        $row = $query->result_array();
        for ($i = 0; $i < sizeof($row); $i++) {
            $row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id'])[0]['name'];
        }

        return $row;
    }

    public function getquestionfromid($id) {
        $this->db->select('*');
        $this->db->from('questions');
        $this->db->where("que_id = '" . $id . "'");
        $this->db->where("is_deleted=0");
        $query = $this->db->get();
        $row = $query->result_array();
        for ($i = 0; $i < sizeof($row); $i++) {
            $row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id'])[0]['name'];
            //$row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id']);
        }
        //print_r($row); exit;
        return $row;
    }

    public function getallusers() {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("isdeleted=0");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function getuserfromid($id) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where("id = '" . $id . "'");
        $this->db->where("isdeleted=0");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function get_paused_account() {
        $this->db->select('*');
        $this->db->from('pause');
        $this->db->where("user_id != ''");
        $this->db->order_by("id", "DESC");
        $query = $this->db->get();
        $row = $query->result_array();
        //print_r($row);
        for ($i = 0; $i < sizeof($row); $i++) {
            //print_r();
            $data = $this->getuserfromid($row[$i]['user_id']);
            $row[$i]['name'] = $data[0]['first_name'] . " " . $data[0]['last_name'];
        }
        //print_r($row);
        //exit;
        return $row;
    }

    public function get_current_paused_account($week, $month, $year) {
        $this->db->select('*');
        $this->db->from('pause');
        $this->db->where('week="' . $week . '"');
        $this->db->where('month="' . $month . '"');
        $this->db->where('year="' . $year . '"');
        $this->db->where('status="0"');
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function resume_account($id) {
        $this->Admin_model->update("pause", array('status' => '1'), array('id' => $id));
    }

    public function get_permanent_paused_account($week, $month, $year) {
        $this->db->select('*');
        $this->db->from('week_entry');
        $this->db->where('week="' . $week . '"');
        $this->db->where('month="' . $month . '"');
        $this->db->where('year="' . $year . '"');
        $query = $this->db->get();
        $row = $query->result_array();

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('delete_date<="' . $row[0]['to_date'] . '"');
        $query = $this->db->get();
        $row = $query->result_array();

        return $row;
    }

    public function get_percentage_gap_message($week, $month, $year, $sender_id, $receiver_id) {
        $this->db->select('*');
        $this->db->from('message');
        $this->db->where('week="' . $week . '"');
        $this->db->where('month="' . $month . '"');
        $this->db->where('year="' . $year . '"');
        $this->db->where('(sender_user_id="' . $sender_id . '" or sender_user_id="' . $receiver_id . '")');
        $this->db->where('(receiver_user_id="' . $sender_id . '" or receiver_user_id="' . $receiver_id . '")');
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }

    public function GetAllNewQuestions($category_id = "") {
        $this->db->select('*');
        $this->db->from('questions');
        $this->db->where("is_deleted=0");
if($category_id != "")
{
        $this->db->where("category_id=".$category_id."");
}
        $this->db->order_by("category_id");
        $query = $this->db->get();
        $row = $query->result_array();
//        for ($i = 0; $i < sizeof($row); $i++) {
//
//            $row[$i]['category_name'] = $this->getcategoryfromid($row[$i]['category_id'])[0]['name'];
//        }
        return $row;
    }
    public function GetAllNewAnswer($que_id) 
    {
        $this->db->select('*');
        $this->db->from('answer');
        $this->db->where("is_deleted=0");
        $this->db->where("que_id=".$que_id."");
        $query = $this->db->get();
        $row = $query->result_array();
        return $row;
    }
    
	public function GetGivenRatingArray($sender_id,$week,$month,$year,$type)
	{
		$this->db->select('receiver_id,rating');
        $this->db->from('ratingbyweek');
        $this->db->where("week=".$week."");
		$this->db->where("month=".$month."");
		$this->db->where("year=".$year."");
		if($type == "inbound")
		{
			$this->db->where('("type"="inbound" or "type"="outbound")');
		}
		else
		{
			 $this->db->where("type='".$type."'");
		}
		$this->db->where("sender_id=".$sender_id);
		$query = $this->db->get();
        $row = $query->result_array();
		$arr['receiver_id'] = array_map (function($value){
			return $value['receiver_id'];
		} , $row);
		$arr['rating'] = array_map (function($value){
			return $value['rating'];
		} , $row);
        return $arr;
	}
	public function GetTakenRatingArray($receiver_id,$week,$month,$year,$type)
	{
		$this->db->select('sender_id,rating');
        $this->db->from('ratingbyweek');
        $this->db->where("week=".$week."");
		$this->db->where("month=".$month."");
		$this->db->where("year=".$year."");
		if($type == "inbound")
		{
			$this->db->where('("type"="inbound" or "type"="outbound")');
		}
		else
		{
			 $this->db->where("type='".$type."'");
		}
		$this->db->where("receiver_id=".$receiver_id);
		$query = $this->db->get();
        $row = $query->result_array();
		$arr['sender_id'] = array_map (function($value){
			return $value['sender_id'];
		} , $row);
		$arr['rating'] = array_map (function($value){
			return $value['rating'];
		} , $row);
        return $arr;
	}

}
