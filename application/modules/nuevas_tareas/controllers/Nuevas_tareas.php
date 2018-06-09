<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nuevas_tareas extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Nuevas_tareas_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('nuevas_tareas?q=' . urlencode($q));
                $config['first_url'] = site_url('nuevas_tareas?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('nuevas_tareas');
                $config['first_url'] = site_url('nuevas_tareas')  ;
            }

            $date = getdate(time());
            $month = $date['mon'];
            $year = $date['year'];

            $config['per_page'] = 30;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Nuevas_tareas_model->total_rows($user->id,$month,$year);
            $nuevas_tareas = $this->Nuevas_tareas_model->get_limit_data($config['per_page'], $start, $user->id,$month,$year);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'nuevas_tareas_data' => $nuevas_tareas,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );

            $data['menuactivo'] = 'nuevas_tareas';
            $data['controlador'] = 'users';
            $data['title'] = 'nuevas_tareas';
            $data['vista'] = $this->load->view('nuevas_tareas_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }

    public function read($id) 
    {
        $row = $this->Nuevas_tareas_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'users_id' => $row->users_id,
		'nuevatarea' => $row->nuevatarea,
		'quien_origino' => $row->quien_origino,
		'causas' => $row->causas,
		'fecha' => $row->fecha,
	    );
            


        $data['menuactivo'] = 'nuevas_tareas';
        $data['title'] = 'nuevas_tareas';
        $data['vista'] = $this->load->view('nuevas_tareas_read', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('nuevas_tareas'));
        }
    }

    public function create() 
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('nuevas_tareas/create_action'),
            'id' => set_value('id'),
          //  'users_id' => set_value('users_id'),
            'nuevatarea' => set_value('nuevatarea'),
            'quien_origino' => set_value('quien_origino'),
            'causas' => set_value('causas'),
            'fecha' => set_value('fecha',Modules::run('adminsettings/fecha_formato_esp',date('Y-m-d'))),
            'hora_ini' => set_value('hora_ini','08:00'),
            'hora_fin' => set_value('hora_fin','08:30'),
            );


        $data['menuactivo'] = 'nuevas_tareas';
        $data['controlador'] = 'users';
        $data['title'] = 'nuevas_tareas';
        $data['vista'] = $this->load->view('nuevas_tareas_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $user = $this->session->userdata('usuario');
            $date = getdate(time());
            $month = $date['mon'];
            $year = $date['year'];
            $data = array(
            'users_id' => $user->id,
            'nuevatarea' => $this->input->post('nuevatarea',TRUE),
            'quien_origino' => $this->input->post('quien_origino',TRUE),
            'causas' => $this->input->post('causas',TRUE),
            'fecha' => Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('fecha', TRUE)),
            'hora_ini' => $this->input->post('hora',TRUE),
            'hora_fin' => $this->input->post('horaf',TRUE),
            'mes' => $month,
            'ano'=>$year,
            );
            $explode_fecha = explode("/", $this->input->post('fecha', TRUE), 3);
            if($month != $explode_fecha[1])
            {
                $this->session->set_flashdata('message', 'La tarea tiene que estar en el mes en curso');
                redirect('nuevas_tareas/create');
            }
            if (Modules::run('aprobacionpt/validaAprobacion', $user->id, $month, $year)) {
                $this->Nuevas_tareas_model->insert($data);
                $this->session->set_flashdata('message', 'Tarea creada correctamente');
                redirect(site_url('nuevas_tareas'));
            }
            else{
                $this->session->set_flashdata('message', 'Este PT aun no ha sido aprobado por tanto no se puede crear tareas extras.');
                redirect('welcome/index/' . $month . '/' . $year);
            }

        }
    }
    public function tarea_de_orden_superior($data) //por aqui le paso los datos que vienen desde arriba
    {
        //este metodo es para en caso de que esta aprobado el pt del usuario las nuevas tareas entonces caen por aqui
        $date = getdate(time());
        $month = $date['mon'];
        $year = $date['year'];
        $data['mes'] = $month;
        $data['ano'] = $year;
        $this->Nuevas_tareas_model->insert($data);
    }

    
    public function update($id) 
    {
        $row = $this->Nuevas_tareas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('nuevas_tareas/update_action'),
                'id' => set_value('id', $row->id),
                'nuevatarea' => set_value('nuevatarea', $row->nuevatarea),
                'quien_origino' => set_value('quien_origino', $row->quien_origino),
                'causas' => set_value('causas', $row->causas),
                'mes' => set_value('mes', $row->mes),
                'ano' => set_value('ano', $row->ano),
                'hora_ini' => set_value('hora_ini', $row->hora_ini),
                'hora_fin' => set_value('hora_fin', $row->hora_fin),
                'fecha' => Modules::run('adminsettings/fecha_formato_esp',$row->fecha),
	    );


        $data['menuactivo'] = 'nuevas_tareas';
        $data['controlador'] = 'users';
        $data['title'] = 'nuevas_tareas';
        $data['vista'] = $this->load->view('nuevas_tareas_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Tarea no encontrada');
            redirect(site_url('nuevas_tareas'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $date = getdate(time());
            $month = $date['mon'];
            $year = $date['year'];
            $user = $this->session->userdata('usuario');
            $data = array(
            'users_id' => $user->id,
            'nuevatarea' => $this->input->post('nuevatarea',TRUE),
            'quien_origino' => $this->input->post('quien_origino',TRUE),
            'causas' => $this->input->post('causas',TRUE),
            'fecha' => Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('fecha', TRUE)),
            'hora_ini' => $this->input->post('hora',TRUE),
            'hora_fin' => $this->input->post('horaf',TRUE),
            );


            $explode_fecha = explode("/", $this->input->post('fecha', TRUE), 3);
            if($month != $explode_fecha[1])
            {
                $this->session->set_flashdata('message', 'La tarea tiene que estar en el mes en curso');
                redirect('nuevas_tareas/create');
            }
            if (Modules::run('aprobacionpt/validaAprobacion', $user->id, $month, $year)) {
                $this->Nuevas_tareas_model->update($this->input->post('id', TRUE), $data);
                $this->session->set_flashdata('message', 'Tarea actualizada');
                redirect(site_url('nuevas_tareas'));
            }
            else{
                $this->session->set_flashdata('message', 'Este PT aun no ha sido aprobado por tanto no se puede crear tareas extras.');
                redirect('welcome/index/' . $month . '/' . $year);
            }


        }
    }
    public function tareas_month($user_id,$month,$year)
    {
        return $this->Nuevas_tareas_model->tareas_month($user_id,$month,$year);
    }
    
    public function delete($id) 
    {
        $row = $this->Nuevas_tareas_model->get_by_id($id);

        if ($row) {
            $this->Nuevas_tareas_model->delete($id);
            $this->session->set_flashdata('message', 'Tarea Eliminada');
            redirect(site_url('nuevas_tareas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('nuevas_tareas'));
        }
    }
    /* esta funcion es para mostrar las tareas extras en el plan de trabajo*/
    function get_plan($date)
    {
        $date = Modules::run('adminsettings/convertDateToMsSQL',$date);
        $usuario = $this->session->userdata('usuario');
        $user_id = $usuario->id;

        $data['query'] = $this->Nuevas_tareas_model->get_where_date($date, $user_id);
        $this->load->view('extra_task', $data);
    }

    public function _rules() 
    {

	$this->form_validation->set_rules('nuevatarea', 'nuevatarea', 'trim|required');
	$this->form_validation->set_rules('quien_origino', 'quien origino', 'trim|required');
	$this->form_validation->set_rules('causas', 'causas', 'trim|required');
    $this->form_validation->set_rules('fecha', 'fecha', 'trim|required');
    $this->form_validation->set_rules('hora', 'hora inicial', 'trim|required');
    $this->form_validation->set_rules('horaf', 'hora final', 'trim|required');
	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Nuevas_tareas.php */
/* Location: ./application/controllers/Nuevas_tareas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-14 14:30:30 */
/* http://harviacode.com */