<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// image upload
function image_upload_helper($file, $path, $file_name, $redirect_url) {
    if (empty($file['name'])) {
        return $file_name;
    }

    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);

    if ($file_name == NULL || $file_name == '') {
        $file_name = time() . '.' . $ext;
    } else {
        $temp_name = substr($file_name, 0, strrpos($file_name, '.'));
        $file_name = $temp_name . '.' . $ext;
    }

    if (!is_dir($path)) {
        mkdir($path, 0755, TRUE);
    }

    $config['upload_path'] = $path;
    $config['allowed_types'] = 'jpg|jpeg|png|gif';
    $config['file_name'] = $file_name;
    $config['overwrite'] = TRUE;

    //Load upload library and initialize configuration
    $CI = & get_instance();
    $CI->load->library('upload', $config);
    $CI->load->initialize($config);

    if (!$CI->upload->do_upload('thumb')) {
        $CI->session->set_flashdata('message', $this->upload->display_errors());
        redirect($redirect_url);
    } else {
        return $file_name;
    }
}

function after_submit_helper($status, $redirect) {
    $CI = & get_instance();
    if ($status) {
        $CI->session->set_flashdata('message', 'Your Data Has Been Successfully Saved.');
    } else {
        $CI->session->set_flashdata('message', 'Failed! Please try after sometime.');
    }

    redirect($redirect, 're');
}
