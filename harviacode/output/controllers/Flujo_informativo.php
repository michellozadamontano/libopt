<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Flujo_informativo extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_flujo_informativo');
        $this->load->library('form_validation');
    }

    public function get_all(){
return $this->Mdl_flujo_informativo->get_all();        
}

function duplicar($id){
        $prod = $this->get_by_id($id);
        $prod2 = $prod;
        unset($prod2->id);

        foreach ($prod2 as $key=>$item)
        {
            if(!$this->Mdl_flujo_informativo->field_exists($key)){
                unset($prod2->$key);
            }
        }
        $this->Mdl_flujo_informativo->insert($prod2);
        redirect( site_url('flujo_informativo'));
    }


public function get_by_id($id){
return $this->Mdl_flujo_informativo->get_by_id($id);
}
        

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));
        
        if ($q <> '') {
            $config['base_url'] = site_url('flujo_informativo?q=' . urlencode($q));
            $config['first_url'] = site_url('flujo_informativo?q=' . urlencode($q));
        } else {
            $config['base_url'] = site_url('flujo_informativo');
            $config['first_url'] = site_url('flujo_informativo')  ;
        }

        $config['per_page'] = 10;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Mdl_flujo_informativo->total_rows($q);
        $flujo_informativo = $this->Mdl_flujo_informativo->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'flujo_informativo_data' => $flujo_informativo,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $data['menuactivo'] = 'flujo_informativo';
        $data['title'] = 'flujo_informativo';
        $data['vista'] = $this->load->view('flujo_informativo_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function read($id) 
    {
        $row = $this->Mdl_flujo_informativo->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'actividad' => $row->actividad,
		'hora_inicio' => $row->hora_inicio,
		'hora_fin' => $row->hora_fin,
		'dirigente' => $row->dirigente,
		'categoria_id' => $row->categoria_id,
	    );
            


        $data['menuactivo'] = 'flujo_informativo';
        $data['title'] = 'flujo_informativo';
        $data['vista'] = $this->load->view('flujo_informativo_read', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('flujo_informativo'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Adicionar',
            'action' => site_url('flujo_informativo/create_action'),
	    'id' => set_value('id'),
	    'actividad' => set_value('actividad'),
	    'hora_inicio' => set_value('hora_inicio'),
	    'hora_fin' => set_value('hora_fin'),
	    'dirigente' => set_value('dirigente'),
	    'categoria_id' => set_value('categoria_id'),
	);
        


        $data['menuactivo'] = 'flujo_informativo';
        $data['title'] = 'flujo_informativo';
        $data['vista'] = $this->load->view('flujo_informativo_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
		'actividad' => $this->input->post('actividad',TRUE),
		'hora_inicio' => $this->input->post('hora_inicio',TRUE),
		'hora_fin' => $this->input->post('hora_fin',TRUE),
		'dirigente' => $this->input->post('dirigente',TRUE),
		'categoria_id' => $this->input->post('categoria_id',TRUE),
	    );

            $inserid =  $this->Mdl_flujo_informativo->insert($data);
            $this->session->set_flashdata('message', 'Registro Insertado Correctamente');
            

            if ($this->input->post('btnsubmit') == 'Guardar y Continuar'){
                redirect(site_url('flujo_informativo/update/'.$inserid));
            } else {
                redirect(site_url('flujo_informativo'));
            }

                
        }
    }
    
    public function update($id) 
    {
        $row = $this->Mdl_flujo_informativo->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Editar',
                'action' => site_url('flujo_informativo/update_action'),
		'id' => set_value('id', $row->id),
		'actividad' => set_value('actividad', $row->actividad),
		'hora_inicio' => set_value('hora_inicio', $row->hora_inicio),
		'hora_fin' => set_value('hora_fin', $row->hora_fin),
		'dirigente' => set_value('dirigente', $row->dirigente),
		'categoria_id' => set_value('categoria_id', $row->categoria_id),
	    );
            


        $data['menuactivo'] = 'flujo_informativo';
        $data['title'] = 'flujo_informativo';
        $data['vista'] = $this->load->view('flujo_informativo_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('flujo_informativo'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
		'actividad' => $this->input->post('actividad',TRUE),
		'hora_inicio' => $this->input->post('hora_inicio',TRUE),
		'hora_fin' => $this->input->post('hora_fin',TRUE),
		'dirigente' => $this->input->post('dirigente',TRUE),
		'categoria_id' => $this->input->post('categoria_id',TRUE),
	    );

            $this->Mdl_flujo_informativo->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Registro Actualizado');
            

            if ($this->input->post('btnsubmit') == 'Save and Continue'){
                redirect(site_url('flujo_informativo/update/'.$this->input->post('id', TRUE)));
            } else {
                redirect(site_url('flujo_informativo'));
            }



        }
    }
    
    public function delete($id) 
    {
        $row = $this->Mdl_flujo_informativo->get_by_id($id);

        if ($row) {
            $resultado = $this->Mdl_flujo_informativo->delete($id);
            if ($resultado["code"] != 0){
                $this->session->set_flashdata("message", $resultado["code"] .": " . $resultado["message"] );
            } else {
                $this->session->set_flashdata('message', 'Registro Eliminado');
            }
            redirect(site_url('flujo_informativo'));
        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('flujo_informativo'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('actividad', 'actividad', 'trim|required');
	$this->form_validation->set_rules('hora_inicio', 'hora inicio', 'trim|required');
	$this->form_validation->set_rules('hora_fin', 'hora fin', 'trim|required');
	$this->form_validation->set_rules('dirigente', 'dirigente', 'trim|required');
	$this->form_validation->set_rules('categoria_id', 'categoria id', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Flujo_informativo.php */
/* Location: ./application/controllers/Flujo_informativo.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-03-22 01:54:07 */
/* http://harviacode.com */