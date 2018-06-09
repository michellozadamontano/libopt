<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tareas_incumplidas extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Tareas_incumplidas_model');
        $this->load->library('form_validation');
       // $this->load->library('html2pdf');
    }

    public function index()
    {
        $user = $this->session->userdata('usuario');
        if($user == null)
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('tareas_incumplidas?q=' . urlencode($q));
                $config['first_url'] = site_url('tareas_incumplidas?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('tareas_incumplidas');
                $config['first_url'] = site_url('tareas_incumplidas')  ;
            }

            $date = getdate(time());
            $month = $date['mon'] - 1;
            $year = $date['year'];
            $config['per_page'] = 30;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Tareas_incumplidas_model->total_rows($user->id,$month,$year);
            $tareas_incumplidas = $this->Tareas_incumplidas_model->get_limit_data($config['per_page'], $start,$user->id,$month,$year);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'tareas_incumplidas_data' => $tareas_incumplidas,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );

            $data['menuactivo'] = 'tareas_incumplidas';
            $data['controlador'] = 'users';
            $data['title'] = 'tareas_incumplidas';
            $data['vista'] = $this->load->view('tareas_incumplidas_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }

    }

    public function read($id) 
    {
        $row = $this->Tareas_incumplidas_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'users_id' => $row->users_id,
		'pt_id' => $row->pt_id,
		'quien_origino' => $row->quien_origino,
		'causas' => $row->causas,
		'incumplida' => $row->incumplida,
		'suspendida' => $row->suspendida,
	    );
            


        $data['menuactivo'] = 'tareas_incumplidas';
        $data['title'] = 'tareas_incumplidas';
        $data['vista'] = $this->load->view('tareas_incumplidas_read', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tareas_incumplidas'));
        }
    }

    public function create() 
    {
        $user = $this->session->userdata('usuario');
        $data = array(
            'button' => 'Crear',
            'action' => site_url('tareas_incumplidas/create_action'),
            'id' => set_value('id'),
            'users_id' => $user->id,
            'pt_id' => set_value('pt_id'),
            'quien_origino' => set_value('quien_origino'),
            'causas' => set_value('causas'),

	);
        


        $data['menuactivo'] = 'tareas_incumplidas';
        $data['controlador'] = 'users';
        $data['title'] = 'tareas_incumplidas';
        $data['vista'] = $this->load->view('tareas_incumplidas_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }
    
    public function create_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
            'users_id' => $this->input->post('users_id',TRUE),
            'pt_id' => $this->input->post('list_task',TRUE),
            'quien_origino' => $this->input->post('quien_origino',TRUE),
            'causas' => $this->input->post('causas',TRUE),
	    );
            $incumplida = $this->input->post('optionsRadios');
            if($incumplida == 'option1')
            {
                $data['incumplida'] = true;
                $data['suspendida'] = false;
            }
            else
            {
                $data['incumplida'] = false;
                $data['suspendida'] = true;
            }

            $this->Tareas_incumplidas_model->insert($data);
            $this->session->set_flashdata('message', 'Datos Insertado Correctamente');
            redirect(site_url('tareas_incumplidas'));
        }
    }
    
    public function update($id) 
    {
        $row = $this->Tareas_incumplidas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('tareas_incumplidas/update_action'),
                'id' => set_value('id', $row->id),
                'users_id' => set_value('users_id', $row->users_id),
                'pt_id' => set_value('pt_id', $row->pt_id),
                'quien_origino' => set_value('quien_origino', $row->quien_origino),
                'causas' => set_value('causas', $row->causas),

	    );
        $data['menuactivo'] = 'tareas_incumplidas';
        $data['controlador'] = 'users';
        $data['title'] = 'tareas_incumplidas';
        $data['vista'] = $this->load->view('tareas_incumplidas_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tareas_incumplidas'));
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
            'pt_id' => $this->input->post('list_task',TRUE),
            'quien_origino' => $this->input->post('quien_origino',TRUE),
            'causas' => $this->input->post('causas',TRUE),
            );
            $incumplida = $this->input->post('optionsRadios');
            if($incumplida == 'option1')
            {
                $data['incumplida'] = true;
                $data['suspendida'] = false;
            }
            else
            {
                $data['incumplida'] = false;
                $data['suspendida'] = true;
            }

            $this->Tareas_incumplidas_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('tareas_incumplidas'));
        }
    }
    public function mes_actual_incumplida($pt_id)//id del plan de trabajo
    {
        $user = $this->session->userdata('usuario');
        $data = array(
            'button' => 'Crear',
            'action' => site_url('tareas_incumplidas/mes_actual_action'),
            'id' => set_value('id'),
            'users_id' => $user->id,
            'pt_id' => $pt_id,
            'quien_origino' => set_value('quien_origino'),
            'causas' => set_value('causas'),

        );
        $data['menuactivo'] = 'tareas_incumplidas';
        $data['controlador'] = 'users';

        $data['obj_actividad'] = Modules::run('pt/get_by_id',$pt_id);
        $data['vista'] = $this->load->view('incumplidas_mes_actual', $data, true);
        echo Modules::run('admintemplate/one_col', $data);

    }
    public function mes_actual_action() //aqui inserto las tareas incumplidas o suspendidas del mes en curso.
    {
        $this->_rules();
        $pt_id = $this->input->post('pt_id',TRUE);
        if ($this->form_validation->run() == FALSE) {
            $this->mes_actual_incumplida($pt_id);
        } else {
            $data = array(
                'users_id' => $this->input->post('users_id',TRUE),
                'pt_id' => $this->input->post('pt_id',TRUE),
                'quien_origino' => $this->input->post('quien_origino',TRUE),
                'causas' => $this->input->post('causas',TRUE),
            );
            $incumplida = $this->input->post('optionsRadios');
            if($incumplida == 'option1')
            {
                $data['incumplida'] = true;
                $data['suspendida'] = false;
            }
            else
            {
                $data['incumplida'] = false;
                $data['suspendida'] = true;
            }
            $obj_valida = $this->Tareas_incumplidas_model->valida_incumplida($pt_id);
            if(count($obj_valida)>0)
            {
                $this->Tareas_incumplidas_model->update($obj_valida->id, $data);
            }
            else
            {
                $this->Tareas_incumplidas_model->insert($data);
            }


            $this->session->set_flashdata('message', 'Datos Insertado Correctamente');

            redirect(base_url('pt/get_dias_vencidos'));
        }
    }
    public function generarmodelo()
    {
        //aqui voy a generar el segundo modelo del plan de trabajo y listo mate el forum
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $date = getdate(time());
            $month = $date['mon'] - 1;
            $year = $date['year'];
            if($month == 0)$year--;
            $data['menuactivo'] = 'modelo';
            $data['controlador'] = 'users';
            $data['mes'] = $this->translate_month($month);
            $data['year'] = $year;

            $tareas = Modules::run('pt/tareas_mes',$user->id,$month,$year);
            $total_sinhacer = $this->Tareas_incumplidas_model->total_rows($user->id,$month,$year);
            $total_incumplidas = $this->Tareas_incumplidas_model->total_incumplidas($user->id,$month,$year);
            $total_suspendidas = $this->Tareas_incumplidas_model->total_suspendidas($user->id,$month,$year);
            $tareas_nuevas = Modules::run('nuevas_tareas/tareas_month',$user->id,$month,$year);

            $data['planificadas'] = $tareas;
            $data['cumplidas'] = $tareas - $total_sinhacer;
            $data['t_incumplidas'] = count($total_incumplidas);
            $data['t_suspendidas'] = count($total_suspendidas);
            $data['t_tareasnuevas'] = count($tareas_nuevas);
            $data['incumplidas'] = $total_incumplidas;
            $data['suspendidas'] = $total_suspendidas;
            $data['tareasnuevas'] = $tareas_nuevas;
            $analisis = Modules::run('analisis/get_analisis',$user->id,$month,$year);
            $cualitativo = "";
            if($analisis!= "")
            {
                $cualitativo = $analisis->analisis;
            }
            $data['analisis'] = $cualitativo;
            $data['user'] = $user;
            $data['title'] = 'modelo';
            $data['vista'] = $this->load->view('tareas_incumplidas_modelo', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }
    public function reportes()
    {
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $data['menuactivo'] = 'reportes';
            $data['controlador'] = 'users';

            $data['planes_proved'] = Modules::run('aprobacionpt/get_user_plan',$user->id);
            $data['vista'] = $this->load->view('reportes', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }
    }
    public function model_pdf($mes,$anno)
    {
        //este es el modelo 2
        //require_once APPPATH.'./third_party/mpdf/vendor/autoload.php';
        require_once APPPATH.'./third_party/mpdf60/mpdf.php';
        $mpdf = new mPDF('utf-8','A3',12);;
        //hacemos que coja la vista como datos a imprimir
        //aqui voy a generar el segundo modelo del plan de trabajo y listo mate el forum
        $user = $this->session->userdata('usuario');
        $date = getdate(time());

        $month = ($mes == 1) ? 12 : $mes - 1;
        $year = ($mes == 1) ? $anno - 1 : $anno;
        $data['menuactivo'] = 'modelo';
        $data['controlador'] = 'users';
        $data['mes'] = $this->translate_month($month);
        $data['year'] = $year;

        $tareas = Modules::run('pt/tareas_mes',$user->id,$month,$year);
        $total_sinhacer = $this->Tareas_incumplidas_model->total_rows($user->id,$month,$year);
        $total_incumplidas = $this->Tareas_incumplidas_model->total_incumplidas($user->id,$month,$year);
        $total_suspendidas = $this->Tareas_incumplidas_model->total_suspendidas($user->id,$month,$year);
        $tareas_nuevas = Modules::run('nuevas_tareas/tareas_month',$user->id,$month,$year);

        $data['planificadas'] = $tareas;
        $data['cumplidas'] = $tareas - $total_sinhacer;
        $data['t_incumplidas'] = count($total_incumplidas);
        $data['t_suspendidas'] = count($total_suspendidas);
        $data['t_tareasnuevas'] = count($tareas_nuevas);
        $data['incumplidas'] = $total_incumplidas;
        $data['suspendidas'] = $total_suspendidas;
        $data['tareasnuevas'] = $tareas_nuevas;
        $analisis = Modules::run('analisis/get_analisis',$user->id,$month,$year);
        $cualitativo = "";
        if($analisis!= "")
        {
            $cualitativo = $analisis->analisis;
        }
        $data['analisis'] = $cualitativo;
        $data['user'] = $user;
        $data['title'] = 'modelo';
        //importante utf8_decode para mostrar bien las tildes, ñ y demás

        $view = $this->load->view('tareas_incumplidas_modelo',$data,true);

        $mpdf->AddPage('L');
        $mpdf->WriteHTML($view);
        $mpdf->Output();
    }
    function translate_month($month)
    {
        $mes=strftime('%B',mktime(0,0,0,$month));

        if ($mes=="January") $mes="Enero";
        if ($mes=="February") $mes="Febrero";
        if ($mes=="March") $mes="Marzo";
        if ($mes=="April") $mes="Abril";
        if ($mes=="May") $mes="Mayo";
        if ($mes=="June") $mes="Junio";
        if ($mes=="July") $mes="Julio";
        if ($mes=="August") $mes="Agosto";
        if ($mes=="September") $mes="Septiembre";
        if ($mes=="October") $mes="Octubre";
        if ($mes=="November") $mes="Noviembre";
        if ($mes=="December") $mes="Diciembre";
        return $mes;
    }
    
    public function delete($id) 
    {
        $row = $this->Tareas_incumplidas_model->get_by_id($id);

        if ($row) {
            $this->Tareas_incumplidas_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('tareas_incumplidas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('tareas_incumplidas'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('users_id', 'users id', 'trim|required');
	$this->form_validation->set_rules('quien_origino', 'quien origino', 'trim|required');
	$this->form_validation->set_rules('causas', 'causas', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }


}

/* End of file Tareas_incumplidas.php */
/* Location: ./application/controllers/Tareas_incumplidas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-11 03:27:45 */
/* http://harviacode.com */