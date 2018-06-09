<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admintemplate extends MX_Controller
{
    function __construct() {
        parent::__construct();
    }

    public function getHeader(){
        return $this->load->view('admintemplate/_1head', "", true);
    }

    public function getJavascripts(){
        return $data['javascript'] = $this->load->view('admintemplate/_6javascripts', "", true);
    }

    public function one_col($data)
    {

        $canentrar = false;

        $isoperador = Modules::run('auth/isoperador', array());
        $isadmin = Modules::run('auth/isadmin', array());
        $isempresa = Modules::run('auth/isempresa', array());
        $directivo = Modules::run('auth/isdirectivo', array());
        if (($isoperador) && (isset($data['controlador']))) {
            if ($data['controlador'] == 'users') {
                $canentrar = true;
            }
        }
        if (($directivo) && (isset($data['controlador']))) {
            if (($data['controlador'] == 'users') || ($data['controlador'] == 'directivo')) {
                $canentrar = true;
            }
        }
        if ($isadmin) {
            $canentrar = true;
        }
        if ($isempresa) {
            $canentrar = true;
        }

        if ($canentrar) {
            $data['headpage'] = $this->load->view('admintemplate/_1head', $data, true);
            $data['headermenu'] = $this->load->view('admintemplate/_2headermenu', $data, true);
            //$data['errormessage'] = $this->load->view('admintemplate/_3_1error_message', $data, true);
            //$data['successmessage'] = $this->load->view('admintemplate/_3_2success_message', $data, true);
            $data['aside'] = $this->load->view('admintemplate/_4aside', $data, true);
            $data['quicksidebar'] = $this->load->view('admintemplate/_4_1quicksidebar', $data, true);
            $data['footer'] = $this->load->view('admintemplate/_5footer', $data, true);
            $data['javascript'] = $this->load->view('admintemplate/_6javascripts', $data, true);
            $this->load->view('admintemplate/one_col', $data);
        } else {
            $this->session->set_flashdata('message', 'no tiene acceso a esta tarea, Ingrese con una cuenta con mas privilegios.');
            redirect('auth/login', 'refresh');
        }
    }

    public function solologin($data)
    {

        $data['headpage'] = $this->load->view('admintemplate/_1head', $data, true);
        $data['headermenu'] = $this->load->view('admintemplate/_2headermenu', $data, true);
        //$data['errormessage'] = $this->load->view('admintemplate/_3_1error_message', $data, true);
        //$data['successmessage'] = $this->load->view('admintemplate/_3_2success_message', $data, true);
        $data['aside'] = $this->load->view('admintemplate/_4aside', $data, true);
        $data['quicksidebar'] = $this->load->view('admintemplate/_4_1quicksidebar', $data, true);
        $data['footer'] = $this->load->view('admintemplate/_5footer', $data, true);
        $data['javascript'] = $this->load->view('admintemplate/_6javascripts', $data, true);

        $this->load->view('admintemplate/one_col', $data);
    }
    public function security($data)
    {

        $data['headpage'] = $this->load->view('admintemplate/_1head', $data, true);
        $data['headermenu'] = $this->load->view('admintemplate/_2headermenu', $data, true);
        //$data['errormessage'] = $this->load->view('admintemplate/_3_1error_message', $data, true);
        //$data['successmessage'] = $this->load->view('admintemplate/_3_2success_message', $data, true);
       // $data['aside'] = $this->load->view('admintemplate/_4aside', $data, true);
       // $data['quicksidebar'] = $this->load->view('admintemplate/_4_1quicksidebar', $data, true);
        $data['footer'] = $this->load->view('admintemplate/_5footer', $data, true);
        $data['javascript'] = $this->load->view('admintemplate/_6javascripts', $data, true);

        $this->load->view('admintemplate/security', $data);
    }
}
