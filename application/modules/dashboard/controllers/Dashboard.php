<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends MX_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->library(array('user', 'user_manager'));
    }

    function home(){
     //  echo Modules::run('site_security/check_is_admin');
        $data['module'] = 'dashboard';
        $data['view_file'] = 'home';
        echo Modules::run('template/admin',$data);        
    }

}