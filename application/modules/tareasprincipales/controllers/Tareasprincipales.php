<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tareasprincipales extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tareasprincipales_model');
        $this->load->library('form_validation');
    }

    public function index($mes = null,$ano = null)
    {
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('tareasprincipales?q=' . urlencode($q));
                $config['first_url'] = site_url('tareasprincipales?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('tareasprincipales');
                $config['first_url'] = site_url('tareasprincipales')  ;
            }

            $date = getdate(time());
            $month = $date['mon'];
            $year = $date['year'];

            if($ano != null){
                $year = $ano;
                $this->session->set_userdata('ano',$ano);
            }
            if($mes!= null)
            {
                $month = $mes;
              //  $this->session->set_userdata('mes',$mes);
            }
            $aprobadopt = Modules::run('aprobacionpt/validaAprobacion',$user->parent_id,$month,$year);
            $user_boss = Modules::run('users/get_user',$user->parent_id);//con esto verifico que el padre sea la empresa

            if($aprobadopt || $user->parent_id == 0 ||$user_boss->rol_id == 5)
            {
                $config['per_page'] = 30;
                $config['page_query_string'] = TRUE;
                $config['total_rows'] = $this->Tareasprincipales_model->total_rows($user->id,$month,$year);
                $tareasprincipales = $this->Tareasprincipales_model->get_limit_data($config['per_page'], $start, $user->id,$month,$year);

                $this->load->library('pagination');
                $this->pagination->initialize($config);

                $data = array(
                    'tareasprincipales_data' => $tareasprincipales,
                    'q' => $q,
                    'pagination' => $this->pagination->create_links(),
                    'total_rows' => $config['total_rows'],
                    'start' => $start,
                    'mes' => $month,
                    'year' => $year,
                );

                $data['menuactivo'] = 'tareasprincipales';
                $data['controlador'] = 'users';
                $data['title'] = 'tareasprincipales';
                $data['vista'] = $this->load->view('tareasprincipales_list', $data, true);
                echo Modules::run('admintemplate/one_col', $data);
            }
            else
            {
                $this->session->set_flashdata('message', 'Su jefe inmediato aun no tiene su plan de trabajo aprobado para este mes, no puede crear las tareas principales.');
                $this->session->unset_userdata('mes');
                $this->session->unset_userdata('ano');
                redirect('predefinidas');
            }


        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }
    public function get_prinipal_task($user_id,$month,$year)
    {
        return $this->Tareasprincipales_model->get_prinipal_task($user_id,$month,$year);
    }

    public function read($id) 
    {
        $row = $this->Tareasprincipales_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'users_id' => $row->users_id,
		'tarea' => $row->tarea,
		'mes' => $row->mes,
		'ano' => $row->ano,
	    );
            


        $data['menuactivo'] = 'tareasprincipales';
        $data['title'] = 'tareasprincipales';
        $data['vista'] = $this->load->view('tareasprincipales_read', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tareasprincipales'));
        }
    }

    public function create($mes = null)
    {
        $user = $this->session->userdata('usuario');
        $date = getdate(time());
        $month = $date['mon'];
        $year = $date['year'];
        $ano = $this->session->userdata('ano');
        if($ano != null)$year = $ano;
        if($mes != null)$month = $mes;
        $data = array(
            'button' => 'Crear',
            'action' => site_url('tareasprincipales/create_action'),
            'id' => set_value('id'),
            'users_id' => $user->id,
            'tarea' => set_value('tarea'),
            'mes' => $month,
            'ano' => $year,
	);
        


        $data['menuactivo'] = 'tareasprincipales';
        $data['controlador'] = 'users';
        $data['title'] = 'tareasprincipales';
        $data['vista'] = $this->load->view('tareasprincipales_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create($this->input->post('mes',TRUE));
        } else {
            $data = array(
            'users_id' => $this->input->post('users_id',TRUE),
            'tarea' => $this->input->post('tarea',TRUE),
            'mes' => $this->input->post('mes',TRUE),
            'ano' => $this->input->post('ano',TRUE),
            );

            $this->Tareasprincipales_model->insert($data);
            $this->session->set_flashdata('message', 'Tarea creada correctamente');
            redirect(site_url('tareasprincipales/index/'.$data['mes'].'/'.$data['ano']));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tareasprincipales_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('tareasprincipales/update_action'),
                'id' => set_value('id', $row->id),
                'users_id' => set_value('users_id', $row->users_id),
                'tarea' => set_value('tarea', $row->tarea),
                'mes' => set_value('mes', $row->mes),
                'ano' => set_value('ano', $row->ano),
	    );
            


        $data['menuactivo'] = 'tareasprincipales';
        $data['controlador'] = 'users';
        $data['title'] = 'tareasprincipales';
        $data['vista'] = $this->load->view('tareasprincipales_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('tareasprincipales'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
            'users_id' => $this->input->post('users_id',TRUE),
            'tarea' => $this->input->post('tarea',TRUE),
            'mes' => $this->input->post('mes',TRUE),
            'ano' => $this->input->post('ano',TRUE),
	    );

            $this->Tareasprincipales_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Tarea actualizada');
            redirect(site_url('tareasprincipales/index/'.$data['mes'].'/'.$data['ano']));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Tareasprincipales_model->get_by_id($id);
        $mes = $this->session->userdata('mes');
        $year = $this->session->userdata('ano');
        if ($row) {
            $this->Tareasprincipales_model->delete($id);
            $this->session->set_flashdata('message', 'Tarea eliminada correctamente');
            redirect(site_url('tareasprincipales/index/'.$mes.'/'.$year));
        } else {
            $this->session->set_flashdata('message', 'Tarea no encontrada');
            redirect(site_url('tareasprincipales/index/'.$mes.'/'.$year));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('users_id', 'users id', 'trim|required');
	$this->form_validation->set_rules('tarea', 'tarea', 'trim|required');
	$this->form_validation->set_rules('mes', 'mes', 'trim|required');
	$this->form_validation->set_rules('ano', 'ano', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Tareasprincipales.php */
/* Location: ./application/controllers/Tareasprincipales.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-15 22:19:24 */
/* http://harviacode.com */