<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Grupo_participantes extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_grupo_participantes');
        $this->load->library('form_validation');
    }

    public function get_all()
    {
        return $this->Mdl_grupo_participantes->get_all();
    }
    public function valida_data($string)
    {
        $result = false;
        $query = $this->Mdl_grupo_participantes->valida_data($string);
        if($query!= null)$result = true;
        return $result;
    }
    public function get_group_by_user($user_id)//este metodo lo llamo desde el Pt
    {
        return $this->Mdl_grupo_participantes->get_group_by_user($user_id);
    }
    public function get_by_flujo_id($flujo_id)
    {
        return $this->Mdl_grupo_participantes->get_by_flujo_id($flujo_id);
    }

    function duplicar($id)
    {
        $prod = $this->get_by_id($id);
        $prod2 = $prod;
        unset($prod2->id);

        foreach ($prod2 as $key => $item) {
            if (!$this->Mdl_grupo_participantes->field_exists($key)) {
                unset($prod2->$key);
            }
        }
        $this->Mdl_grupo_participantes->insert($prod2);
        redirect(site_url('grupo_participantes'));
    }


    public function get_by_id($id)
    {
        return $this->Mdl_grupo_participantes->get_by_id($id);
    }


    public function index()
    {
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('grupo_participantes?q=' . urlencode($q));
                $config['first_url'] = site_url('grupo_participantes?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('grupo_participantes');
                $config['first_url'] = site_url('grupo_participantes');
            }

            $config['per_page'] = 10;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Mdl_grupo_participantes->total_rows($q);
            $grupo_participantes = $this->Mdl_grupo_participantes->get_limit_data($config['per_page'], $start, $q);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'grupo_participantes_data' => $grupo_participantes,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );

            $data['menuactivo'] = 'grupo_participantes';
           // $data['controlador'] = 'directivo';
            $data['title'] = 'grupo_participantes';
            $data['vista'] = $this->load->view('grupo_participantes_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }

    public function read($id)
    {
        $row = $this->Mdl_grupo_participantes->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'titulo' => $row->titulo,
            );


            $data['menuactivo'] = 'grupo_participantes';
          //  $data['controlador'] = 'directivo';
            $data['title'] = 'grupo_participantes';
            $data['vista'] = $this->load->view('grupo_participantes_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('grupo_participantes'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Adicionar',
            'action' => site_url('grupo_participantes/create_action'),
            'id' => set_value('id'),
            'titulo' => set_value('titulo'),
        );


        $data['menuactivo'] = 'grupo_participantes';
      //  $data['controlador'] = 'directivo';
        $data['title'] = 'grupo_participantes';
        $data['vista'] = $this->load->view('grupo_participantes_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'titulo' => $this->input->post('titulo', TRUE),
            );
            if($this->valida_data($this->input->post('titulo', TRUE)))
            {
                $this->session->set_flashdata('message', 'Ya existe un grupo con este nombre');
                redirect(site_url('grupo_participantes'));
            }
            else
            {
                $inserid = $this->Mdl_grupo_participantes->insert($data);
                $this->session->set_flashdata('message', 'Registro Insertado Correctamente');

            }

            if ($this->input->post('btnsubmit') == 'Guardar y Continuar') {
                redirect(site_url('grupo_participantes/update/' . $inserid));
            } else {
                redirect(site_url('grupo_participantes'));
            }


        }
    }

    public function update($id)
    {
        $row = $this->Mdl_grupo_participantes->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Editar',
                'action' => site_url('grupo_participantes/update_action'),
                'id' => set_value('id', $row->id),
                'titulo' => set_value('titulo', $row->titulo),
            );


            $data['menuactivo'] = 'grupo_participantes';
          //  $data['controlador'] = 'directivo';
            $data['title'] = 'grupo_participantes';
            $data['vista'] = $this->load->view('grupo_participantes_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('grupo_participantes'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'titulo' => $this->input->post('titulo', TRUE),
            );

            $this->Mdl_grupo_participantes->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Registro Actualizado');


            if ($this->input->post('btnsubmit') == 'Save and Continue') {
                redirect(site_url('grupo_participantes/update/' . $this->input->post('id', TRUE)));
            } else {
                redirect(site_url('grupo_participantes'));
            }


        }
    }

    public function delete($id)
    {
        $row = $this->Mdl_grupo_participantes->get_by_id($id);

        if ($row) {
            $resultado = $this->Mdl_grupo_participantes->delete($id);
            if ($resultado["code"] != 0) {
                $this->session->set_flashdata("message", $resultado["code"] . ": " . $resultado["message"]);
            } else {
                $this->session->set_flashdata('message', 'Registro Eliminado');
            }
            redirect(site_url('grupo_participantes'));
        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('grupo_participantes'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('titulo', 'titulo', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Grupo_participantes.php */
/* Location: ./application/controllers/Grupo_participantes.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-03-21 16:03:52 */
/* http://harviacode.com */