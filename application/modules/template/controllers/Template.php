<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Template extends MX_Controller {
    function __construct(){
        parent::__construct();

        // Load the Library
        $this->load->library(array('user', 'user_manager'));
    }

	public function one_col($data)
	{
		$this->load->view('one_col',$data);
	}
    public function two_col($data)
    {
        $this->load->view('two_col',$data);
    }
    public function admin($data)
    {
       // Modules::run('site_security/check_is_admin');
        $this->load->view('admin',$data);
    }
    function master($data)
    {       
        $this->load->view('master',$data);
    }
    function login($data){
        $this->load->view('login',$data);
    }

}
