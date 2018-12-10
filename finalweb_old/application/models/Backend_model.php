<?php

class Backend_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    /**
     * insert row into table
     * @param type $table
     * @param type $data
     * @return type
     */
    public function insert_data($table, $data) {
        $this->db->insert($table, $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : FALSE;
    }

    /**
     * insert batch data into table
     * @param type $table
     * @param type $data
     * @return type
     */
    public function insert_batch($table, $data = array()) {
        $insert = $this->db->insert_batch($table, $data);
        return $insert ? true : false;
    }

    /**
     * get single row from table
     * @param type $table
     * @param type $condition_arr
     * @return type
     */
    public function get_table_row($table, $condition_arr) {
        $this->db->select('*');
        $this->db->from($table);
        if ($condition_arr) {
            $this->db->where($condition_arr);
        }
        return $this->db->get()->row();
    }

    /**
     * check row exit or not
     * @param type $table
     * @param type $where
     * @return boolean
     */
    function check_row_exist($table, $where) {
        $this->db->select('*');
        $this->db->from($table);
        if ($where) {
            $this->db->where($where);
        }

        $row = $this->db->get()->row();
        if ($row) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * delete row from table
     * @param type $table
     * @param type $condition
     * @return type
     */
    public function delete_data($table, $condition) {
        $this->db->where($condition);
        $this->db->delete($table);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * count number of rows.
     * @param type $table
     * @param type $condition
     * @return type
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
     * @param type $table
     * @param type $condition
     * @param type $data
     * @return type
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
     * get multiple table row
     * @param type $table
     * @param type $condition
     * @param type $order
     * @return type
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
     * get multiple table row
     * @param type $table
     * @param type $condition
     * @param type $order
     * @return type
     */
    public function get_selected_table($table, $column, $condition = NULL) {
        $this->db->select($column);
        $this->db->from($table);

        if ($condition) {
            $this->db->where($condition);
        }

        return $this->db->get()->result();
    }

    /**
     * Message List
     * @param type $condition
     * @param type $user_id
     * @return type
     */
    public function messages($condition, $user_id) {
        $this->db->select("*");
        $this->db->from('messages');

        $this->db->where($condition);
        $this->db->where("(user_id = $user_id OR user_id = 0 OR sender_id = $user_id)", NULL, FALSE);

        $this->db->order_by('id', 'desc');
        return $this->db->get()->result();
    }

    /**
     * average assessment (text and voice)
     * @param type $condition
     */
    public function assessment_report($condition = NULL, $group = NULL) {
        $this->db->select("w.receiver_id, w.rating, w.sender_id, t.type_id");
        $this->db->from('week_rating as w');

        $this->db->join('assessment as a', 'a.assessment = w.assessment');
        $this->db->join('type as t', 't.type_id = a.type_id');

        if ($condition) {
            $this->db->where($condition);
        }

        if ($group) {
            $this->db->group_by($group);
            $this->db->having('COUNT(*) = (SELECT COUNT(*) FROM type)');
        }

        //$this->db->having('COUNT(*) = (SELECT COUNT(*) FROM type)');

        $this->db->order_by('w.receiver_id', 'asc');
        $this->db->order_by('w.sender_id', 'asc');
        $this->db->order_by('t.type_id', 'asc');

        return $this->db->get()->result();
    }

    /**
     * list of category
     * @param type $condition
     * @return type
     */
    public function categories($condition) {
        $this->db->select("c.cat_id, c.name, c.weighting, c.created, a.name as type, a.assessment");
        $this->db->from('category as c');
        $this->db->join('assessment as a', 'a.assessment = c.assessment');

        if ($condition) {
            $this->db->where($condition);
        }

        $this->db->order_by('c.cat_id', 'asc');
        return $this->db->get()->result();
    }

    /**
     * list of questions
     * @param type $condition
     * @return type
     */
    public function questions($condition, $limit = 0, $start = 0) {
        $this->db->select("q.question_id, q.description, q.weight, q.is_parent, q.has_child, q.count, q.no_answer, q.date_added, c.cat_id, c.name, a.assessment, a.name as type");
        $this->db->from('question as q');
        $this->db->join('category as c', 'c.cat_id = q.cat_id');
        $this->db->join('assessment as a', 'a.assessment = c.assessment');

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

    /**
     * list of questions
     * @param type $condition
     * @return type
     */
    public function child_question($condition) {
        $this->db->select("q.question_id, q.description, q.evaluation, q.weight, q.is_parent, q.has_child, q.count, q.no_answer, q.date_added, c.cat_id, c.name, a.assessment, a.name as type");
        $this->db->from('question as q');
        $this->db->join('category as c', 'c.cat_id = q.cat_id');
        $this->db->join('assessment as a', 'a.assessment = c.assessment');

        if ($condition) {
            $this->db->where($condition);
        }

        $this->db->order_by('q.question_id', 'asc');
        return $this->db->get()->result();
    }

    /**
     * current week of the month
     * @return boolean
     */
    public function getcurrentweek() {
        $this->db->select('*');
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
     * get row from week table
     * @param type $condition
     * @return boolean
     */
    public function getWeek($condition) {
        $this->db->select('*');
        $this->db->from('week');
        if ($condition) {
            $this->db->where($condition);
        }
        $row = $this->db->get()->row();

        if ($row) {
            return $row;
        }

        return false;
    }

    /**
     * squad member week average assessment
     * @param type $condition
     * @return type
     */
    public function customer_love($condition) {
        $this->db->select("w.receiver_id, ROUND(AVG(w.rating), 2) as rating, w.sender_id, COUNT(*) as total");
        $this->db->from('week_rating as w');

        $this->db->join('assessment as a', 'a.assessment = w.assessment');
        $this->db->join('type as t', 't.type_id = a.type_id');

        if ($condition) {
            $this->db->where($condition);
        }

        $this->db->group_by(array("w.receiver_id", "w.sender_id"));
        $this->db->having('COUNT(*) = (SELECT COUNT(*) FROM type)');

        return $this->db->get()->result();
    }

    /**
     * get how much rating given by logged in user
     * @param type $condition
     * @return int
     */
    public function getuser_rating($condition) {
        $this->db->select("w.rating");
        $this->db->from('week_rating as w');
        $this->db->join('assessment as a', 'a.assessment = w.assessment');
        $this->db->join('type as t', 't.type_id = a.type_id');

        if ($condition) {
            $this->db->where($condition);
        }

        $row = $this->db->get()->row();
        if ($row) {
            return $row->rating;
        } else {
            return 0;
        }
    }

    /**
     * check squad member give rating or not
     * @param type $condition
     * @return boolean
     */
    public function isRating($condition) {
        $this->db->select("w.rating");
        $this->db->from('week_rating as w');
        $this->db->join('assessment as a', 'a.assessment = w.assessment');
        $this->db->join('type as t', 't.type_id = a.type_id');

        if ($condition) {
            $this->db->where($condition);
        }

        $row = $this->db->get()->row();
        if ($row) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * 
     * @param type $condition
     */
    public function previousAssessment($condition, $limit = 0) {
        $this->db->select("week, month, year");
        $this->db->from('week_rating');
        if ($condition) {
            $this->db->where($condition);
        }

        $this->db->group_by(array("week", "month", "year"));
        $this->db->order_by('id', 'desc');

        if ($limit != 0) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result();
    }

    /**
     * list of remaining squad
     * @param type $condition
     * @return type
     */
    public function remainingSquad($where) {
        $this->db->select("s.squad_group, s.squad_name");
        $this->db->from('squad_group as s');
        $this->db->where('s.squad_group NOT IN (SELECT q.squad_group FROM qa_squad as q WHERE q.week = "' . $where['week'] . '" AND q.month = "' . $where['month'] . '" AND q.year = "' . $where['year'] . '" AND q.user_id = "' . $where['user_id'] . '")', NULL, FALSE);

        return $this->db->get()->result();
    }

    /**
     * list of scheduled
     * @param type $condition
     * @return type
     */
    public function scheduled($condition, $limit = 0, $start = 0) {
        $this->db->select("s.id, s.date, s.from_time, s.to_time, s.week, s.month, s.year, s.sender_id, s.user_id, CONCAT(u.first_name, ' ', u.last_name) as user_name, sg.squad_name");
        $this->db->from('scheduled as s');
        $this->db->join('users as u', 'u.id = s.user_id');
        $this->db->join('squad_group as sg', 'sg.squad_group = u.squad_group');

        if ($condition) {
            $this->db->where($condition);
        }

        if ($limit == 0 and $start == 0) {
            return $this->db->count_all_results();
        }

        $this->db->limit($limit, $start);
        $this->db->order_by('s.id', 'desc');
        return $this->db->get()->result();
    }

}
