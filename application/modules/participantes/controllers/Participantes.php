<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Participantes extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_participantes');
        $this->load->library('form_validation');
    }

    public function get_all()
    {
        return $this->Mdl_participantes->get_all();
    }
    public function get_by_user_id($user_id,$grupo_id)
    {
        $result = false;
        $query = $this->Mdl_participantes->get_by_user_id($user_id,$grupo_id);
        if($query != null)$result = true;
        return $result;
    }

    function duplicar($id)
    {
        $prod = $this->get_by_id($id);
        $prod2 = $prod;
        unset($prod2->id);

        foreach ($prod2 as $key => $item) {
            if (!$this->Mdl_participantes->field_exists($key)) {
                unset($prod2->$key);
            }
        }
        $this->Mdl_participantes->insert($prod2);
        redirect(site_url('participantes'));
    }


    public function get_by_id($id)
    {
        return $this->Mdl_participantes->get_by_id($id);
    }


    public function index()
    {
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('participantes?q=' . urlencode($q));
                $config['first_url'] = site_url('participantes?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('participantes');
                $config['first_url'] = site_url('participantes');
            }

            $config['per_page'] = 10;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Mdl_participantes->total_rows($q);
            $participantes = $this->Mdl_participantes->get_limit_data($config['per_page'], $start, $q);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'participantes_data' => $participantes,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );

            $data['menuactivo'] = 'participantes';
            $data['title'] = 'participantes';
            $data['vista'] = $this->load->view('participantes_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }

    public function read($id)
    {
        $row = $this->Mdl_participantes->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'users_id' => $row->users_id,
                'grupo_participantes_id' => $row->grupo_participantes_id,
            );


            $data['menuactivo'] = 'participantes';
            $data['title'] = 'participantes';
            $data['vista'] = $this->load->view('participantes_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('participantes'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Adicionar',
            'action' => site_url('participantes/create_action'),
            'id' => set_value('id'),
            'users_id' => Modules::run('users/get_all'),
            'grupo_participantes_id' => Modules::run('grupo_participantes/get_all'),
        );


        $data['menuactivo'] = 'participantes';
        $data['title'] = 'participantes';
        $data['vista'] = $this->load->view('participantes_form', $data, true);
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
                'grupo_participantes_id' => $this->input->post('grupo_participantes_id', TRUE),
            );
            if($this->get_by_user_id($this->input->post('users_id', TRUE),$this->input->post('grupo_participantes_id', TRUE)))
            {
                $this->session->set_flashdata('message', 'Ya este usuario pertenece a este grupo');
                redirect(site_url('participantes'));
            }
            else
            {
                $inserid = $this->Mdl_participantes->insert($data);
                $this->session->set_flashdata('message', 'Registro Insertado Correctamente');
            }



            if ($this->input->post('btnsubmit') == 'Guardar y Continuar') {
                redirect(site_url('participantes/update/' . $inserid));
            } else {
                redirect(site_url('participantes'));
            }


        }
    }

    public function update($id)
    {
        $row = $this->Mdl_participantes->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Editar',
                'action' => site_url('participantes/update_action'),
                'id' => set_value('id', $row->id),
                'users_id' =>  Modules::run('users/get_all'),
                'grupo_participantes_id' =>  Modules::run('grupo_participantes/get_all'),
            );


            $data['menuactivo'] = 'participantes';
            $data['title'] = 'participantes';
            $data['vista'] = $this->load->view('participantes_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('participantes'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'users_id' => $this->input->post('users_id', TRUE),
                'grupo_participantes_id' => $this->input->post('grupo_participantes_id', TRUE),
            );

            $this->Mdl_participantes->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Registro Actualizado');


            if ($this->input->post('btnsubmit') == 'Guardar y Continuar') {
                redirect(site_url('participantes/update/' . $this->input->post('id', TRUE)));
            } else {
                redirect(site_url('participantes'));
            }


        }
    }

    public function delete($id)
    {
        $row = $this->Mdl_participantes->get_by_id($id);

        if ($row) {
            $resultado = $this->Mdl_participantes->delete($id);
            if ($resultado["code"] != 0) {
                $this->session->set_flashdata("message", $resultado["code"] . ": " . $resultado["message"]);
            } else {
                $this->session->set_flashdata('message', 'Registro Eliminado');
            }
            redirect(site_url('participantes'));
        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('participantes'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('users_id', 'users id', 'trim|required');
        $this->form_validation->set_rules('grupo_participantes_id', 'grupo participantes id', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Participantes.php */
/* Location: ./application/controllers/Participantes.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-03-21 16:05:11 */
/* http://harviacode.com */