<?php

// Admin Name 
function get_adminname_helper($user_id) {
    $CI = & get_instance();
    $CI->db->select("CONCAT(first_name, ' ', last_name) as name");
    $CI->db->from('users');
    $CI->db->where('id', $user_id);

    return $CI->db->get()->row()->name;
}

// SQUAD Users
function get_squad_users($condition) {
    $CI = & get_instance();
    $CI->db->select("s.id, s.squad_id, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.id as user_id");
    $CI->db->from('squad_users as s');

    $CI->db->join('users as u', 'u.id = s.user_id');

    if ($condition) {
        $CI->db->where($condition);
    }

    return $CI->db->get()->result();
}

// Level Name
function get_levelname_helper($level_id) {
    $CI = & get_instance();
    $CI->db->select("level_name");
    $CI->db->from('levels');
    $CI->db->where('level_id', $level_id);

    return $CI->db->get()->row()->level_name;
}
