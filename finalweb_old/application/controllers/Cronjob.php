<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cronjob extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // Load Model
        $this->load->model('backend_model', 'backend');
    }

    public function reset() {
        $this->data['groups'] = $this->backend->get_table('squad_group');
        $week = $this->backend->getcurrentweek();

        $batchData = array();
        $i = 0;

        if (count($this->data['groups']) > 0) {
            foreach ($this->data['groups'] as $group) {
                $condition = array('u.squad_group' => $group->squad_group, 'u.active' => 1);
                $this->data['users'] = get_activeusers_heper($condition, $week->week, $week->month, $week->year);

                if (count($this->data['users']) > 0) {
                    foreach ($this->data['users'] as $user) {

                        $batchData[$i]['user_id'] = $user->id;
                        $batchData[$i]['is_paused'] = $user->is_paused;
                        $batchData[$i]['squad_group'] = $group->squad_group;
                        $batchData[$i]['week'] = $week->week;
                        $batchData[$i]['month'] = $week->month;
                        $batchData[$i]['year'] = $week->year;
                        $batchData[$i]['created_date'] = date('Y-m-d H:i:s');

                        $i++;
                    }
                }
            }

            if ($batchData) {
                $this->backend->insert_batch('week_squad', $batchData);
                echo 'Success';
                return;
            }
        }

        echo 'Failed';
    }

}
