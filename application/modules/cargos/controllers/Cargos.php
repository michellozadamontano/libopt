<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Cargos extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Cargos_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = site_url('cargos?q=' . urlencode($q));
            $config['first_url'] = site_url('cargos?q=' . urlencode($q));
        } else {
            $config['base_url'] = site_url('cargos');
            $config['first_url'] = site_url('cargos')  ;
        }

        $config['per_page'] = 30;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Cargos_model->total_rows($q);
        $cargos = $this->Cargos_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'cargos_data' => $cargos,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $data['menuactivo'] = 'cargos';
        $data['title'] = 'cargos';
        $data['vista'] = $this->load->view('cargos_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function cargos_select()
    {
        $data['cargos'] = $this->Cargos_model->get_all();
        $this->load->view('cargos_select',$data);
    }

    public function read($id) 
    {
        $row = $this->Cargos_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'nombre_cargo' => $row->nombre_cargo,
	    );
            


        $data['menuactivo'] = 'cargos';
        $data['title'] = 'cargos';
        $data['vista'] = $this->load->view('cargos_read', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cargos'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('cargos/create_action'),
	    'id' => set_value('id'),
	    'nombre_cargo' => set_value('nombre_cargo'),
	);
        


        $data['menuactivo'] = 'cargos';
        $data['title'] = 'cargos';
        $data['vista'] = $this->load->view('cargos_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'nombre_cargo' => $this->input->post('nombre_cargo',TRUE),
	    );

            $this->Cargos_model->insert($data);
            $this->session->set_flashdata('message', 'Cargo creado correctamente');
            redirect(site_url('cargos'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Cargos_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('cargos/update_action'),
		'id' => set_value('id', $row->id),
		'nombre_cargo' => set_value('nombre_cargo', $row->nombre_cargo),
	    );
            


        $data['menuactivo'] = 'cargos';
        $data['title'] = 'cargos';
        $data['vista'] = $this->load->view('cargos_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'No encontrado');
            redirect(site_url('cargos'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'nombre_cargo' => $this->input->post('nombre_cargo',TRUE),
	    );

            $this->Cargos_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Cargo actualizado');
            redirect(site_url('cargos'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Cargos_model->get_by_id($id);

        if ($row) {
            $this->Cargos_model->delete($id);
            $this->session->set_flashdata('message', 'Cargo eliminado');
            redirect(site_url('cargos'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('cargos'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('nombre_cargo', 'nombre cargo', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Cargos.php */
/* Location: ./application/controllers/Cargos.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-02 17:58:04 */
/* http://harviacode.com */