<?php

/**
 * User Controller
 * This controller fully demonstrates the user class.
 *
 * @package User
 * @author Waldir Bertazzi Junior
 * @link http://waldir.org/
 **/
class Login extends MX_Controller {
	
	function __construct(){
		parent::__construct();
		
		// Load the Library
		$this->load->library(array('user', 'user_manager'));
        $this->load->helper('url');
	}
	
	function index()
	{
		// If user is already logged in, send it to private page
		$this->user->on_valid_session('login/private_page');
		
		// Loads the login view
        $data['module'] = 'login';
        $data['view_file'] = 'login';
        echo Modules::run('template/login',$data);



	}
	
	function private_page(){
		// if user tries to direct access it will be sent to login
		//$this->user->on_invalid_session('login');
        $result = Modules::run('site_security/check_is_admin');
		if($result)
        {
          //  $data['module'] = 'login';
          //  $data['view_file'] = 'home';
            echo Modules::run('dashboard/home');
        }
        else
        {
            // ... else he will view home
          //  $data['module'] = 'login';
          //  $data['view_file'] = 'home';
          //  echo Modules::run('template/login',$data);
            echo Modules::run('welcome/index');
        }


	}
	
	function validate()
	{
		// Receives the login data
		$login = $this->input->post('login');
		$password = $this->input->post('password');
		
		/* 
		 * Validates the user input
		 * The user->login returns true on success or false on fail.
		 * It also creates the user session.
		*/
		if($this->user->login($login, $password)){
			// Success
            $id = $this->user->get_id(); // aqui obtengo el id de usuario loggeado
            $permision = $this->user_manager->tye_of_permission($id); //verifico  si es cliente o administrador o empleado del sistema
            if($permision->permission_id == 5){
                redirect('login/private_page');
            }
            if($permision->permission_id == 4){
                echo Modules::run('dashboard/home');
            }


		} else {
			// Oh, holdon sir.
			redirect('login');
		}
	}

    function register(){
        if($this->input->post()){
            $full_name = $this->input->post('fullname');
            $login = $this->input->post('username');
            $password = $this->input->post('password');
            $email = $this->input->post('email');
            $permission = $this->input->post('rol');
            $cargo  = $this->input->post('cargos');

            $user_id = $this->user_manager->save_user($full_name, $login, $password, $email,$cargo,1,$permission);
            if($user_id > 0){
                redirect('login/private_page');
            }
            else{
                $this->session->set_flashdata('error_message','this username is already taken');
                redirect('login');
            }

        }
        else{
            $data['query'] = $this->user_manager->get_permission();
            $data['module'] = 'login';
            $data['view_file'] = 'register';
            echo Modules::run('template/admin',$data);
        }

    }
	
	// Simple logout function
	function logout()
	{
		// Removes user session and redirects to login
		$this->user->destroy_user('login');
	}

}
?>
