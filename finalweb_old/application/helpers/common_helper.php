<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// question list
function get_questions_helper($_cat_id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('question');
    $CI->db->where('cat_id', $_cat_id);
    $CI->db->where('is_parent', 0);
    return $CI->db->get()->result();
}

// answers
function get_answers_helper($_question_id) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('answer');
    $CI->db->where('question_id', $_question_id);
    return $CI->db->get()->result();
}

// qasquad list
function get_qasquad_helper($_condition) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('qa_squad');
    if ($_condition) {
        $CI->db->where($_condition);
    }
    return $CI->db->get()->result();
}

// squad members
function get_squad_helper($_squad_group) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('users');
    $CI->db->where('squad_group', $_squad_group);
    $CI->db->where('active', 1);
    return $CI->db->get()->result();
}

// mysquad members
function get_countassessment_helper($_condition) {
    $CI = & get_instance();
    $CI->db->select('*');
    $CI->db->from('week_rating');
    $CI->db->where($_condition);
    return $CI->db->count_all_results();
}

/**
 * rating given or not
 * @param type $_condition
 * @return int
 */
function get_israting_helper($_condition) {
    $CI = & get_instance();
    $CI->db->select("ROUND(w.rating, 2) as rating, w.assessment");
    $CI->db->from('week_rating as w');
    $CI->db->join('assessment as a', 'a.assessment = w.assessment');
    $CI->db->join('type as t', 't.type_id = a.type_id');

    if ($_condition) {
        $CI->db->where($_condition);
    }

    $row = $CI->db->get()->row();
    if ($row) {
        return $row;
    } else {
        return 0;
    }
}

/**
 * QA claim squad or not
 * @param type $_condition
 * @return int
 */
function get_isclaim_helper($_condition) {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from('qa_squad');

    if ($_condition) {
        $CI->db->where($_condition);
    }

    $row = $CI->db->get()->row();
    if ($row) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * get squad group name
 */
function get_squadgroup_helper($condition) {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from('squad_group');

    if ($condition) {
        $CI->db->where($condition);
    }

    return $CI->db->get()->row()->squad_name;
}

/**
 * get first name
 */
function get_firstname_helper($_condition) {
    $CI = & get_instance();
    $CI->db->select("first_name");
    $CI->db->from('users');

    if ($_condition) {
        $CI->db->where($_condition);
    }

    return $CI->db->get()->row()->first_name;
}

/**
 * squad average week rating
 * @param type $condition
 * @return type
 */
function get_averagerating_helper($condition) {
    $CI = & get_instance();
    $CI->db->select("AVG(ROUND(rating, 2)) as rating");
    $CI->db->from('week_rating');
    if ($condition) {
        $CI->db->where($condition);
    }

    return $CI->db->get()->row()->rating;
}

/**
 * squad average week rating
 * @param type $condition
 * @return type
 */
function get_ispaused_helper($condition) {
    $CI = & get_instance();
    $CI->db->select("id");
    $CI->db->from('pause_account');
    if ($condition) {
        $CI->db->where($condition);
    }

    $row = $CI->db->get()->row();
    if ($row) {
        return FALSE;
    } else {
        return TRUE;
    }
}

/**
 * get first name
 */
function get_enddate_helper($_condition) {
    $CI = & get_instance();
    $CI->db->select("to_date, from_date");
    $CI->db->from('week');

    if ($_condition) {
        $CI->db->where($_condition);
    }

    return $CI->db->get()->row();
}

/**
 * return row if assessment completed.
 */
function get_checkassessment_helper($_condition) {
    $CI = & get_instance();
    $CI->db->select("w.receiver_id, ROUND(AVG(w.rating), 2) as rating, w.sender_id, COUNT(*) as total");
    $CI->db->from('week_rating as w');

    $CI->db->join('assessment as a', 'a.assessment = w.assessment');
    $CI->db->join('type as t', 't.type_id = a.type_id');

    if ($_condition) {
        $CI->db->where($_condition);
    }

    $CI->db->group_by(array("w.receiver_id", "w.sender_id"));
    $CI->db->having('COUNT(*) = (SELECT COUNT(*) FROM type)');

    return $CI->db->get()->row();
}

/**
 * return result if assessment completed.
 */
function get_getallassessment_helper($condition) {
    $CI = & get_instance();
    $CI->db->select("w.receiver_id, ROUND(AVG(w.rating), 2) as rating, w.sender_id, COUNT(*) as total");
    $CI->db->from('week_rating as w');

    $CI->db->join('assessment as a', 'a.assessment = w.assessment');
    $CI->db->join('type as t', 't.type_id = a.type_id');

    if ($condition) {
        $CI->db->where($condition);
    }

    $CI->db->group_by(array("w.receiver_id", "w.sender_id"));
    return $CI->db->get()->result();
}

/**
 * return result if assessment completed.
 */
function get_assessment_helper($condition) {
    $CI = & get_instance();
    $CI->db->select("w.receiver_id, rating, w.sender_id, t.type_id");
    $CI->db->from('week_rating as w');

    $CI->db->join('assessment as a', 'a.assessment = w.assessment');
    $CI->db->join('type as t', 't.type_id = a.type_id');

    if ($condition) {
        $CI->db->where($condition);
    }

    return $CI->db->get()->result();
}

/**
 * Member list
 * @param type $condition
 * @param type $week
 * @param type $month
 * @param type $year
 * @return result
 */
function get_activeusers_heper($condition, $week, $month, $year) {
    $CI = & get_instance();
    $CI->db->select("id, first_name, last_name, squad_group, profile, IF ((SELECT COUNT(*) FROM pause_account WHERE user_id = u.id AND status = 0 AND week = " . $week . " AND month = " . $month . " AND year = " . $year . "), 1,0) as is_paused", FALSE);
    $CI->db->from('users as u');
    if ($condition) {
        $CI->db->where($condition);
    }

    $CI->db->order_by('is_paused', 'asc');
    $CI->db->order_by('id', 'asc');

    return $CI->db->get()->result();
}

/**
 * Check one assessment
 * @param type $condition
 * @return type
 */
function check_oneassessment($condition) {
    $CI = & get_instance();
    $CI->db->select("*");
    $CI->db->from('one_assessment');

    if ($condition) {
        $CI->db->where($condition);
    }

    return $CI->db->get()->row();
}
