<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grupo extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Grupo_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = site_url('grupo?q=' . urlencode($q));
            $config['first_url'] = site_url('grupo?q=' . urlencode($q));
        } else {
            $config['base_url'] = site_url('grupo');
            $config['first_url'] = site_url('grupo');
        }

        $config['per_page'] = 30;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Grupo_model->total_rows($q);
        $grupo = $this->Grupo_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'grupo_data' => $grupo,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $data['menuactivo'] = 'grupo';
        $data['controlador'] = 'directivo';
        $data['title'] = 'grupo';
        $data['vista'] = $this->load->view('grupo_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function grupo_select()
    {
        $data['grupos'] = $this->Grupo_model->get_all();
        $this->load->view('grupo_select', $data);
    }

    public function read($id)
    {
        $row = $this->Grupo_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'nombre_grupo' => $row->nombre_grupo,
            );


            $data['menuactivo'] = 'grupo';
            $data['title'] = 'grupo';
            $data['vista'] = $this->load->view('grupo_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('grupo'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('grupo/create_action'),
            'id' => set_value('id'),
            'nombre_grupo' => set_value('nombre_grupo'),
        );


        $data['menuactivo'] = 'grupo';
        $data['controlador'] = 'directivo';
        $data['title'] = 'grupo';
        $data['vista'] = $this->load->view('grupo_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'nombre_grupo' => $this->input->post('nombre_grupo', TRUE),
            );

            $this->Grupo_model->insert($data);
            $this->session->set_flashdata('message', 'Grupo creado correctamente');
            redirect(site_url('grupo'));
        }
    }

    public function update($id)
    {
        $row = $this->Grupo_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('grupo/update_action'),
                'id' => set_value('id', $row->id),
                'nombre_grupo' => set_value('nombre_grupo', $row->nombre_grupo),
            );


            $data['menuactivo'] = 'grupo';
            $data['controlador'] = 'directivo';
            $data['title'] = 'grupo';
            $data['vista'] = $this->load->view('grupo_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('grupo'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'nombre_grupo' => $this->input->post('nombre_grupo', TRUE),
            );

            $this->Grupo_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Grupo Actualizado');
            redirect(site_url('grupo'));
        }
    }

    public function delete($id)
    {
        $row = $this->Grupo_model->get_by_id($id);

        if ($row) {
            $this->Grupo_model->delete($id);
            $this->session->set_flashdata('message', 'Grupo Eliminado');
            redirect(site_url('grupo'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('grupo'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nombre_grupo', 'nombre grupo', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Grupo.php */
/* Location: ./application/controllers/Grupo.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-30 19:28:07 */
/* http://harviacode.com */