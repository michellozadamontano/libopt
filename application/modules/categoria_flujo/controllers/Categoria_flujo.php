<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Categoria_flujo extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_categoria_flujo');
        $this->load->library('form_validation');
    }

    public function get_all()
    {
        return $this->Mdl_categoria_flujo->get_all();
    }
    public function valida_data($string)
    {
        $result = false;
        $query = $this->Mdl_categoria_flujo->valida_data($string);
        if($query!= null)$result = true;
        return $result;
    }

    function duplicar($id)
    {
        $prod = $this->get_by_id($id);
        $prod2 = $prod;
        unset($prod2->id);

        foreach ($prod2 as $key => $item) {
            if (!$this->Mdl_categoria_flujo->field_exists($key)) {
                unset($prod2->$key);
            }
        }
        $this->Mdl_categoria_flujo->insert($prod2);
        redirect(site_url('categoria_flujo'));
    }


    public function get_by_id($id)
    {
        return $this->Mdl_categoria_flujo->get_by_id($id);
    }


    public function index()
    {
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('categoria_flujo?q=' . urlencode($q));
                $config['first_url'] = site_url('categoria_flujo?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('categoria_flujo');
                $config['first_url'] = site_url('categoria_flujo');
            }

            $config['per_page'] = 10;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Mdl_categoria_flujo->total_rows($q);
            $categoria_flujo = $this->Mdl_categoria_flujo->get_limit_data($config['per_page'], $start, $q);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'categoria_flujo_data' => $categoria_flujo,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );

            $data['menuactivo'] = 'categoria_flujo';
           // $data['controlador'] = 'directivo';
            $data['title'] = 'categoria_flujo';
            $data['vista'] = $this->load->view('categoria_flujo_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }

    public function read($id)
    {
        $row = $this->Mdl_categoria_flujo->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'nombre' => $row->nombre,
            );


            $data['menuactivo'] = 'categoria_flujo';
            $data['controlador'] = 'directivo';
            $data['title'] = 'categoria_flujo';
            $data['vista'] = $this->load->view('categoria_flujo_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('categoria_flujo'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Adicionar',
            'action' => site_url('categoria_flujo/create_action'),
            'id' => set_value('id'),
            'nombre' => set_value('nombre'),
        );


        $data['menuactivo'] = 'categoria_flujo';
       // $data['controlador'] = 'directivo';
        $data['title'] = 'categoria_flujo';
        $data['vista'] = $this->load->view('categoria_flujo_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre', TRUE),
            );
            if($this->valida_data($this->input->post('nombre', TRUE)))
            {
                $this->session->set_flashdata('message', 'Ya existe una categoria con este nombre');
                redirect(site_url('categoria_flujo'));
            }

            $inserid = $this->Mdl_categoria_flujo->insert($data);
            $this->session->set_flashdata('message', 'Registro Insertado Correctamente');


            if ($this->input->post('btnsubmit') == 'Guardar y Continuar') {
                redirect(site_url('categoria_flujo/update/' . $inserid));
            } else {
                redirect(site_url('categoria_flujo'));
            }


        }
    }

    public function update($id)
    {
        $row = $this->Mdl_categoria_flujo->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Editar',
                'action' => site_url('categoria_flujo/update_action'),
                'id' => set_value('id', $row->id),
                'nombre' => set_value('nombre', $row->nombre),
            );


            $data['menuactivo'] = 'categoria_flujo';
           // $data['controlador'] = 'directivo';
            $data['title'] = 'categoria_flujo';
            $data['vista'] = $this->load->view('categoria_flujo_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('categoria_flujo'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre', TRUE),
            );

            $this->Mdl_categoria_flujo->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Registro Actualizado');


            if ($this->input->post('btnsubmit') == 'Save and Continue') {
                redirect(site_url('categoria_flujo/update/' . $this->input->post('id', TRUE)));
            } else {
                redirect(site_url('categoria_flujo'));
            }


        }
    }

    public function delete($id)
    {
        $row = $this->Mdl_categoria_flujo->get_by_id($id);

        if ($row) {
            $resultado = $this->Mdl_categoria_flujo->delete($id);
            if ($resultado["code"] != 0) {
                $this->session->set_flashdata("message", $resultado["code"] . ": " . $resultado["message"]);
            } else {
                $this->session->set_flashdata('message', 'Registro Eliminado');
            }
            redirect(site_url('categoria_flujo'));
        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('categoria_flujo'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Categoria_flujo.php */
/* Location: ./application/controllers/Categoria_flujo.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-03-21 13:51:36 */
/* http://harviacode.com */