<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analisis extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Analisis_model');
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
                $config['base_url'] = site_url('analisis?q=' . urlencode($q));
                $config['first_url'] = site_url('analisis?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('analisis');
                $config['first_url'] = site_url('analisis')  ;
            }

            $date = getdate(time());
            $month = $date['mon'];
            $year = $date['year'];
            $config['per_page'] = 30;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Analisis_model->total_rows($user->id,$year);
            $analisis = $this->Analisis_model->get_limit_data($config['per_page'], $start,$user->id,$year);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $month_name = Modules::run('adminsettings/translate_month_number',$month);
            $data = array(
                'analisis_data' => $analisis,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
                'month' => $month_name
            );

            $data['menuactivo'] = 'analisis';
            $data['controlador'] = 'users';
            $data['title'] = 'analisis';
            $data['vista'] = $this->load->view('analisis_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }

    public function read($id) 
    {
        $row = $this->Analisis_model->get_by_id($id);
        if ($row) {
            $data = array(
		'id' => $row->id,
		'users_id' => $row->users_id,
		'mes' => $row->mes,
		'ano' => $row->ano,
		'analisis' => $row->analisis,
	    );
            


        $data['menuactivo'] = 'analisis';
        $data['title'] = 'analisis';
        $data['vista'] = $this->load->view('analisis_read', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('analisis'));
        }
    }
    public function get_analisis($user_id,$month,$year)
    {
        $row = $this->Analisis_model->get_analisis($user_id,$month,$year);
        return $row;
    }

    public function create() 
    {

        $data = array(
            'button' => 'Crear',
            'action' => site_url('analisis/create_action'),
            'id' => set_value('id'),
            'mes' => set_value('mes'),
            'analisis' => set_value('analisis'),
        );
        $meses =[
            '1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril',
            '5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto',
            '9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre',
        ];
        $data['meses'] = $meses;

        $data['menuactivo'] = 'analisis';
        $data['controlador'] = 'users';
        $data['title'] = 'analisis';
        $data['vista'] = $this->load->view('analisis_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }
    public function valida_analisis($mes,$ano)
    {
        $user = $this->session->userdata('usuario');
        $date = getdate(time());
        $month = $mes;
        $year = $ano;
        $total_rows = $this->Analisis_model->CheckAnalisis($user->id,$month,$year);
        $result = false;
        if($total_rows > 0)
        {
            $result = true;
        }
        return $result;
    }
    
    public function create_action() 
    {

        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $date = getdate(time());
            $month = $this->input->post('mes',TRUE);
            $year = $date['year'];
            if($this->valida_analisis($month,$year))
            {
                $this->session->set_flashdata('message', 'Ya existe un análisis para este mes. Actualícelo!');
                redirect(site_url('analisis'));
            }
            else
            {
                $user = $this->session->userdata('usuario');


                $data = array(
                    'users_id' => $user->id,
                    'mes' => $month,
                    'ano' => $year,
                    'analisis' => $this->input->post('analisis',TRUE),
                );

                $this->Analisis_model->insert($data);
                $this->session->set_flashdata('message', 'An&aacute;lisis creado correctamente');
                redirect(site_url('analisis'));
            }

        }
    }
    
    public function update($id) 
    {
        $row = $this->Analisis_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('analisis/update_action'),
                'id' => set_value('id', $row->id),
                'mes'=> set_value('mes',$row->mes),
                'analisis' => set_value('analisis', $row->analisis),
	    );
            $meses =[
                '1'=>'Enero','2'=>'Febrero','3'=>'Marzo','4'=>'Abril',
                '5'=>'Mayo','6'=>'Junio','7'=>'Julio','8'=>'Agosto',
                '9'=>'Septiembre','10'=>'Octubre','11'=>'Noviembre','12'=>'Diciembre',
            ];
            $data['meses'] = $meses;


        $data['menuactivo'] = 'analisis';
        $data['controlador'] = 'users';
        $data['title'] = 'analisis';
        $data['vista'] = $this->load->view('analisis_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('analisis'));
        }
    }
    
    public function update_action() 
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $user = $this->session->userdata('usuario');
            $date = getdate(time());
            $month = $date['mon'];
            $year = $date['year'];
            $data = array(
                'users_id' => $user->id,
                'mes' => $month,
                'ano' => $year,
                'analisis' => $this->input->post('analisis',TRUE),
            );

            $this->Analisis_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'An&aacute;lisis Actualizado');
            redirect(site_url('analisis'));
        }
    }
    
    public function delete($id) 
    {
        $row = $this->Analisis_model->get_by_id($id);

        if ($row) {
            $this->Analisis_model->delete($id);
            $this->session->set_flashdata('message', 'An&aacute;lisis eliminado');
            redirect(site_url('analisis'));
        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('analisis'));
        }
    }

    public function _rules() 
    {
	$this->form_validation->set_rules('analisis', 'analisis', 'trim|required');

	$this->form_validation->set_rules('id', 'id', 'trim');
	$this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Analisis.php */
/* Location: ./application/controllers/Analisis.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-12 20:11:20 */
/* http://harviacode.com */