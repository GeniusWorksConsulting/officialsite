<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library(array('ion_auth'));

        if (!$this->ion_auth->logged_in()) {
            // redirect them to the login page
            redirect('auth/login', 'refresh');
        } else if (!$this->ion_auth->is_admin()) { // remove this elseif if you want to enable this for non-admins
            // redirect them to the home page because they must be an administrator to view this
            $this->ion_auth->logout();
            return show_error('You must be an administrator to view this page.');
        }
    }

}
