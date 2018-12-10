<?php

class Backend_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * insert row into table
     */
    public function insert_data($table, $data) {
        $this->db->insert($table, $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * insert batch data into table
     */
    public function insert_batch($table, $data = array()) {
        $insert = $this->db->insert_batch($table, $data);
        return $insert ? true : false;
    }

    /**
     * get single row from table
     */
    public function get_table_row($table, $condition_arr) {
        $this->db->select('*');
        $this->db->from($table);
        if ($condition_arr) {
            $this->db->where($condition_arr);
        }
        return $this->db->get()->row();
    }

    // check row exist or not
    function check_row_exist($table, $condition) {
        $this->db->where($condition);
        $this->db->select('*');
        $query = $this->db->get($table);
        $row_count = $query->num_rows();

        if ($row_count > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * delete row from table
     */
    public function delete_data($table, $condition) {
        $this->db->where($condition);
        $this->db->delete($table);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * count number of rows.
     */
    public function count_table_rows($table, $condition = NULL) {
        $this->db->select("*");
        $this->db->from($table);

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->count_all_results();
    }

    /**
     * update table row
     */
    public function update_data($table, $condition, $data) {
        if ($condition) {
            $this->db->update($table, $data, $condition);
        } else {
            $this->db->update($table, $data);
        }

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * get multiple rows
     */
    public function get_table($table, $condition = NULL, $order = NULL, $or_condition = NULL) {
        $this->db->select("*");
        $this->db->from($table);

        if ($condition) {
            $this->db->where($condition);
        }

        if ($or_condition) {
            $this->db->or_where($or_condition);
        }

        if ($order) {
            $this->db->order_by($order['column'], $order['order']);
        }

        return $this->db->get()->result();
    }

    /**
     * get multiple rows
     */
    public function get_selected_table($table, $column, $condition = NULL) {
        $this->db->select($column);
        $this->db->from($table);

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->result();
    }

    // Current Week
    public function get_current_week() {
        $this->db->select('week, month, year, from_date, to_date');
        $this->db->from('week');
        $this->db->where("from_date<='" . date("Y-m-d") . "'");
        $this->db->where("to_date>='" . date("Y-m-d") . "'");

        $row = $this->db->get()->row();

        if ($row) {
            return $row;
        }

        return false;
    }

    /**
     * get users
     */
    public function get_users($condition = NULL, $limit = 0, $start = 0) {
        $this->db->select("u.id, u.first_name, u.last_name, u.active, u.email, u.phone, ua.admin_id, g.description");
        $this->db->from('users as u');
        $this->db->join('users_admin as ua', 'ua.user_id = u.id');
        $this->db->join('users_groups as ug', 'ug.user_id = u.id');
        $this->db->join('groups as g', 'g.id = ug.group_id');

        if ($condition) {
            $this->db->where($condition);
        }

        if ($limit == 0 and $start == 0) {
            return $this->db->count_all_results();
        }

        $this->db->limit($limit, $start);
        return $this->db->get()->result();
    }

    /**
     * get users
     */
    public function get_all_users($condition = NULL) {
        $this->db->select("u.id, u.first_name, u.last_name, u.active, u.email, u.phone, ua.admin_id, g.description");
        $this->db->from('users as u');
        $this->db->join('users_admin as ua', 'ua.user_id = u.id');
        $this->db->join('users_groups as ug', 'ug.user_id = u.id');
        $this->db->join('groups as g', 'g.id = ug.group_id');

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->result();
    }

    /**
     * get user row
     */
    public function get_user_row($condition = NULL) {
        $this->db->select("u.id, u.first_name, u.last_name, u.username, u.active, u.email, u.phone, ua.admin_id, g.id as group_id");
        $this->db->from('users as u');
        $this->db->join('users_admin as ua', 'ua.user_id = u.id');
        $this->db->join('users_groups as ug', 'ug.user_id = u.id');
        $this->db->join('groups as g', 'g.id = ug.group_id');

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->row();
    }

    // Squad Groups
    public function get_squad_groups($condition = NULL) {
        $this->db->select("s.squad_id, s.squad_name, s.site, s.created, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('squad_group as s');
        $this->db->join('users as u', 'u.id = s.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->result();
    }

    // Get Weeks
    public function get_weeks($condition = NULL, $limit = 0, $start = 0) {
        $this->db->select("w.id, w.week, w.month, w.year, w.from_date, w.to_date, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('week as w');
        $this->db->join('users as u', 'u.id = w.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        if ($limit == 0 and $start == 0) {
            return $this->db->count_all_results();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result();
    }

    // Get All Weeks
    public function get_all_week($condition = NULL) {
        $this->db->select("w.id, w.week, w.month, w.year, w.from_date, w.to_date, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('week as w');
        $this->db->join('users as u', 'u.id = w.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        $this->db->order_by('id', 'DESC');
        return $this->db->get()->result();
    }

    // Schedule
    public function get_schedule($condition = NULL, $limit = 0, $start = 0) {
        $this->db->select("s.schedule_id, s.sender_id, s.admin_id, s.schedule_date, s.from_time, s.to_time, s.week, s.month, s.year, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('scheduled as s');
        $this->db->join('users as u', 'u.id = s.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        if ($limit == 0 and $start == 0) {
            return $this->db->count_all_results();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('s.schedule_id', 'desc');
        return $this->db->get()->result();
    }

    // Schedule All
    public function get_all_schedule($condition = NULL) {
        $this->db->select("s.schedule_id, s.sender_id, s.admin_id, s.schedule_date, s.from_time, s.to_time, s.week, s.month, s.year, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('scheduled as s');
        $this->db->join('users as u', 'u.id = s.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        $this->db->order_by('s.schedule_id', 'desc');
        return $this->db->get()->result();
    }

    // Squad Users
    public function get_squad_users($condition = NULL) {
        $this->db->select("s.squad_id, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.id as user_id");
        $this->db->from('squad_users as s');
        $this->db->join('users as u', 'u.id = s.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->result();
    }

    // Sub Assessment
    public function get_sub_assessment($condition = NULL) {
        $this->db->select("s.sub_ass_id, s.name, s.created, a.assessment_id, a.name as ass_name, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('sub_assessment as s');
        $this->db->join('assessment as a', 'a.assessment_id = s.assessment_id');
        $this->db->join('users as u', 'u.id = s.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->result();
    }

    // Category
    public function get_category($condition = NULL) {
        $this->db->select("c.cat_id, c.cat_name, c.created, c.weighting, s.name, a.name as ass_name, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('category as c');
        $this->db->join('sub_assessment as s', 's.sub_ass_id = c.sub_ass_id');
        $this->db->join('assessment as a', 'a.assessment_id = s.assessment_id');
        $this->db->join('users as u', 'u.id = s.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->result();
    }

    // Get Admin Id
    public function get_admin_id($condition = NULL) {
        $this->db->select("admin_id");
        $this->db->from('users_admin');

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->row()->admin_id;
    }

    // Questions
    public function get_questions($condition, $limit = 0, $start = 0) {
        $this->db->select("q.question_id, q.description, q.evaluation, q.weight, q.is_parent, q.has_child, q.count, q.no_answer, q.created, c.cat_id, c.cat_name, s.sub_ass_id, s.name as sub_name, a.assessment_id, a.name as ass_name, CONCAT(u.first_name, ' ', u.last_name) as user_name");
        $this->db->from('question as q');
        $this->db->join('category as c', 'c.cat_id = q.cat_id');
        $this->db->join('sub_assessment as s', 's.sub_ass_id = c.sub_ass_id');
        $this->db->join('assessment as a', 'a.assessment_id = s.assessment_id');
        $this->db->join('users as u', 'u.id = q.user_id');

        if ($condition) {
            $this->db->where($condition);
        }

        if ($limit == 0 and $start == 0) {
            return $this->db->count_all_results();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('q.question_id', 'asc');
        return $this->db->get()->result();
    }

}
