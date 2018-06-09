<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Actividades extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Actividades_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = site_url('actividades?q=' . urlencode($q));
            $config['first_url'] = site_url('actividades?q=' . urlencode($q));
        } else {
            $config['base_url'] = site_url('actividades');
            $config['first_url'] = site_url('actividades');
        }

        $config['per_page'] = 30;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Actividades_model->total_rows($q);
        $actividades = $this->Actividades_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'actividades_data' => $actividades,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $data['menuactivo'] = 'actividades';
        $data['controlador'] = 'directivo';
        $data['title'] = 'actividades';
        $data['vista'] = $this->load->view('actividades_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    function activity_by_group()
    {
        $options = "";
        if ($this->input->post('grupo_id')) {
            $grupo_id = $this->input->post('grupo_id');
            $query = $this->get_activity_by_group($grupo_id);

            foreach ($query as $row) {
                ?>
                <option value="<?php echo $row->nombre_actividad ?>"><?php echo $row->nombre_actividad ?></option>;
                <?php
            }
            //  echo json_encode($options);
        }
    }

    function get_activity_by_group($id)
    {
        $this->load->model('actividades_model');
        $query = $this->actividades_model->get_activity_by_group($id);
        return $query;
    }

    public function read($id)
    {
        $row = $this->Actividades_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'grupo_id' => $row->grupo_id,
                'nombre_actividad' => $row->nombre_actividad,
            );


            $data['menuactivo'] = 'actividades';
            $data['title'] = 'actividades';
            $data['vista'] = $this->load->view('actividades_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('actividades'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('actividades/create_action'),
            'id' => set_value('id'),
            'grupo_id' => set_value('grupo_id'),
            'nombre_actividad' => set_value('nombre_actividad'),
        );


        $data['menuactivo'] = 'actividades';
        $data['controlador'] = 'directivo';
        $data['title'] = 'actividades';
        $data['vista'] = $this->load->view('actividades_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'grupo_id' => $this->input->post('grupo_id', TRUE),
                'nombre_actividad' => $this->input->post('nombre_actividad', TRUE),
            );

            $this->Actividades_model->insert($data);
            $this->session->set_flashdata('message', 'Activiad creada correctamente');
            redirect(site_url('actividades'));
        }
    }

    public function update($id)
    {
        $row = $this->Actividades_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('actividades/update_action'),
                'id' => set_value('id', $row->id),
                'grupo_id' => set_value('grupo_id', $row->grupo_id),
                'nombre_actividad' => set_value('nombre_actividad', $row->nombre_actividad),
            );


            $data['menuactivo'] = 'actividades';
            $data['controlador'] = 'directivo';
            $data['title'] = 'actividades';
            $data['vista'] = $this->load->view('actividades_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('actividades'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'grupo_id' => $this->input->post('grupo_id', TRUE),
                'nombre_actividad' => $this->input->post('nombre_actividad', TRUE),
            );

            $this->Actividades_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Actividad actualizado');
            redirect(site_url('actividades'));
        }
    }

    public function delete($id)
    {
        $row = $this->Actividades_model->get_by_id($id);

        if ($row) {
            $this->Actividades_model->delete($id);
            $this->session->set_flashdata('message', 'Actividad eliminada');
            redirect(site_url('actividades'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('actividades'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('grupo_id', 'grupo id', 'trim|required');
        $this->form_validation->set_rules('nombre_actividad', 'nombre actividad', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Actividades.php */
/* Location: ./application/controllers/Actividades.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-31 13:20:29 */
/* http://harviacode.com */