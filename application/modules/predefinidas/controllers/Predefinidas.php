<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Predefinidas extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Predefinidas_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user = $this->session->userdata('usuario');
        if ($user != null) {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('predefinidas?q=' . urlencode($q));
                $config['first_url'] = site_url('predefinidas?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('predefinidas');
                $config['first_url'] = site_url('predefinidas');
            }

            $config['per_page'] = 30;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Predefinidas_model->total_rows($q);
            $predefinidas = $this->Predefinidas_model->get_limit_data($config['per_page'], $start, $q);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'predefinidas_data' => $predefinidas,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );
            $this->session->unset_tempdata('message');
            $data['controlador'] = 'users';
            $data['menuactivo'] = 'predefinidas';
            $data['title'] = 'predefinidas';
            $data['vista'] = $this->load->view('predefinidas_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            redirect('auth/login', 'refresh');
        }

    }

    public function tareas_fecha()
    {
        $data['controlador'] = 'users';
        $data['menuactivo'] = 'predefinidas';
        $data['title'] = 'predefinidas';
        $data['action'] = 'tareas_fechas_action';
        $data['vista'] = $this->load->view('predefinidas_fechas', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function get_activity_by_user($user_id, $dia, $date)
    {
        $data['actividades'] = $this->Predefinidas_model->get_activity_by_user($user_id);//actividades predefinidas para este usuario
        $date = $this->convertDateToMsSQL($date);
        foreach ($data['actividades'] as $row) {
           // if (($dia == 'Domingo') || ($dia == 'Sábado')) continue;
            if (($dia == $row->dia) || ($row->dia == 'T/D')) {
                $datos['user_id'] = $user_id;
                $datos['fecha'] = $date;
                $datos['hora'] = $row->hora;
                $datos['hora_fin'] = $row->hora_fin;
                $datos['actividad'] = $row->tarea;

                /* aqui voy a comprobar que el dia no sea por casualidad T/D*/
                $datos_td['user_id'] = $user_id;
                $datos_td['fecha'] = $date;
                $datos_td['hora'] = 'T/D';
                $datos_td['hora_fin'] = 'T/D';
                $datos_td['actividad'] = $row->tarea;
                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                {
                    Modules::run('pt/_insert', $datos);
                }
            }


        }
        //  $data['dia'] = $dia;
        //  $this->load->view('predefinidas_activity',$data);
    }

    /**
     * @param $data
     * @return mixed|string
     */
    public function _valida_activity($data)
    {
        return Modules::run('pt/valida_activity', $data);
    }

    public function get_predefinidas_user($user_id)//obtener las tareas predefinidas del usuario.
    {
        return $this->Predefinidas_model->get_activity_by_user($user_id);//actividades predefinidas para este usuario
    }

    public function get_by_id($id)
    {
        return $this->Predefinidas_model->get_by_id($id);
    }

    public function get_pre_sub($predefinida_id)//tareas que fueron asignadas a subordinados
    {
        return $this->Predefinidas_model->get_pre_sub($predefinida_id);
    }

    public function insert_pre_subor($parent_id, $subor_id, $dia, $date)//insertar tareas predefinidas a subordinados.
    {
        $data['actividades'] = $this->Predefinidas_model->get_activity_by_user($parent_id);//actividades predefinidas para este usuario
        $date = $this->convertDateToMsSQL($date);
        foreach ($data['actividades'] as $row) {
            if (($dia == 'Domingo')) continue;
            if (($dia == $row->dia) || ($row->dia == 'T/D')) {
                $datos['user_id'] = $subor_id;
                $datos['fecha'] = $date;
                $datos['hora'] = $row->hora;
                $datos['hora_fin'] = $row->hora_fin;
                $datos['actividad'] = $row->tarea;
                $datos['tarea_superior'] = true;
                $datos['parent_id'] = $parent_id;

                /* aqui voy a comprobar que el dia no sea por casualidad T/D*/
                $datos_td['user_id'] = $subor_id;
                $datos_td['fecha'] = $date;
                $datos_td['hora'] = 'T/D';
                $datos_td['hora_fin'] = 'T/D';
                $datos_td['actividad'] = $row->tarea;

                $pre_sub = $this->Predefinidas_model->get_pre_subor_task($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                if ($pre_sub != null) {
                    foreach ($pre_sub as $pre) {
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            Modules::run('pt/_insert', $datos);
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                $id = $obj_activity->id;
                               // Modules::run('pt/_update', $id, $datos);
                                /* ojo que esto es una nueva version y voy a insertar la tarea del jefe y dejar que el usuario tome su decisicion*/
                                Modules::run('pt/_insert', $datos);
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    $id = $obj_activity->id;
                                   // Modules::run('pt/_update', $id, $datos);
                                    Modules::run('pt/_insert', $datos);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public function has_task()
    {
        $result = false;
        $user = $this->session->userdata('usuario');
        $query = $this->Predefinidas_model->get_activity_by_user($user->id);
        if (count($query) > 0) {
            $result = true;
        }
        return $result;
    }

    public function create()
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('predefinidas/create_action'),
            'id' => set_value('id'),
            'tarea' => set_value('tarea'),
            'dia' => set_value('dia'),
            'hora' => set_value('hora'),
            'hora_fin' => set_value('hora_fin'),
        );
        $user = $this->session->userdata('usuario');
        if (($user->rol_id == 3)||($user->rol_id == 5)) {
            $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
            $data['user'] = $user;
        }

        $data['controlador'] = 'users';
        $data['menuactivo'] = 'predefinidas';
        $data['title'] = 'predefinidas';
        $data['vista'] = $this->load->view('predefinidas_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'tarea' => $this->input->post('tarea', TRUE),
                'dia' => $this->input->post('dia', TRUE),
                'hora' => $this->input->post('hora', TRUE),
                'hora_fin' => $this->input->post('horaf', TRUE),
            );
            if ($this->input->post('td')) {
                $data['hora'] = 'T/D';
                $data['hora_fin'] = 'T/D';
            }
            $user = $this->session->userdata('usuario');
            $data['users_id'] = $user->id;
            //aqui creo las validaciones para los usuarios excepto la empresa
            if($user->rol_id != 5)
            {
                if($this->validaTarea($data['dia'], $data['hora'],$user->id))
                {
                    $this->session->set_flashdata('message', 'Ya existe una actividad para esa fecha que empieza con esta hora');
                    redirect(base_url('predefinidas'));
                }
                if ($this->valida_dia_hora($data['dia'], $data['hora'],$data['hora_fin'], $user->id)) {
                    $this->session->set_flashdata('message', 'Ya existe una actividad para esa fecha y hora');
                    redirect(base_url('predefinidas'));
                }
                if (($data['hora'] == 'T/D') && ($this->valida_dia($data['dia'], $user->id))) {
                    $this->session->set_flashdata('message', 'No puede existir una actividad (T/D) cuando ya existe actividades agregadas');
                    redirect(base_url('predefinidas'));
                }
                if ($this->valida_dia_hora($data['dia'], 'T/D','T/D', $user->id)) {
                    $this->session->set_flashdata('message', 'Este dia no admite mas actividad');
                    redirect(base_url('predefinidas'));
                }
            }


            if($data['dia'] == 'T/D')
            {
                $dias = array("Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
                foreach($dias as $dia)
                {
                    $data['dia'] = $dia;

                    $data['orden'] = $this->getOrden($data['dia']);
                    $this->Predefinidas_model->insert($data);
                    $predefinidas_id = $this->Predefinidas_model->get_max(); //obtengo el id de la tarea que acabo de introducir.
                    $multiselect = $this->input->post('my_multi_select', true);
                    if ($multiselect != null) {
                        foreach ($multiselect as $row) {
                            $datos['users_id'] = $row;
                            $datos['predefinidas_id'] = $predefinidas_id;
                            $this->Predefinidas_model->insert_pre_sub($datos);
                        }
                    }
                }
                $this->session->set_flashdata('message', 'Revise que no haya existido algún solapamiento de horas');
            }else{
                $data['orden'] = $this->getOrden($data['dia']);
                $this->Predefinidas_model->insert($data);
                $predefinidas_id = $this->Predefinidas_model->get_max(); //obtengo el id de la tarea que acabo de introducir.
                $multiselect = $this->input->post('my_multi_select', true);
                if ($multiselect != null) {
                    foreach ($multiselect as $row) {
                        $datos['users_id'] = $row;
                        $datos['predefinidas_id'] = $predefinidas_id;
                        $this->Predefinidas_model->insert_pre_sub($datos);
                    }
                }
                $this->session->set_flashdata('message', 'Actividad Creada Correctamente');
            }
            redirect(site_url('predefinidas'));


        }
    }

    public function getOrden($dia)
    {
        $orden = 0;
        if ($dia == 'Lunes') {
            $orden = 1;
        }
        if ($dia == 'Martes') {
            $orden = 2;
        }
        if ($dia == 'Miércoles') {
            $orden = 3;
        }
        if ($dia == 'Jueves') {
            $orden = 4;
        }
        if ($dia == 'Viernes') {
            $orden = 5;
        }
        if ($dia == 'Sábado') {
            $orden = 6;
        }
        if ($dia == 'Domingo') {
            $orden = 7;
        }
        return $orden;
    }

    public function valida_dia_hora($dia, $hora,$hora_fin, $user_id)//existe una actividad para esta fecha y hora
    {
        $result = false;
        $query = $this->Predefinidas_model->get_dia_hora($dia, $hora,$hora_fin, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }
    public function validaTarea($dia,$hora,$user_id)
    {
        $result = false;
        $query = $this->Predefinidas_model->validaTarea($dia,$hora,$user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }

    public function valida_dia($dia, $user_id) // ya este dia existe
    {
        $result = false;
        $query = $this->Predefinidas_model->get_dia($dia, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }


    public function update($id)
    {
        $row = $this->Predefinidas_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('predefinidas/update_action'),
                'id' => set_value('id', $row->id),
                'tarea' => set_value('tarea', $row->tarea),
                'dia' => set_value('dia', $row->dia),
                'hora' => set_value('hora', $row->hora),
                'hora_fin' => set_value('hora_fin', $row->hora_fin),
            );
            $user = $this->session->userdata('usuario');
            if (($user->rol_id == 3)||($user->rol_id == 5)) {
                $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
                $data['user'] = $user;
                $subordinados = Modules::run('users/get_subordinados', $user->id);

                $subo_with_task = array();
                $list_task_subor = $this->get_subor_with_task($row->id);
                for($k =0, $kMax = count($list_task_subor); $k< $kMax; $k++)
                {
                    $subo_with_task[$k] = Modules::run('users/get_user',($list_task_subor[$k]->users_id));
                }
                $data['subo_with_task'] = $subo_with_task;


                $subo_list = array();//esta es la lista de subordinados que no le han sido asignado esta tarea
                $i = 0;//contador para el arreglo subo_list
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
            $data['menuactivo'] = 'predefinidas';
            $data['title'] = 'predefinidas';
            $data['vista'] = $this->load->view('predefinidas_update_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('predefinidas'));
        }
    }
    public function get_subor_with_task($id_pre)
    {
        return $this->Predefinidas_model->get_pre_sub($id_pre);
    }
    public function delete_predef_subor($predef_id,$user_id)
    {
        $this->Predefinidas_model->delete_predef_subor($predef_id,$user_id);
        $this->session->set_flashdata('message', 'Subordinado Eliminado');
        redirect(site_url('predefinidas'));
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'tarea' => $this->input->post('tarea', TRUE),
                'dia' => $this->input->post('dia', TRUE),
                'hora' => $this->input->post('hora', TRUE),
                'hora_fin' => $this->input->post('horaf', TRUE),
            );
            if ($this->input->post('td')) {
                $data['hora'] = 'T/D';
                $data['hora_fin'] = 'T/D';
            }
            $user = $this->session->userdata('usuario');

          /*  if (($data['hora'] == 'T/D') && ($this->valida_dia($data['dia'], $user->id))) {
                $this->session->set_flashdata('message', 'No puede existir una actividad (T/D) cuando ya existe actividades agregadas');
                redirect(base_url('predefinidas'));
            }*/
            $this->Predefinidas_model->update($this->input->post('id', TRUE), $data);
            $multiselect = $this->input->post('my_multi_select', true);
            if ($multiselect != null) {
                foreach ($multiselect as $row) {
                    $datos['users_id'] = $row;
                    $datos['predefinidas_id'] = $this->input->post('id');
                    $this->Predefinidas_model->insert_pre_sub($datos);
                }
            }
            $this->session->set_flashdata('message', 'Actualizado correctamente');
            redirect(site_url('predefinidas'));
        }
    }

    public function delete($id)
    {
        $row = $this->Predefinidas_model->get_by_id($id);

        if ($row) {
            $this->Predefinidas_model->delete($id);
            $this->session->set_flashdata('message', 'Tarea eliminada correctamente');
            redirect(site_url('predefinidas'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('predefinidas'));
        }
    }
    function valida_pre_sub($id_pre,$users_id)
    {
        return $this->Predefinidas_model->valida_pre_sub($id_pre,$users_id);
    }


    public function _rules()
    {
        $this->form_validation->set_rules('tarea', 'tarea', 'trim|required');
        $this->form_validation->set_rules('dia', 'dia', 'trim|required');
        $this->form_validation->set_rules('hora', 'hora', 'trim|required');
        $this->form_validation->set_rules('horaf', 'hora fin', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function convertDateToMsSQL($date)
    {

            $values = preg_split('/(\/|-)/', $date);
            $values[0] = (strlen($values[0]) == 2 ? $values[0] : "0" . $values[0]);
            $values[1] = (strlen($values[1]) == 2 ? $values[1] : "0" . $values[1]);
            $values[2] = (strlen($values[2]) == 4 ? $values[2] : substr(date("Y"), 0, 2) . $values[4]);
            return $values[2] . $values[1] . $values[0];
    }

    /**
     * Funcion para validar una fecha en formato dd/mm/yyyy
     */
    public function validateDateEs($date)
    {
        $pattern = "/^(0?[1-9]|[12][0-9]|[3][01])[\/|-](0?[1-9]|[1][12])[\/|-]((19|20)?[0-9]{2})$/";
        if (preg_match($pattern, $date))
            return true;
        return false;
    }

}

/* End of file Predefinidas.php */
/* Location: ./application/controllers/Predefinidas.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-03 20:47:16 */
/* http://harviacode.com */