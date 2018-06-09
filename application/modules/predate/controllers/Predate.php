<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Predate extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Predate_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user = $this->session->userdata('usuario');
        if ($user != null) {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('predate?q=' . urlencode($q));
                $config['first_url'] = site_url('predate?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('predate');
                $config['first_url'] = site_url('predate');
            }

            $config['per_page'] = 10;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Predate_model->total_rows($q,$user->id);
            $predate = $this->Predate_model->get_limit_data($config['per_page'], $start, $user->id,$q);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'predate_data' => $predate,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );
            $data['controlador'] = 'users';
            $data['menuactivo'] = 'predate';
            $data['title'] = 'predate';
            $data['vista'] = $this->load->view('predate_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            redirect('auth/login', 'refresh');
        }
    }

    public function read($id)
    {
        $row = $this->Predate_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'user_id' => $row->user_id,
                'fecha' => $row->fecha,
                'hora_inicio' => $row->hora_inicio,
                'hora_fin' => $row->hora_fin,
                'actividad' => $row->actividad,
            );


            $data['menuactivo'] = 'predate';
            $data['title'] = 'predate';
            $data['vista'] = $this->load->view('predate_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('predate'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('predate/create_action'),
            'id' => set_value('id'),
            'user_id' => set_value('user_id'),
            'fecha' => set_value('fecha'),
            'hora_inicio' => set_value('hora_inicio','08:00'),
            'hora_fin' => set_value('hora_fin','08:30'),
            'actividad' => set_value('actividad'),
        );
        $user = $this->session->userdata('usuario');
        if (($user->rol_id == 3)||($user->rol_id == 5)) {
            $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
            $data['user'] = $user;
        }

        $data['controlador'] = 'users';
        $data['menuactivo'] = 'predate';
        $data['title'] = 'predate';
        $data['vista'] = $this->load->view('predate_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);

    }

    public function create_action()
    {
        $this->_rules();
        $user = $this->session->userdata('usuario');
        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'user_id' => $user->id,
                'fecha' =>Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('fecha', TRUE)),
                'hora_inicio' => $this->input->post('hora_inicio', TRUE),
                'hora_fin' => $this->input->post('hora_fin', TRUE),
                'actividad' => $this->input->post('actividad', TRUE),
            );
            if ($this->input->post('td')) {
                $data['hora_inicio'] = 'T/D';
                $data['hora_fin'] = 'T/D';
            }
            if(!$this->validaTarea($data['fecha'],$data['hora_inicio'],$data['hora_fin'],$user->id))
            {
                $this->Predate_model->insert($data);
                $predate_id = $this->Predate_model->get_max(); //obtengo el id de la tarea que acabo de introducir.
                $multiselect = $this->input->post('my_multi_select', true);
                if ($multiselect != null) {
                    foreach ($multiselect as $row) {
                        $datos['user_id'] = $row;
                        $datos['predate_id'] = $predate_id;
                        $this->Predate_model->insert_pre_sub($datos);
                    }
                }
                $this->session->set_flashdata('message', 'Tarea Insertada Correctamente');
                redirect(site_url('predate'));
            }
            else{
                $this->session->set_flashdata('message', 'Ya existe una tarea para esta fecha y hora.');
                redirect(site_url('predate'));
            }

        }
    }

    public function update($id)
    {
        $row = $this->Predate_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('predate/update_action'),
                'id' => set_value('id', $row->id),
                'user_id' => set_value('user_id', $row->user_id),
                'fecha' => set_value('fecha',Modules::run('adminsettings/fecha_formato_esp',$row->fecha)),
                'hora_inicio' => set_value('hora_inicio', $row->hora_inicio),
                'hora_fin' => set_value('hora_fin', $row->hora_fin),
                'actividad' => set_value('actividad', $row->actividad),
            );
            $user = $this->session->userdata('usuario');
            if (($user->rol_id == 3)||($user->rol_id == 5)) {
               // $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
                $data['user'] = $user;
                $subordinados = Modules::run('users/get_subordinados', $user->id);
                $subo_list = array();//esta es la lista de subordinados que no le han sido asignado esta tarea
                $i = 0;//contador para el arreglo subo_list
                $subo_with_task = array();
                $list_task_subor = $this->get_subor_with_task($row->id);
                for($k =0;$k<count($list_task_subor);$k++)
                {
                    $subo_with_task[$k] = Modules::run('users/get_user',($list_task_subor[$k]->user_id));
                }
                $data['subo_with_task'] = $subo_with_task;
                foreach ($subordinados as $sub) {
                    $task = $this->valida_pre_sub($row->id,$sub->id);

                    if ($task == null) {
                        $subo_list[$i] = $sub;
                    }
                    $i++;
                }
                $data['subo_list'] = $subo_list;
            }

            $data['controlador'] = 'users';
            $data['menuactivo'] = 'predate';
            $data['title'] = 'predate';
            $data['vista'] = $this->load->view('predate_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('predate'));
        }
    }
    function valida_pre_sub($id_pre,$users_id)
    {
        return $this->Predate_model->valida_pre_sub($id_pre,$users_id);
    }
    function get_subor_with_task($id_pre)
    {
        return $this->Predate_model->get_pre_sub($id_pre);
    }

    public function update_action()
    {
        $this->_rules();
        $user = $this->session->userdata('usuario');
        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'user_id' =>$user->id ,
                'fecha' => Modules::run('adminsettings/convertDateToMsSQL',$this->input->post('fecha', TRUE)),
                'hora_inicio' => $this->input->post('hora_inicio', TRUE),
                'hora_fin' => $this->input->post('hora_fin', TRUE),
                'actividad' => $this->input->post('actividad', TRUE),
            );
            if ($this->input->post('td')) {
                $data['hora_inicio'] = 'T/D';
                $data['hora_fin'] = 'T/D';
            }

            $this->Predate_model->update($this->input->post('id', TRUE), $data);
            $multiselect = $this->input->post('my_multi_select', true);
            if ($multiselect != null) {
                foreach ($multiselect as $row) {
                    $datos['user_id'] = $row;
                    $datos['predate_id'] = $this->input->post('id');
                    $this->Predate_model->insert_pre_sub($datos);
                }
            }
            $this->session->set_flashdata('message', 'Tarea Actualizada');
            redirect(site_url('predate'));

        }
    }

    public function delete($id)
    {
        $row = $this->Predate_model->get_by_id($id);

        if ($row) {
            $this->Predate_model->delete($id);
            $this->session->set_flashdata('message', 'Tarea eliminada correctamente');
            redirect(site_url('predate'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('predate'));
        }
    }
    //validaciones
    public function validaTarea($fecha, $hora_inicio,$hora_fin, $user_id)
    {
        $result = false;
        $query = $this->Predate_model->validaTarea($fecha, $hora_inicio,$hora_fin, $user_id);
        if(count($query)>0)$result = true;
        return $result;
    }
    public function insert_date_pt($month,$user_id)
    {
        $date = getdate(time());
        $year = $date['year'];
        $actividades = $this->Predate_model->get_actividades_month($month,$year,$user_id);
        if(count($actividades)>0)
        {
            foreach($actividades as $activity)
            {
                $data['fecha'] =$activity->fecha; //Modules::run('adminsettings/convertDateToMsSQL', $activity->fecha);
                $data['hora'] = $activity->hora_inicio;
                $data['hora_fin'] = $activity->hora_fin;
                $data['actividad'] = $activity->actividad;
                $data['user_id'] = $user_id;
                $data['tarea_superior'] = false;

                /* aqui voy a comprobar que el dia no sea por casualidad T/D*/
                $datos_td['user_id'] = $user_id;
                $datos_td['fecha'] = $activity->fecha;
                $datos_td['hora'] = 'T/D';
                $datos_td['hora_fin'] = 'T/D';
                if(Modules::run('pt/valida_activity',$data)->num_rows() == 0 && Modules::run('pt/valida_activity',$datos_td)->num_rows() == 0)
                {
                    Modules::run('pt/_insert',$data);
                }
                else
                {
                    if (Modules::run('pt/valida_activity',$data)->num_rows() != 0) {
                        $obj_activity = Modules::run('pt/valida_activity',$data)->row();
                        if ($obj_activity->actividad != "VACACIONES") {
                            Modules::run('pt/_insert',$data);
                        }
                    }
                    if (Modules::run('pt/valida_activity',$datos_td)->num_rows() != 0) {
                        $obj_activity = Modules::run('pt/valida_activity',$datos_td)->row();
                        if ($obj_activity->actividad != "VACACIONES") {
                            Modules::run('pt/_insert',$data);
                        }
                    }

                }
            }
        }

    }
    public function insert_predate_subor_pt($month,$parent_id,$user_id)
    {
        $date = getdate(time());
        $year = $date['year'];
        $actividades = $this->Predate_model->get_actividades_month($month,$year,$parent_id);
        if(count($actividades)>0)
        {
            foreach($actividades as $activity)
            {
                $data['fecha'] =$activity->fecha; //Modules::run('adminsettings/convertDateToMsSQL', $activity->fecha);
                $data['hora'] = $activity->hora_inicio;
                $data['hora_fin'] = $activity->hora_fin;
                $data['actividad'] = $activity->actividad;
                $data['user_id'] = $user_id;
                $data['tarea_superior'] = true;
                $data['parent_id'] = $parent_id;

                /* aqui voy a comprobar que el dia no sea por casualidad T/D*/
                $datos_td['user_id'] = $user_id;
                $datos_td['fecha'] = $activity->fecha;
                $datos_td['hora'] = 'T/D';
                $datos_td['hora_fin'] = 'T/D';

                $pre_sub = $this->Predate_model->get_pre_subor_task($activity->id,$user_id);
                if($pre_sub != null)
                {
                    if(Modules::run('pt/valida_activity',$data)->num_rows() == 0 && Modules::run('pt/valida_activity',$datos_td)->num_rows() == 0)
                    {
                        Modules::run('pt/_insert',$data);
                    }
                    else
                    {
                        if (Modules::run('pt/valida_activity',$data)->num_rows() != 0) {
                            $obj_activity = Modules::run('pt/valida_activity',$data)->row();
                            if ($obj_activity->actividad != "VACACIONES") {
                                Modules::run('pt/_insert',$data);
                            }
                        }
                        if (Modules::run('pt/valida_activity',$datos_td)->num_rows() != 0) {
                            $obj_activity = Modules::run('pt/valida_activity',$datos_td)->row();
                            if ($obj_activity->actividad != "VACACIONES") {
                                Modules::run('pt/_insert',$data);
                            }
                        }

                    }
                }


            }
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('fecha', 'fecha', 'trim|required');
        $this->form_validation->set_rules('hora_inicio', 'hora inicio', 'trim|required');
        $this->form_validation->set_rules('hora_fin', 'hora fin', 'trim|required');
        $this->form_validation->set_rules('actividad', 'actividad', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Predate.php */
/* Location: ./application/controllers/Predate.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-09-22 16:22:05 */
/* http://harviacode.com */