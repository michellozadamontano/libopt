<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Site_security extends MX_Controller
{

    function __construct() {
        parent::__construct();
        $this->load->library(array('user', 'user_manager'));
        $this->load->helper('url');
    }
    function check_is_admin(){
       //make sure user is logged as admin
        $result = FALSE;
        if(!$this->user->on_invalid_session('login')){
            $id = $this->user->get_id(); // aqui obtengo el id de usuario loggeado
            $this->session->set_userdata('user_id',$id);
            $permision = $this->user_manager->tye_of_permission($id); //verifico  si es cliente o administrador o empleado del sistema
            if($permision->permission_id == 4){
                $result = TRUE;
            }
        }

       return $result;
    }
    

}