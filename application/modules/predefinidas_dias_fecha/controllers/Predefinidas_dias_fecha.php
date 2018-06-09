<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Predefinidas_dias_fecha extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Predefinidas_dias_fecha_model');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $user = $this->session->userdata('usuario');
        if ($user != null) {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('predefinidas_dias_fecha?q=' . urlencode($q));
                $config['first_url'] = site_url('predefinidas_dias_fecha?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('predefinidas_dias_fecha');
                $config['first_url'] = site_url('predefinidas_dias_fecha');
            }

            $config['per_page'] = 30;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Predefinidas_dias_fecha_model->total_rows($q, $user->id);
            $predefinidas_dias_fecha = $this->Predefinidas_dias_fecha_model->get_limit_data($config['per_page'], $start, $q, $user->id);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'predefinidas_dias_fecha_data' => $predefinidas_dias_fecha,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );
            $data['controlador'] = 'users';
            $data['menuactivo'] = 'predefinidas_dias_fecha';
            $data['title'] = 'predefinidas_dias_fecha';
            $data['vista'] = $this->load->view('predefinidas_dias_fecha_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            redirect('auth/login', 'refresh');
        }

    }

    public function read($id)
    {
        $row = $this->Predefinidas_dias_fecha_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'tarea' => $row->tarea,
                'cuando' => $row->cuando,
                'dia' => $row->dia,
                'hora' => $row->hora,
                'users_id' => $row->users_id,
                'orden' => $row->orden,
            );


            $data['menuactivo'] = 'predefinidas_dias_fecha';
            $data['title'] = 'predefinidas_dias_fecha';
            $data['vista'] = $this->load->view('predefinidas_dias_fecha_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('predefinidas_dias_fecha'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('predefinidas_dias_fecha/create_action'),
            'id' => set_value('id'),
            'tarea' => set_value('tarea'),
            'cuando' => set_value('cuando'),
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
        $data['menuactivo'] = 'predefinidas_dias_fecha';
        $data['title'] = 'predefinidas_dias_fecha';
        $data['vista'] = $this->load->view('predefinidas_dias_fecha_form', $data, true);
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
                'cuando' => $this->input->post('cuando', TRUE),
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

            //valido las tareas siempre y cuando no sea el user de la empresa
            if($user->rol_id !=5)
            {
                if ($this->validaTarea($data['dia'],$data['cuando'], $data['hora'],$data['hora_fin'], $user->id)) {
                    $this->session->set_flashdata('message', 'Ya existe una actividad para este dia y hora.');
                    redirect(base_url('predefinidas_dias_fecha'));
                }
                if ($this->valida_dia_hora($data['dia'], $data['hora'],$data['cuando'], $user->id)) {
                    $this->session->set_flashdata('message', 'Ya existe una actividad que empieza con esta hora.');
                    redirect(base_url('predefinidas_dias_fecha'));
                }
                if (($data['hora'] == 'T/D') && ($this->valida_dia($data['dia'], $user->id,$data['cuando']))) {
                    $this->session->set_flashdata('message', 'No puede existir una actividad (T/D) cuando ya existe actividades agregadas');
                    redirect(base_url('predefinidas_dias_fecha'));
                }
                if ($this->valida_dia_hora($data['dia'], 'T/D', $data['cuando'], $user->id)) {
                    $this->session->set_flashdata('message', 'Este dia no admite mas actividad');
                    redirect(base_url('predefinidas_dias_fecha'));
                }
            }



            $data['orden'] = $this->getOrden($data['dia']);
            $this->Predefinidas_dias_fecha_model->insert($data);

            $predefinidas_id = $this->Predefinidas_dias_fecha_model->get_max(); //obtengo el id de la tarea que acabo de introducir.
            $multiselect = $this->input->post('my_multi_select', true);
            if ($multiselect != null) {
                foreach ($multiselect as $row) {
                    $datos['user_id'] = $row;
                    $datos['Predefinidas_dias_fecha_id'] = $predefinidas_id;
                    $this->Predefinidas_dias_fecha_model->insert_pre_sub($datos);
                }
            }
            $this->session->set_flashdata('message', 'Tarea Insertada Correctamente');
            redirect(site_url('predefinidas_dias_fecha'));

           /* if (!$this->verifica_tarea($data)) {
                $data['orden'] = $this->getOrden($data['dia']);
                $this->Predefinidas_dias_fecha_model->insert($data);
                $this->session->set_flashdata('message', 'Tarea Insertada Correctamente');
                redirect(site_url('predefinidas_dias_fecha'));
            } else {
                $this->session->set_flashdata('message', 'Ya existe una tarea para esta fecha');
                redirect(site_url('predefinidas_dias_fecha'));
            }*/

        }
    }

    function verifica_tarea($data)
    {
        $result = false;
        $query = $this->Predefinidas_dias_fecha_model->verifica_tarea($data);
        if (count($query) > 0) $result = true;
        return $result;
    }
    function valida_dia_hora($dia, $hora, $cuando, $user_id)//existe una actividad que empieza con esta hora
    {
        $result = false;
        $query = $this->Predefinidas_dias_fecha_model->get_dia_hora($dia, $hora,$cuando, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }
    function validaTarea($dia,$cuando, $hora,$hora_fin, $user_id)//existe una actividad para esta fecha y hora
    {
        $result = false;
        $query = $this->Predefinidas_dias_fecha_model->validaTarea($dia,$cuando, $hora,$hora_fin, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }
    function valida_dia($dia, $user_id,$cuando) // ya este dia existe
    {
        $result = false;
        $query = $this->Predefinidas_dias_fecha_model->get_dia($dia, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }

    function getOrden($dia)
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

    public function get_activity_by_user($user_id, $dia, $date, $dataday)
    {
        $actividades = $this->Predefinidas_dias_fecha_model->get_activity_by_user($user_id);//actividades predefinidas para este usuario
        $date = Modules::run('adminsettings/convertDateToMsSQL', $date);
        foreach ($actividades as $row) {

            if ($dia == $row->dia) {
                if ($dia == 'Lunes' && $dataday['lu'] == $row->cuando) {
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

                    $hay_tarea = Modules::run('pt/check_data_date',$datos);
                    if (!$hay_tarea)//validando los datos en pt
                    {
                        Modules::run('pt/_insert', $datos);
                    } else {
                        //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            $list_data = Modules::run('pt/list_data_date',$datos);
                            if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                            {
                               // Modules::run('pt/delete_data_date', $datos);
                                Modules::run('pt/_insert', $datos);
                            }
                            else{
                                Modules::run('pt/_insert', $datos);
                            }
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }

                        }
                    }
                }
                if ($dia == 'Martes' && $dataday['ma'] == $row->cuando) {
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
                    $hay_tarea = Modules::run('pt/check_data_date',$datos);
                    if (!$hay_tarea)//validando los datos en pt
                    {
                        Modules::run('pt/_insert', $datos);
                    } else {
                        //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            $list_data = Modules::run('pt/list_data_date',$datos);
                            if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                            {
                               // Modules::run('pt/delete_data_date', $datos);
                                Modules::run('pt/_insert', $datos);
                            }
                            else{
                                Modules::run('pt/_insert', $datos);
                            }
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                      //  Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }

                        }
                    }
                }
                if ($dia == 'Miércoles' && $dataday['mi'] == $row->cuando) {
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

                    $hay_tarea = Modules::run('pt/check_data_date',$datos);
                    if (!$hay_tarea)//validando los datos en pt
                    {
                        Modules::run('pt/_insert', $datos);
                    } else {
                        //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            $list_data = Modules::run('pt/list_data_date',$datos);
                            if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                            {
                               // Modules::run('pt/delete_data_date', $datos);
                                Modules::run('pt/_insert', $datos);
                            }
                            else{
                                Modules::run('pt/_insert', $datos);
                            }
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }

                        }
                    }
                }
                if ($dia == 'Jueves' && $dataday['ju'] == $row->cuando) {
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

                    $hay_tarea = Modules::run('pt/check_data_date',$datos);
                    if (!$hay_tarea)//validando los datos en pt
                    {
                        Modules::run('pt/_insert', $datos);
                    } else {
                        //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            $list_data = Modules::run('pt/list_data_date',$datos);
                            if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                            {
                               // Modules::run('pt/delete_data_date', $datos);
                                Modules::run('pt/_insert', $datos);
                            }
                            else{
                                Modules::run('pt/_insert', $datos);
                            }
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }

                        }
                    }
                }
                if ($dia == 'Viernes' && $dataday['vi'] == $row->cuando) {
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

                    $hay_tarea = Modules::run('pt/check_data_date',$datos);
                    if (!$hay_tarea)//validando los datos en pt
                    {
                        Modules::run('pt/_insert', $datos);
                    } else {
                        //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            $list_data = Modules::run('pt/list_data_date',$datos);
                            if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                            {
                               // Modules::run('pt/delete_data_date', $datos);
                                Modules::run('pt/_insert', $datos);
                            }
                            else{
                                Modules::run('pt/_insert', $datos);
                            }
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }

                        }
                    }
                }
                if ($dia == 'Sábado' && $dataday['sa'] == $row->cuando) {
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

                    $hay_tarea = Modules::run('pt/check_data_date',$datos);
                    if (!$hay_tarea)//validando los datos en pt
                    {
                        Modules::run('pt/_insert', $datos);
                    } else {
                        //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            $list_data = Modules::run('pt/list_data_date',$datos);
                            if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                            {
                               // Modules::run('pt/delete_data_date', $datos);
                                Modules::run('pt/_insert', $datos);
                            }
                            else{
                                Modules::run('pt/_insert', $datos);
                            }
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                       // Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }

                        }
                    }
                }
                if ($dia == 'Domingo' && $dataday['do'] == $row->cuando) {
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

                    $hay_tarea = Modules::run('pt/check_data_date',$datos);
                    if (!$hay_tarea)//validando los datos en pt
                    {
                        Modules::run('pt/_insert', $datos);
                    } else {
                        //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                        if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                        {
                            $list_data = Modules::run('pt/list_data_date',$datos);
                            if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                            {
                               // Modules::run('pt/delete_data_date', $datos);
                                Modules::run('pt/_insert', $datos);
                            }
                            else{
                                Modules::run('pt/_insert', $datos);
                            }
                        } else {
                            if ($this->_valida_activity($datos)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                        $id = $obj_activity->id;
                                      //  Modules::run('pt/_update', $id, $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                }
                            }
                            if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                $obj_activity = $this->_valida_activity($datos_td)->row();
                                if ($obj_activity->actividad != "VACACIONES") {
                                    if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
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
    }
    function insert_pre_subor($parent_id, $subor_id, $dia, $date,$dataday)//insertar tareas predefinidas a subordinados.
    {
        $actividades = $this->Predefinidas_dias_fecha_model->get_activity_by_user($parent_id);//actividades predefinidas para este usuario
        $date = Modules::run('adminsettings/convertDateToMsSQL', $date);
        foreach ($actividades as $row) {

            if ($dia == $row->dia) {
                if ($dia == 'Lunes' && $dataday['lu'] == $row->cuando) {
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

                    $pre_sub = $this->Predefinidas_dias_fecha_model->get_pre_sub($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                    if ($pre_sub != null) {
                        foreach ($pre_sub as $pre) {
                            $hay_tarea = Modules::run('pt/check_data_date',$datos);
                            if (!$hay_tarea)//validando los datos en pt
                            {
                                Modules::run('pt/_insert', $datos);
                            } else {
                                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                                {
                                    $list_data = Modules::run('pt/list_data_date',$datos);
                                    if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                                    {
                                       // Modules::run('pt/delete_data_date', $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                    else{
                                        if ($this->_valida_activity($datos)->num_rows() != 0) {
                                            $obj_activity = $this->_valida_activity($datos)->row();
                                            if ($obj_activity->actividad !== 'VACACIONES') {
                                                if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    Modules::run('pt/_update', $id, $datos);
                                                   // Modules::run('pt/_insert', $datos);
                                                }
                                                else{
                                                    if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                        $id = $obj_activity->id;
                                                       // Modules::run('pt/_update', $id, $datos);
                                                        Modules::run('pt/_insert', $datos);
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            Modules::run('pt/_insert', $datos);
                                        }
                                    }
                                } else {
                                    if ($this->_valida_activity($datos)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                              //  Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                   // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                            }
                                        }
                                    }
                                    if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos_td)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                               // Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
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
                }
                if ($dia == 'Martes' && $dataday['ma'] == $row->cuando) {
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

                    $pre_sub = $this->Predefinidas_dias_fecha_model->get_pre_sub($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                    if ($pre_sub != null) {
                        foreach ($pre_sub as $pre) {
                            $hay_tarea = Modules::run('pt/check_data_date',$datos);
                            if (!$hay_tarea)//validando los datos en pt
                            {
                                Modules::run('pt/_insert', $datos);
                            } else {
                                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                                {
                                    $list_data = Modules::run('pt/list_data_date',$datos);
                                    if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                                    {
                                        // Modules::run('pt/delete_data_date', $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                    else{
                                        if ($this->_valida_activity($datos)->num_rows() != 0) {
                                            $obj_activity = $this->_valida_activity($datos)->row();
                                            if ($obj_activity->actividad != "VACACIONES") {
                                                if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                                else{
                                                    if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                        $id = $obj_activity->id;
                                                        // Modules::run('pt/_update', $id, $datos);
                                                        Modules::run('pt/_insert', $datos);
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            Modules::run('pt/_insert', $datos);
                                        }
                                    }
                                } else {
                                    if ($this->_valida_activity($datos)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                //  Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                            }
                                        }
                                    }
                                    if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos_td)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                // Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
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
                }
                if ($dia == 'Miércoles' && $dataday['mi'] == $row->cuando) {
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

                    $pre_sub = $this->Predefinidas_dias_fecha_model->get_pre_sub($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                    if ($pre_sub != null) {
                        foreach ($pre_sub as $pre) {
                            $hay_tarea = Modules::run('pt/check_data_date',$datos);
                            if (!$hay_tarea)//validando los datos en pt
                            {
                                Modules::run('pt/_insert', $datos);
                            } else {
                                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                                {
                                    $list_data = Modules::run('pt/list_data_date',$datos);
                                    if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                                    {
                                        // Modules::run('pt/delete_data_date', $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                    else{
                                        if ($this->_valida_activity($datos)->num_rows() != 0) {
                                            $obj_activity = $this->_valida_activity($datos)->row();
                                            if ($obj_activity->actividad != "VACACIONES") {
                                                if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                                else{
                                                    if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                        $id = $obj_activity->id;
                                                        // Modules::run('pt/_update', $id, $datos);
                                                        Modules::run('pt/_insert', $datos);
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            Modules::run('pt/_insert', $datos);
                                        }
                                    }
                                } else {
                                    if ($this->_valida_activity($datos)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                //  Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                            }
                                        }
                                    }
                                    if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos_td)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                // Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
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
                }
                if ($dia == 'Jueves' && $dataday['ju'] == $row->cuando) {
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

                    $pre_sub = $this->Predefinidas_dias_fecha_model->get_pre_sub($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                    if ($pre_sub != null) {
                        foreach ($pre_sub as $pre) {
                            $hay_tarea = Modules::run('pt/check_data_date',$datos);
                            if (!$hay_tarea)//validando los datos en pt
                            {
                                Modules::run('pt/_insert', $datos);
                            } else {
                                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                                {
                                    $list_data = Modules::run('pt/list_data_date',$datos);
                                    if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                                    {
                                        // Modules::run('pt/delete_data_date', $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                    else{
                                        if ($this->_valida_activity($datos)->num_rows() != 0) {
                                            $obj_activity = $this->_valida_activity($datos)->row();
                                            if ($obj_activity->actividad != "VACACIONES") {
                                                if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                                else{
                                                    if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                        $id = $obj_activity->id;
                                                        // Modules::run('pt/_update', $id, $datos);
                                                        Modules::run('pt/_insert', $datos);
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            Modules::run('pt/_insert', $datos);
                                        }
                                    }
                                } else {
                                    if ($this->_valida_activity($datos)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                //  Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                            }
                                        }
                                    }
                                    if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos_td)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                // Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
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
                }
                if ($dia == 'Viernes' && $dataday['vi'] == $row->cuando) {
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

                    $pre_sub = $this->Predefinidas_dias_fecha_model->get_pre_sub($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                    if ($pre_sub != null) {
                        foreach ($pre_sub as $pre) {
                            $hay_tarea = Modules::run('pt/check_data_date',$datos);
                            if (!$hay_tarea)//validando los datos en pt
                            {
                                Modules::run('pt/_insert', $datos);
                            } else {
                                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                                {
                                    $list_data = Modules::run('pt/list_data_date',$datos);
                                    if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                                    {
                                        // Modules::run('pt/delete_data_date', $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                    else{
                                        if ($this->_valida_activity($datos)->num_rows() != 0) {
                                            $obj_activity = $this->_valida_activity($datos)->row();
                                            if ($obj_activity->actividad != "VACACIONES") {
                                                if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                                else{
                                                    if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                        $id = $obj_activity->id;
                                                        // Modules::run('pt/_update', $id, $datos);
                                                        Modules::run('pt/_insert', $datos);
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            Modules::run('pt/_insert', $datos);
                                        }
                                    }
                                } else {
                                    if ($this->_valida_activity($datos)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                //  Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                            }
                                        }
                                    }
                                    if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos_td)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                // Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
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
                }
                if ($dia == 'Sábado' && $dataday['sa'] == $row->cuando) {
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

                    $pre_sub = $this->Predefinidas_dias_fecha_model->get_pre_sub($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                    if ($pre_sub != null) {
                        foreach ($pre_sub as $pre) {
                            $hay_tarea = Modules::run('pt/check_data_date',$datos);
                            if (!$hay_tarea)//validando los datos en pt
                            {
                                Modules::run('pt/_insert', $datos);
                            } else {
                                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                                {
                                    $list_data = Modules::run('pt/list_data_date',$datos);
                                    if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                                    {
                                        // Modules::run('pt/delete_data_date', $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                    else{
                                        if ($this->_valida_activity($datos)->num_rows() != 0) {
                                            $obj_activity = $this->_valida_activity($datos)->row();
                                            if ($obj_activity->actividad != "VACACIONES") {
                                                if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                                else{
                                                    if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                        $id = $obj_activity->id;
                                                        // Modules::run('pt/_update', $id, $datos);
                                                        Modules::run('pt/_insert', $datos);
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            Modules::run('pt/_insert', $datos);
                                        }
                                    }
                                } else {
                                    if ($this->_valida_activity($datos)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                //  Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                            }
                                        }
                                    }
                                    if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos_td)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                // Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
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
                }
                if ($dia == 'Domingo' && $dataday['do'] == $row->cuando) {
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

                    $pre_sub = $this->Predefinidas_dias_fecha_model->get_pre_sub($row->id,$subor_id); //tareas que fueron asignadas a subordinados
                    if ($pre_sub != null) {
                        foreach ($pre_sub as $pre) {
                            $hay_tarea = Modules::run('pt/check_data_date',$datos);
                            if (!$hay_tarea)//validando los datos en pt
                            {
                                Modules::run('pt/_insert', $datos);
                            } else {
                                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                                if ($this->_valida_activity($datos)->num_rows() == 0 && $this->_valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                                {
                                    $list_data = Modules::run('pt/list_data_date',$datos);
                                    if(($datos['hora'] == 'T/D') && (!$this->check_order_superior($list_data)))
                                    {
                                        // Modules::run('pt/delete_data_date', $datos);
                                        Modules::run('pt/_insert', $datos);
                                    }
                                    else{
                                        if ($this->_valida_activity($datos)->num_rows() != 0) {
                                            $obj_activity = $this->_valida_activity($datos)->row();
                                            if ($obj_activity->actividad != "VACACIONES") {
                                                if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                                else{
                                                    if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                        $id = $obj_activity->id;
                                                        // Modules::run('pt/_update', $id, $datos);
                                                        Modules::run('pt/_insert', $datos);
                                                    }
                                                }
                                            }
                                        }
                                        else{
                                            Modules::run('pt/_insert', $datos);
                                        }
                                    }
                                } else {
                                    if ($this->_valida_activity($datos)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                //  Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                    $id = $obj_activity->id;
                                                    // Modules::run('pt/_update', $id, $datos);
                                                    Modules::run('pt/_insert', $datos);
                                                }
                                            }
                                        }
                                    }
                                    if ($this->_valida_activity($datos_td)->num_rows() != 0) {
                                        $obj_activity = $this->_valida_activity($datos_td)->row();
                                        if ($obj_activity->actividad != "VACACIONES") {
                                            if (!$obj_activity->tarea_superior) {//esto lo hago para evitar actualizar las tareas de orden superior
                                                $id = $obj_activity->id;
                                                // Modules::run('pt/_update', $id, $datos);
                                                Modules::run('pt/_insert', $datos);
                                            }
                                            else{
                                                if ($obj_activity->parent_id == $parent_id) {//esto lo hago para evitar actualizar las tareas de orden superior
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
                }

            }
        }
    }

    function check_order_superior($list) //chequeo que las actividades no sean de orden superior
    {
        $result = false;
        foreach($list as $l)
        {
            if($l->tarea_superior){
                $result = true;
                break;
            }
        }
        return $result;
    }

    function has_task()
    {
        $result = false;
        $user = $this->session->userdata('usuario');
        $query = $this->Predefinidas_dias_fecha_model->get_activity_by_user($user->id);
        if (count($query) > 0) {
            $result = true;
        }
        return $result;
    }

    function _valida_activity($data)
    {
        return Modules::run('pt/valida_activity', $data);
    }

    public function update($id)
    {
        $row = $this->Predefinidas_dias_fecha_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('predefinidas_dias_fecha/update_action'),
                'id' => set_value('id', $row->id),
                'tarea' => set_value('tarea', $row->tarea),
                'cuando' => set_value('cuando', $row->cuando),
                'dia' => set_value('dia', $row->dia),
                'hora' => set_value('hora', $row->hora),
                'hora_fin' => set_value('hora_fin', $row->hora_fin),
            );

            $user = $this->session->userdata('usuario');
            $data['users_id'] = $user->id;

            if (($user->rol_id == 3)||($user->rol_id == 5)) {
                $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
                $data['user'] = $user;
                $subordinados = Modules::run('users/get_subordinados', $user->id);

                $subo_with_task = array();
                $list_task_subor = $this->get_subor_with_task($row->id);
                for($k =0;$k<count($list_task_subor);$k++)
                {
                    $subo_with_task[$k] = Modules::run('users/get_user',($list_task_subor[$k]->user_id));
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
            $data['menuactivo'] = 'predefinidas_dias_fecha';
            $data['title'] = 'predefinidas_dias_fecha';
            $data['vista'] = $this->load->view('predefinidas_dias_fecha_update_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('predefinidas_dias_fecha'));
        }
    }
    function get_subor_with_task($id_pre)
    {
        return $this->Predefinidas_dias_fecha_model->get_pre_list_sub($id_pre);
    }
    public function delete_predef_subor($id_pre,$subor_id)
    {
        $this->Predefinidas_dias_fecha_model->delete_predef_subor($id_pre,$subor_id);
        $this->session->set_flashdata('message', 'Subordinado Eliminado');
        redirect(site_url('predefinidas_dias_fecha'));
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'tarea' => $this->input->post('tarea', TRUE),
                'cuando' => $this->input->post('cuando', TRUE),
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
            $data['orden'] = $this->getOrden($data['dia']);

            if($user->rol_id != 5)
            {
                if (($data['hora'] == 'T/D') && ($this->valida_dia($data['dia'], $user->id))) {
                    $this->session->set_flashdata('message', 'No puede existir una actividad (T/D) cuando ya existe actividades agregadas');
                    redirect(base_url('predefinidas_dias_fecha'));
                }
            }


            $this->Predefinidas_dias_fecha_model->update($this->input->post('id', TRUE), $data);

            $multiselect = $this->input->post('my_multi_select', true);
            if ($multiselect != null) {
                foreach ($multiselect as $row) {
                    $datos['user_id'] = $row;
                    $datos['Predefinidas_dias_fecha_id'] = $this->input->post('id');
                    $this->Predefinidas_dias_fecha_model->insert_pre_sub($datos);
                }
            }

            $this->session->set_flashdata('message', 'Tarea actualizada');
            redirect(site_url('predefinidas_dias_fecha'));
        }
    }
    function valida_pre_sub($id_pre,$users_id)
    {
        return $this->Predefinidas_dias_fecha_model->valida_pre_sub($id_pre,$users_id);
    }


    public function delete($id)
    {
        $row = $this->Predefinidas_dias_fecha_model->get_by_id($id);

        if ($row) {
            $this->Predefinidas_dias_fecha_model->delete($id);
            $this->session->set_flashdata('message', 'Tarea eliminada');
            redirect(site_url('predefinidas_dias_fecha'));
        } else {
            $this->session->set_flashdata('message', 'Tarea no encontrada');
            redirect(site_url('predefinidas_dias_fecha'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('tarea', 'tarea', 'trim|required');
        $this->form_validation->set_rules('cuando', 'cuando', 'trim|required');
        $this->form_validation->set_rules('dia', 'dia', 'trim|required');
        $this->form_validation->set_rules('hora', 'hora', 'trim|required');
        $this->form_validation->set_rules('horaf', 'hora fin', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Predefinidas_dias_fecha.php */
/* Location: ./application/controllers/Predefinidas_dias_fecha.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-07-12 23:07:00 */
/* http://harviacode.com */