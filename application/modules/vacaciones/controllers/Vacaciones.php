<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Vacaciones extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Vacaciones_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user = $this->session->userdata('usuario');
        if ($user != null) {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('vacaciones?q=' . urlencode($q));
                $config['first_url'] = site_url('vacaciones?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('vacaciones');
                $config['first_url'] = site_url('vacaciones');
            }

            $config['per_page'] = 30;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Vacaciones_model->total_rows($q,$user->id);
            $vacaciones = $this->Vacaciones_model->get_limit_data($config['per_page'], $start, $q,$user->id);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'vacaciones_data' => $vacaciones,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );

            $data['menuactivo'] = 'vacaciones';
            $data['controlador'] = 'users';
            $data['title'] = 'vacaciones';
            $data['vista'] = $this->load->view('vacaciones_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            redirect('auth/login', 'refresh');
        }
    }

    public function read($id)
    {
        $row = $this->Vacaciones_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'users_id' => $row->users_id,
                'desde' => $row->desde,
                'hasta' => $row->hasta,
            );


            $data['menuactivo'] = 'vacaciones';
            $data['title'] = 'vacaciones';
            $data['vista'] = $this->load->view('vacaciones_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('vacaciones'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('vacaciones/create_action'),
            'id' => set_value('id'),
            'desde' => set_value('desde'),
            'hasta' => set_value('hasta'),
        );


        $data['menuactivo'] = 'vacaciones';
        $data['controlador'] = 'users';
        $data['title'] = 'vacaciones';
        $data['vista'] = $this->load->view('vacaciones_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'users_id' => $this->input->post('users_id', TRUE),
                'desde' => Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('desde', TRUE)) ,
                'hasta' => Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('hasta', TRUE)),
            );
            $user = $this->session->userdata('usuario');
            $data['users_id'] = $user->id;
            $this->Vacaciones_model->insert($data);
            $this->session->set_flashdata('message', 'Vacaciones creadas correctamente');
            redirect(site_url('vacaciones'));
        }
    }

    public function update($id)
    {
        $row = $this->Vacaciones_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('vacaciones/update_action'),
                'id' => set_value('id', $row->id),
                'users_id' => set_value('users_id', $row->users_id),
                'desde' => set_value('desde',Modules::run('adminsettings/fecha_formato_esp',$row->desde) ),
                'hasta' => set_value('hasta', Modules::run('adminsettings/fecha_formato_esp',$row->hasta)),
               // 'desde' => set_value('desde',$row->desde ),
               // 'hasta' => set_value('hasta', $row->hasta),
            );


            $data['menuactivo'] = 'vacaciones';
            $data['controlador'] = 'users';
            $data['title'] = 'vacaciones';
            $data['vista'] = $this->load->view('vacaciones_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('vacaciones'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'desde' => Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('desde', TRUE)),
                'hasta' => Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('hasta', TRUE)),
            );

            $this->Vacaciones_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Vacaciones Actualizadas');
            redirect(site_url('vacaciones'));
        }
    }

    public function delete($id)
    {
        $row = $this->Vacaciones_model->get_by_id($id);

        if ($row) {
            $this->Vacaciones_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('vacaciones'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('vacaciones'));
        }
    }
    public function get_vacation($month,$year,$user_id)
    {
        return $this->Vacaciones_model->get_vacation($month,$year,$user_id);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('desde', 'desde', 'trim|required');
        $this->form_validation->set_rules('hasta', 'hasta', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Vacaciones.php */
/* Location: ./application/controllers/Vacaciones.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-08-09 14:51:27 */
/* http://harviacode.com */