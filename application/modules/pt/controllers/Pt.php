<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Pt extends MX_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('html2pdf');
        $this->load->model('mdl_pt');

        $prefs['start_day'] = 'monday';
        $prefs['template'] = '

        {table_open}<table style="width: 100%;font-size: 12pt" class="tablacalendario" border="0" cellpadding="0" cellspacing="0">{/table_open}

        {heading_row_start}<tr>{/heading_row_start}

        {heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
        {heading_title_cell}<th colspan="{colspan}"></th>{/heading_title_cell}
        {heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}

        {heading_row_end}</tr>{/heading_row_end}

        {week_row_start}<tr>{/week_row_start}
        {week_day_cell}<td></td>{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}

        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td style="width: 14%;vertical-align:top;border:1px solid black;">{/cal_cell_start}
        {cal_cell_start_today}<td style="width: 14%;vertical-align:top;border:1px solid black;">{/cal_cell_start_today}
        {cal_cell_start_other}<td  style="width: 14%;vertical-align:top;vertical-align: top;border:0px solid transparent;" class="other-month">{/cal_cell_start_other}

        {cal_cell_content}<div style="height100%;width:100%; ">{content}</div>{/cal_cell_content}
        {cal_cell_content_today}<div style="height100%; width:100%;">{content}</div>{/cal_cell_content_today}

        {cal_cell_no_content}<div style="height100%;width:100%; "></div>{/cal_cell_no_content}
        {cal_cell_no_content_today}<div style="height100%;width:100%;"></div>{/cal_cell_no_content_today}

        {cal_cell_blank}&nbsp;{/cal_cell_blank}

        {cal_cell_other}{week_day} {day}{/cal_cel_other}

        {cal_cell_end}</td>{/cal_cell_end}
        {cal_cell_end_today}</td>{/cal_cell_end_today}
        {cal_cell_end_other}</td>{/cal_cell_end_other}
        {cal_row_end}</tr>{/cal_row_end}

        {table_close}</table>{/table_close}
';

        $prefs['show_next_prev'] = FALSE;
        $prefs['next_prev_url'] = '';
        $prefs['show_other_days'] = TRUE;


        $this->load->library('calendar', $prefs);

    }

    public function get_plan($date)
    {
        $date = $this->convertDateToMsSQL($date);
        $usuario = $this->session->userdata('usuario');
        $user_id = $usuario->id;

        $data['query'] = $this->get_where_date($date, $user_id);
        $this->load->view('pt', $data);
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

    public function get_where_date($date, $user_id)
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->get_where_date($date, $user_id);
        return $query;
    }

    public function get_by_id($id)
    {
        return $this->mdl_pt->get_by_id($id);
    }

    public function get_plan_subor($date, $user_id)
    {
        $date = $this->convertDateToMsSQL($date);

        $data['query'] = $this->get_where_date($date, $user_id);
        $this->load->view('pt', $data);
    }

    public function hora_select()
    {
        $data['horas'] = ['08:00', '09:00', '10:00', '11:00', '12:00', '13:00', '14:00', '15:00', '16:00', 'T/D'];
        $this->load->view('hora_select', $data);
    }

    function get_planpdf_v2($d, $m, $a)
    {
        $date = $d . '-' . $m . '-' . $a;
        $data['d'] = $d;
        $data['m'] = $m;
        $data['a'] = $a;
        $date = $this->convertDateToMsSQL($date);

        $usuario = $this->session->userdata('usuario');
        $user_id = $usuario->id;
        $data['query'] = $this->get_where_date($date, $user_id);

        return $this->load->view('ptpdf', $data, true);

    }

    function get_planpdf($date)
    {
        $date = $this->convertDateToMsSQL($date);
        $usuario = $this->session->userdata('usuario');
        $user_id = $usuario->id;

        $data['query'] = $this->get_where_date($date, $user_id);
        $this->load->view('ptpdf', $data);
    }

    function list_plan_update()
    {
        $this->session->unset_userdata('date');
        $date = $this->input->post('fecha');
        $date = $this->convertDateToMsSQL($date);

        $this->session->set_userdata('date', $date);
        $usuario = $this->session->userdata('usuario');
        $user_id = $usuario->id;
        $data['query'] = $this->get_where_date($date, $user_id);
        $page = $this->load->view('list_plan', $data, true);
        $json['page'] = $page;
        header('Content-Type: application/json');
        echo json_encode($json);
    }

    function show_view_plan()
    {
        $user_id = $this->uri->segment(3);
        if ($user_id > 0) {
            $date = $this->session->userdata('date');
            $data['query'] = $this->get_where_date($date, $user_id);
            $flash = $this->session->flashdata('item');
            if ($flash != "") {
                $data['flash'] = $flash;
            }
            $data['menuactivo'] = 'update_plan';
            $data['controlador'] = 'users';
            $data['vista'] = $this->load->view('pt/show_plan', $data, true);
            echo Modules::run('admintemplate/one_col', $data);

        } else {
            $data['menuactivo'] = 'update_plan';
            $data['controlador'] = 'users';
            $data['vista'] = $this->load->view('pt/show_plan', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }

    }

    function add_zero($a)
    {
        $b = $a;
        if (strlen($a) == 1) {
            $b = "0" . $a;
        }
        return $b;
    }

    public function create_plan_action()
    {
        $this->form_validation->set_rules('fecha', "Fecha", 'trim|required');
        $this->form_validation->set_rules('hora', "Hora Final", 'trim|required');
        $this->form_validation->set_rules('horaf', "Hora Final", 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->create_plan($this->input->post('fecha'));
        } else {
            $actividad = $this->input->post('activity');
            $tareaextra = $this->input->post('extra_task');
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $fecha = $this->input->post('fecha');

            $range = $this->input->post('range_date');
            $day = $this->input->post('day');

            $dia = explode("-", $fecha, 3);
            $yearr = $dia[2];
            $monthh = $dia[1];
            $dita = $dia[0];
            for ($i = $day; $i <= $range; $i++) {
                $fecha = $dita . '-' . $monthh . '-' . $yearr;
                $dita++;
                if ($dita < 10) $dita = '0' . $dita;

                if (($actividad == "") && ($tareaextra == "")) {
                    $this->session->set_flashdata('flash', 'Por favor inserte alguna tarea');
                    $this->create_plan($fecha);
                    return;
                }
                if (($actividad == "") && ($tareaextra != "")) {
                    $actividad = $tareaextra;
                }
                $usuario = $this->session->userdata('usuario');

                $date = $this->convertDateToMsSQL($fecha);
                $data['fecha'] = $date;
                $data['hora'] = $this->input->post('hora');
                $data['hora_fin'] = $this->input->post('horaf');
                if ($this->input->post('td')) {
                    $data['hora'] = 'T/D';
                    $data['hora_fin'] = 'T/D';
                }
                $data['actividad'] = $actividad;
                $data['user_id'] = $usuario->id;
                $data['tarea_superior'] = false;

                if ($this->valida_dia_hora($data['fecha'], $data['hora'], $data['hora_fin'], $usuario->id)) {
                    $this->session->set_flashdata('message', 'Ya existe una actividad para esa fecha y hora');
                    redirect(site_url('pt/create_plan/' . $fecha));
                }
                if (($data['hora'] == 'T/D') && ($this->valida_dia($data['fecha'], $usuario->id))) {
                    $this->session->set_flashdata('message', 'No puede existir una actividad (T/D) cuando ya existe actividades agregadas');
                    redirect(site_url('pt/create_plan/' . $fecha));
                }
                if ($this->valida_dia_hora($data['fecha'], 'T/D', 'T/D', $usuario->id)) {
                    $this->session->set_flashdata('message', 'Este dia no admite mas actividad');
                    redirect(site_url('pt/create_plan/' . $fecha));
                }

                $insert_id = $this->_insert($data);
                // $this->insert_datos_jefes($month, $usuario->id);//esto lo hago no vaya a ser que quede algo en esta tabla temporal
                $multiselect = $this->input->post('my_multi_select', true);
                if ($multiselect != null) {
                    foreach ($multiselect as $row) {
                        $data['user_id'] = $row;
                        $data['tarea_superior'] = true;
                        $data['parent_id'] = $usuario->id;
                        if (Modules::run('aprobacionpt/validaAprobacion', $row, $month, $year)) {
                            $data_t = array(
                                'users_id' => $row,
                                'nuevatarea' => $actividad,
                                'quien_origino' => $usuario->name,
                                'causas' => 'Necesidad de la Operación',
                                'mes' => $month,
                                'ano' => $year,
                            );
                            Modules::run('nuevas_tareas/tarea_de_orden_superior', $data_t);
                            continue;
                        }
                        /* if ($this->valida_dia_hora($data['fecha'], $data['hora'],$data['hora_fin'], $row)) {

                             $task_sub = $this->find_task_subor_directo($row, $data['fecha'], $data['hora'],$data['hora_fin']);
                             $this->_update($task_sub->id, $data);

                         }
                         if ($this->valida_dia_hora($data['fecha'], 'T/D','T/D', $row)) {

                             $task_sub = $this->find_task_subor_directo($row, $data['fecha'], 'T/D','T/D');
                             $this->_update($task_sub->id, $data);
                         }*/
                        if ((!$this->valida_dia_hora($data['fecha'], $data['hora'], $data['hora_fin'], $row)) && (!$this->valida_dia_hora($data['fecha'], 'T/D', 'T/D', $row))) {
                            // inserto la tarea de este subordinado en la misma tabla del pt
                            //$this->_insert($data);
                            $data_pt_subor['pt_id'] = $insert_id;
                            $data_pt_subor['user_id'] = $row;
                            $data_pt_subor['fecha'] = $date;
                            $this->mdl_pt->insert_pt_subor($data_pt_subor);
                            /* if ($this->check_month_subor($month, $row, $year))//verifico que ya el subordinado tenga algo
                             {
                                 $this->_insert($data);
                             } else {
                                 if (!$this->valida_dia_hora_tempo($data['fecha'], $data['hora'], $data['hora_fin'], $data['user_id'])) {
                                     $this->_insert_temppt($data);
                                 }

                             }*/

                            continue;
                        }

                    }
                }
            }
            $this->session->set_flashdata('flash', 'Tarea creada correctemente');
            redirect(site_url('pt/create_plan/' . $fecha));
        }
    }

    public function create_plan($fecha)//,$month,$year
    {

        $user = $this->session->userdata('usuario');
        $dia = explode("-", $fecha, 3);
        $year = $dia[2];
        $month = $dia[1]; //(string)(int)$dia[1];
        $day = $dia[0];//(string)(int)$dia[0];

        $this->session->set_userdata('mes', $month);

        $data['d_ant'] = $this->operacion_fecha($fecha, -1); //dia anterior;
        $data['d_prox'] = $this->operacion_fecha($fecha, +1); //dia proximo;
        if (Modules::run('aprobacionpt/validaAprobacion', $user->id, $month, $year)) {
            $this->session->set_flashdata('message', 'Ya este este PT fue aprobado y no se puede modificar');
            redirect('welcome/index/' . $month . '/' . $year);
        }
        $date = $fecha;//$this->input->post('dateplan');
        $data['date'] = $date;
        $data['menuactivo'] = 'update_plan';
        $data['controlador'] = 'users';
        $data['month'] = $month;
        $data['year'] = $year;
        $data['day'] = $day;
        $flash = $this->session->flashdata('flash');
        if ($flash != "") {
            $data['flash'] = $flash;
        }

        if ($user->rol_id == 3) {
            $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
            $data['user'] = $user;
        }
        $data['vista'] = $this->load->view('pt/create_plan', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function operacion_fecha($fecha, $dias)
    {//para restar y sumar dias
        list ($dia, $mes, $ano) = explode("-", $fecha);
        if (!checkdate($mes, $dia, $ano)) {
            return false;
        }
        $dia = $dia + $dias;
        $fecha = date("d-m-Y", mktime(0, 0, 0, $mes, $dia, $ano));
        return $fecha;
    }

    public function valida_dia_hora($dia, $hora, $hora_fin, $user_id)//existe una actividad para esta fecha y hora?
    {
        $result = false;
        $query = $this->mdl_pt->get_dia_hora($dia, $hora, $hora_fin, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }

    public function valida_dia($dia, $user_id) // ya este dia existe
    {
        $result = false;
        $query = $this->mdl_pt->get_dia($dia, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }

    public function _insert($data)
    {
        $this->load->model('mdl_pt');
       return $this->mdl_pt->_insert($data);
    }

    public function insert_datos_jefes($month,$year, $user_id) //estos datos los cargos de la tabla temporal temppt
    {
       // $tareas = $this->mdl_pt->_find_data_tempo($month, $user_id);
        $tareas = $this->mdl_pt->usuario_tareas_mes($month,$year,$user_id);
        if (count($tareas) > 0) {
            foreach ($tareas as $t) {
                $task = $this->mdl_pt->get_by_id($t->pt_id);
                $data['fecha'] = $task->fecha;
                $data['hora'] = $task->hora;
                $data['hora_fin'] = $task->hora_fin;
                $data['actividad'] = $task->actividad;
                $data['user_id'] = $t->user_id;
                $data['tarea_superior'] = true;
                $data['parent_id'] = $task->user_id;

                /* aqui voy a comprobar que el dia no sea por casualidad T/D*/
                $datos_td['user_id'] = $t->user_id;
                $datos_td['fecha'] = $task->fecha;
                $datos_td['hora'] = 'T/D';
                $datos_td['hora_fin'] = 'T/D';
                //verifico que en esta fecha no exista nada y de existir lo actualizo porque tengo prioridad
                if ($this->valida_activity($data)->num_rows() == 0 && $this->valida_activity($datos_td)->num_rows() == 0)//validando los datos en pt
                {
                    $this->_insert($data);
                    // $this->_delete_temppt($task->id);
                } else {
                    if ($this->valida_activity($data)->num_rows() != 0) {
                        $obj_activity = $this->valida_activity($data)->row();
                        $id = $obj_activity->id;
                         $this->_update($id, $data);
                        //$this->_insert($data);
                        // $this->_delete_temppt($task->id);
                    }
                    if ($this->valida_activity($datos_td)->num_rows() != 0) {
                        $obj_activity = $this->valida_activity($datos_td)->row();
                        if ($obj_activity->actividad != "VACACIONES") {
                            $id = $obj_activity->id;
                             $this->_update($id, $data);
                           // $this->_insert($data);
                            // $this->_delete_temppt($task->id);
                        }
                        // $this->_delete_temppt($task->id);

                    }

                }
            }
        }

    }
    public function insert_datos_empresa($month, $user_id)
    {
        //aqui obtengo los grupos a los que pertenece el usuario
        $list_group = Modules::run('grupo_participantes/get_group_by_user',$user_id);
        $date = getdate(time());
        $parent = Modules::run('users/get_by_rol_id',5);

        foreach($list_group as $item)
        {
            $list_flujo = Modules::run('flujo_informativo/get_flujo_by_grupo_participantes',$item->id,$month,$date['year']);
            foreach($list_flujo as $flujo)
            {
                $data['fecha'] = $flujo->fecha;
                $data['hora'] = $flujo->hora_inicio;
                $data['hora_fin'] = $flujo->hora_fin;
                $data['actividad'] = $flujo->actividad;
                $data['user_id'] = $user_id;
                $data['tarea_superior'] = true;
                $data['parent_id'] = $parent->id;
                $this->_insert($data);
            }
        }
    }

    public function valida_activity($data)
    {
        $this->load->model('mdl_pt');
        return $this->mdl_pt->valida_activity($data);
    }

    public function check_data_date($data)//consulta para verificar que ese dia tenga algo y retorna un booleano
    {
        $this->load->model('mdl_pt');
        $result = false;
        $query = $this->mdl_pt->check_data_date($data);
        if (count($query) > 0) $result = true;
        return $result;
    }

    public function list_data_date($data)//consulta para verificar que ese dia tenga algo y retorna una lista
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->check_data_date($data);
        return $query;
    }


    public function _update($id, $data)
    {
        $this->load->model('mdl_pt');
        $this->mdl_pt->_update($id, $data);
    }

    public function find_task_subor_directo($users_id, $fecha, $hora, $hora_fin)//tarea del hijo directo y me importa un carajo lo que tenga
    {
        $query = $this->mdl_pt->find_task_subor_directo($users_id, $fecha, $hora, $hora_fin);
        return $query;
    }

    public function check_month_subor($month, $user_id, $year) //esto es para chequear si ya el mes en curso tiene datos
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->check_month_subor($month, $user_id, $year);
        $result = false;
        if (count($query) > 0) {
            $result = true;
        }
        return $result;
    }

    public function _insert_temppt($data)
    {
        $this->load->model('mdl_pt');
        $this->mdl_pt->_insert_temppt($data);
    }

    public function show_calendar_subor()
    {
        $data['menuactivo'] = 'update_plan';
        $data['controlador'] = 'directivo';
        $data['vista'] = $this->load->view('pt/show_calendar_subor', $data, true);
        echo Modules::run('admintemplate/one_col', $data);

    }

    public function tarea_subor_action()
    {
        $this->form_validation->set_rules('fecha', "Fecha", 'trim|required');
        $this->form_validation->set_rules('hora', "Hora Inicio", 'trim|required');
        $this->form_validation->set_rules('horaf', "Hora Fin", 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->tarea_subor($this->input->post('fecha'));
        } else {
            $actividad = $this->input->post('activity');
            $tareaextra = $this->input->post('extra_task');
            $month = $this->input->post('month');
            $year = $this->input->post('year');
            $fecha = $this->input->post('fecha');
            if (($actividad == "") && ($tareaextra == "")) {
                $this->session->set_flashdata('flash', 'Por favor inserte alguna tarea');
                $this->tarea_subor($fecha);
                return;
            }
            if (($actividad == "") && ($tareaextra != "")) {
                $actividad = $tareaextra;
            }
            $usuario = $this->session->userdata('usuario');

            $date = $this->convertDateToMsSQL($fecha);
            $data['fecha'] = $date;
            $data['hora'] = $this->input->post('hora');
            $data['hora_fin'] = $this->input->post('horaf');
            if ($this->input->post('td')) {
                $data['hora'] = 'T/D';
                $data['hora_fin'] = 'T/D';
            }
            $data['actividad'] = $actividad;
            $data['user_id'] = $usuario->id;
            $data['tarea_superior'] = false;

            $multiselect = $this->input->post('my_multi_select', true);
            if ($multiselect != null) {
                foreach ($multiselect as $row) {
                    $data['user_id'] = $row;
                    $data['tarea_superior'] = true;
                    $data['parent_id'] = $usuario->id;
                    if (Modules::run('aprobacionpt/validaAprobacion', $row, $month, $year)) {
                        $data_t = array(
                            'users_id' => $row,
                            'nuevatarea' => $actividad,
                            'quien_origino' => $usuario->name,
                            'causas' => 'Necesidad de la Operación',
                            'fecha' => $date,
                            'hora_ini' => $this->input->post('hora'),
                            'hora_fin' => $this->input->post('horaf'),
                            'mes' => $month,
                            'ano' => $year,
                        );
                        Modules::run('nuevas_tareas/tarea_de_orden_superior', $data_t);
                        continue;
                    }
                    $subor = Modules::run('users/get_user', $row);
                    $data_email['email'] = $subor->email;
                    $data_email['fecha'] = $fecha;
                    $data_email['actividad'] = $actividad;

                    if ($this->valida_dia_hora($data['fecha'], $data['hora'], $data['hora_fin'], $row)) {

                        $task_sub = $this->find_task_subor_directo($row, $data['fecha'], $data['hora'], $data['hora_fin']);
                        if (!$task_sub->tarea_superior) {
                            $this->_update($task_sub->id, $data);
                            $this->send_email_new_task_subor($data_email);
                        } else {
                            if ($task_sub->parent_id == $usuario->id) {
                                $this->_update($task_sub->id, $data);
                                $this->send_email_new_task_subor($data_email);
                            }
                        }
                    }
                    if ($this->valida_dia_hora($data['fecha'], 'T/D', 'T/D', $row)) {

                        $task_sub = $this->find_task_subor_directo($row, $data['fecha'], 'T/D', 'T/D');
                        if ($task_sub->actividad != "VACACIONES") {
                            if (!$task_sub->tarea_superior) {
                                $this->_update($task_sub->id, $data);
                                $this->send_email_new_task_subor($data_email);
                            } else {
                                if ($task_sub->parent_id == $usuario->id) {
                                    $this->_update($task_sub->id, $data);
                                    $this->send_email_new_task_subor($data_email);
                                }
                            }

                        }
                    }
                    if ((!$this->valida_dia_hora($data['fecha'], $data['hora'], $data['hora_fin'], $row)) && (!$this->valida_dia_hora($data['fecha'], 'T/D', 'T/D', $row))) {
                        if ($this->check_month_subor($month, $row, $year))//verifico que ya el subordinado tenga algo
                        {
                            $this->_insert($data);
                            $this->send_email_new_task_subor($data_email);
                        } else {
                            $this->_insert_temppt($data);
                            $this->send_email_new_task_subor($data_email);
                        }

                        continue;
                    }

                }
            } else {
                $this->session->set_flashdata('flash', 'Seleccione al menos un subordinado por favor');
                redirect(site_url('pt/tarea_subor/' . $fecha));
            }

            $this->session->set_flashdata('flash', 'Tarea creada correctemente');
            redirect(site_url('pt/tarea_subor/' . $fecha));
        }
    }

    public function send_email_new_task_subor($data)
    {
      //  $from = Modules::run('adminsettings/email_from');
       // $from = $this->email->smtp_user;
       // $this->email->from($from);
       // $this->email->to($data['email']);
       // $this->email->subject('Nueva tarea');

        $message = 'Le han asignado una nueva tarea, verifique su plan de trbajo. Fecha: ' . $data['fecha'] . ' Actividad: ' . $data['actividad'];
       // $this->email->message($message);
       // $this->email->send();
        Modules::run('adminsettings/send_mail',$data['email'],"Nueva tarea",$message);
    }

    public function tarea_subor($date = null) //metodo para asignarle tareas a los subordinados.
    {
        $user = $this->session->userdata('usuario');
        $fecha = $this->input->post('fecha');
        if ($date != null) $fecha = $date;
        $dia = explode("-", $fecha, 3);
        $year = $dia[2];
        $month = $dia[1]; //(string)(int)$dia[1];
        $day = $dia[0];//(string)(int)$dia[0];

        $this->session->set_userdata('mes', $month);

        $data['d_ant'] = $this->operacion_fecha($fecha, -1); //dia anterior;
        $data['d_prox'] = $this->operacion_fecha($fecha, +1); //dia proximo;

        $date = $fecha;//$this->input->post('dateplan');
        $data['date'] = $date;
        $data['menuactivo'] = 'update_plan';
        $data['controlador'] = 'directivo';
        $data['month'] = $month;
        $data['year'] = $year;
        $flash = $this->session->flashdata('flash');
        if ($flash != "") {
            $data['flash'] = $flash;
        }

        if ($user->rol_id == 3) {
            $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
            $data['user'] = $user;
        }
        $data['vista'] = $this->load->view('pt/tarea_subor', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function tareas_hijos($hijo, $data, $month, $year) //esto es recursivo para tocar a todos los hijos
    {
        $subor_hijo = Modules::run('users/get_subordinados', $hijo->id); //subordinados de mi hijo
        foreach ($subor_hijo as $sub) {
            $task = $this->find_task_subor($sub->id, $data['fecha'], $data['hora'], $data['hora_fin'], $hijo->id);
            if ($task != null) {
                if ($task->tarea_superior) {
                    if (Modules::run('aprobacionpt/validaAprobacion', $sub->id, $month, $year)) {
                        $data_t = array(
                            'users_id' => $sub->id,
                            'nuevatarea' => $data['actividad'],
                            'quien_origino' => $hijo->name,
                            'causas' => 'Necesidad de la Operación',
                            'mes' => $month,
                            'ano' => $year,
                        );
                        Modules::run('nuevas_tareas/tarea_de_orden_superior', $data_t);
                        continue;
                    } else {
                        $data['user_id'] = $task->user_id;
                        $this->_update($task->id, $data);
                    }

                }
            }
            if ($sub->rol_id == 3) $this->tareas_hijos($sub, $data, $month, $year);
        }
    }

    public function find_task_subor($users_id, $fecha, $hora, $hora_fin, $parent_id)
    {
        $query = $this->mdl_pt->find_task_subor($users_id, $fecha, $hora, $hora_fin, $parent_id);
        return $query;
    }

    public function valida_dia_tempo($dia, $user_id) // ya este dia existe en temppt
    {
        $result = false;
        $query = $this->mdl_pt->get_dia_tempo($dia, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }

    public function get_dias_vencidos()
    {
        $user = $this->session->userdata('usuario');
        if ($user != null) {
            $date = getdate(time());
            $second = mktime(0, 0, 0, $date['mon'], 1);
            $fecha_ini = strftime('%Y-%m-%d', $second);
            $second = mktime(0, 0, 0, $date['mon'], $date['mday']);
            $fecha_fin = strftime('%Y-%m-%d', $second);
            $data['fechas'] = $this->mdl_pt->dias_hasta_hora($user->id, $fecha_ini, $fecha_fin);

            $data['menuactivo'] = 'diasvencidos';
            $data['controlador'] = 'users';
            $data['vista'] = $this->load->view('pt/pt_dias_vencidos', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            redirect('auth/login', 'refresh');
        }

    }

    public function insert_plan()
    {
        if ($this->input->is_ajax_request()) {
            $this->form_validation->set_rules('actividad', "Actividad", 'trim|required');
            $this->form_validation->set_rules('fecha', "Fecha", 'trim|required');
            $this->form_validation->set_rules('hora', "Hora", 'trim|required');


            if ($this->form_validation->run() == FALSE) {
                $errors = array(
                    'actividad' => form_error('actividad'),
                    'fecha' => form_error('fecha'),
                    'hora' => form_error('hora')
                );
                //y lo devolvemos así para parsearlo con JSON.parse
                echo json_encode($errors);

                $dataerror = validation_errors();
                return;
            } else {
                $usuario = $this->session->userdata('usuario');
                $date = $this->input->post('fecha');
                $date = $this->convertDateToMsSQL($date);
                $data['fecha'] = $date;
                $data['hora'] = $this->input->post('hora');
                $data['actividad'] = $this->input->post('actividad');
                $data['user_id'] = $usuario->id;
                $this->_insert($data);

                $response = array(
                    'respuesta' => 'Tarea insertada'
                );

                echo json_encode($response);
            }
        }
    }

    public function update_plan_list($fecha)
    {
        $user = $this->session->userdata('usuario');
        $dia = explode("-", $fecha, 3);
        $year = $dia[2];
        $month = $dia[1];
        $mes = $this->session->userdata('mes');
        if (Modules::run('aprobacionpt/validaAprobacion', $user->id, $month, $year)) {
            $this->session->set_flashdata('message', 'Ya este PT fue aprobado y no se puede modificar');
            redirect('welcome/index/' . $mes . '/' . $year);
        }
        $date = $fecha;//$this->input->post('fechaplan');
        $data['date'] = $date;
        $data['menuactivo'] = 'update_plan';
        $data['controlador'] = 'users';
        $flash = $this->session->flashdata('flash');
        if ($flash != "") {
            $data['flash'] = $flash;
        }

        if ($user->rol_id == 3) {
            $data['directivo'] = 3; //esto es una forma de saber que el usuario es directivo
            $data['user'] = $user;
        }
        $fechamysql = $this->convertDateToMsSQL($date);
        $data['tareas'] = $this->mdl_pt->get_tareas($user->id, $fechamysql);
        $data['vista'] = $this->load->view('pt/update_plan_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function update_plan_action()
    {
        $this->_rules();
        if ($this->form_validation->run() == FALSE) {
            $this->update_plan($this->input->post('id', TRUE));
        } else {
            $data = array(
                'hora' => $this->input->post('hora', TRUE),
                'hora_fin' => $this->input->post('horaf', TRUE),
                'actividad' => $this->input->post('actividad', TRUE),
                'fecha' => $this->convertDateToMsSQL($this->input->post('fecha', TRUE)),
            );
            if ($this->input->post('td')) {
                $data['hora'] = 'T/D';
                $data['hora_fin'] = 'T/D';
            }
            $user = $this->session->userdata('usuario');
            $this->_update($this->input->post('id', TRUE), $data);
            $row = $this->mdl_pt->get_by_id($this->input->post('id'));
            if (($user->rol_id == 3)|| ($user->rol_id == 5)) {
                $subordinados = Modules::run('users/get_subordinados', $user->id);
                //obtengo las usuarios asociados a esta tarea y les actualizo la fecha tambien
                $user_tareas = $this->mdl_pt->usuarios_con_tareas($row->id);
                if(count($user_tareas)>0)
                {
                    $this->mdl_pt->actualiza_usuario_tarea($row->id, $data['fecha']);
                }

                /* foreach ($subordinados as $sub) {
                    $task = $this->find_task_subor($sub->id, $row->fecha, $data['hora'], $data['hora_fin'], $user->id);
                   // $task_tempo = $this->find_task_subor_tempo($sub->id, $row->fecha, $data['hora'], $data['hora_fin'], $user->id);
                    if ($task != null) {
                        if ($task->tarea_superior) {
                            $this->_update($task->id, $data);
                        }
                    }
                  /*  if ($task_tempo != null) {
                        if ($task_tempo->tarea_superior) {
                            $this->_update_temppt($task_tempo->id, $data);
                        }
                    }
                }*/

                $multiselect = $this->input->post('my_multi_select', true);
                if ($multiselect != null) {
                    $dia = explode("/", $this->input->post('fecha'), 3);
                    $year = $dia[2];
                    $month = $dia[1];
                    $data['fecha'] = $this->convertDateToMsSQL($this->input->post('fecha', TRUE));
                    foreach ($multiselect as $row) {
                        $data['user_id'] = $row;
                        $data['tarea_superior'] = true;
                        $data['parent_id'] = $user->id;
                        if (Modules::run('aprobacionpt/validaAprobacion', $row, $month, $year)) {
                            $datas = array(
                                'users_id' => $row,
                                'nuevatarea' => $data['actividad'],
                                'quien_origino' => $user->name,
                                'causas' => 'Necesidad de la Operación',
                                'mes' => $month,
                                'ano' => $year,
                            );
                            Modules::run('nuevas_tareas/tarea_de_orden_superior', $datas);
                            continue;
                        }
                        if ($this->valida_dia_hora($data['fecha'], $data['hora'], $data['hora_fin'], $row)) {

                            $task_sub = $this->find_task_subor_directo($row, $data['fecha'], $data['hora'], $data['hora_fin']);
                            //  $task_sub_tempo = $this->find_task_subor_directo_tempo($row,$data['fecha'],$data['hora']);
                            $this->_update($task_sub->id, $data);

                        }
                        if ($this->valida_dia_hora($data['fecha'], 'T/D', 'T/D', $row)) {

                            //obtengo la tarea de este subordinado y la actualizo
                            $task_sub = $this->find_task_subor_directo($row, $data['fecha'], 'T/D', 'T/D');
                            //  $task_sub_tempo = $this->find_task_subor_directo_tempo($row,$data['fecha'],'T/D');
                            $this->_update($task_sub->id, $data);
                            //   $this->_update_temppt($task_sub_tempo->id, $data);

                        }
                        //aqui voy a analizar la tabla temporal, ojo que esto es muy importante
                       /* if ($this->valida_dia_hora_tempo($data['fecha'], $data['hora'], $data['hora_fin'], $row)) {

                            $task_sub_tempo = $this->find_task_subor_directo_tempo($row, $data['fecha'], $data['hora'], $data['hora_fin']);
                            if ($task_sub_tempo->parent_id == $user->id) {
                                $this->_update_temppt($task_sub_tempo->id, $data);
                            }


                        }
                        if ($this->valida_dia_hora_tempo($data['fecha'], 'T/D', 'T/D', $row)) {

                            $task_sub_tempo = $this->find_task_subor_directo_tempo($row, $data['fecha'], 'T/D', 'T/D');
                            if ($task_sub_tempo->parent_id == $user->id) {
                                $this->_update_temppt($task_sub_tempo->id, $data);
                            }


                        }*/
                        if ((!$this->valida_dia_hora($data['fecha'], $data['hora'], $data['hora_fin'], $row)) && (!$this->valida_dia_hora($data['fecha'], 'T/D', 'T/D', $row))) {

                           // $this->_insert($data);
                            //agrego el usuario a la tabla pt_subor
                            $data_pt_subor['pt_id'] = $this->input->post('id', TRUE);
                            $data_pt_subor['user_id'] = $row;
                            $data_pt_subor['fecha'] = $data['fecha'];
                            $this->mdl_pt->insert_pt_subor($data_pt_subor);
                           /* if ($this->check_month_subor($month, $row, $year))//verifico que ya el subordinado tenga algo
                            {
                                $this->_insert($data);
                            } else {
                                if (!$this->valida_dia_hora_tempo($data['fecha'], $data['hora'], $data['hora_fin'], $data['user_id'])) {
                                    $this->_insert_temppt($data);
                                }
                            }*/
                            continue;
                        }

                    }
                }
            }
            $mes = $this->session->userdata('mes');
            $ano = $this->session->userdata('ano');
            $this->session->set_flashdata('message', 'Tarea Actualizada');
            redirect('welcome/index/' . $mes . '/' . $ano);
        }
    }
    public function delete_task_subor()
    {
        // aqui estoy eliminando el usuario subordinado con esta esta tarea
        $data = array(
            'hora' => $this->input->post('hora', TRUE),
            'hora_fin' => $this->input->post('hora_fin', TRUE),
            'user_id' => $this->input->post('user_id', TRUE),
            'parent_id' => $this->input->post('parent_id', TRUE),
            'fecha' => $this->convertDateToMsSQL($this->input->post('fecha', TRUE)),
        );
        $this->mdl_pt->delete_task_subor($data);
    }

    public function _rules()
    {
        $this->form_validation->set_rules('actividad', 'actividad', 'trim|required');
        $this->form_validation->set_rules('hora', 'hora', 'trim|required');
        $this->form_validation->set_rules('horaf', 'hora final', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function update_plan($id)
    {
        $row = $this->mdl_pt->get_by_id($id);
        /* if ($this->validatarea($id)) {
             $month = $this->session->userdata('mes');
             $this->session->set_flashdata('message', 'Esta tarea no puede ser Actualizada por ser de orden superior');
             redirect('welcome/index/' . $month);
         }*/
        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('pt/update_plan_action'),
                'id' => set_value('id', $row->id),
                'hora' => set_value('hora', $row->hora),
                'hora_fin' => set_value('hora', $row->hora_fin),
                'fecha' => set_value('fecha',Modules::run('adminsettings/fecha_formato_esp',$row->fecha) ),
                'actividad' => set_value('actividad', $row->actividad),
            );
            if ($this->validatarea($id))
            {
                $data['directivo'] = true;
            }
            $data['fecha_texto'] = Modules::run('adminsettings/fechaesp', $row->fecha);
            $user = $this->session->userdata('usuario');
            if (($user->rol_id == 3)||($user->rol_id == 5)) {
                $subordinados = Modules::run('users/get_subordinados', $user->id);
                $subo_list = array();//esta es la lista de subordinados que no le han sido asignado esta tarea
                $subo_with_task = array();
                $i = 0;//contador para el arreglo subo_list
                $k = 0;
                foreach ($subordinados as $sub) {
                    //$task = $this->find_task_subor($sub->id, $row->fecha, $data['hora'], $data['hora_fin'], $user->id);
                    //$task_tempo = $this->find_task_subor_tempo($sub->id, $row->fecha, $data['hora'], $data['hora_fin'], $user->id);
                    $task = $this->mdl_pt->verificar_usuario_con_tarea($row->id,$sub->id);
                    if (count($task) == 0) {
                        $subo_list[$i] = $sub;
                        $i++;
                    }
                    if(count($task)>0)
                    {
                        $subo_with_task[$k] = $sub;
                        $k++;
                    }

                }
                $data['subo_list'] = $subo_list;
                $data['subo_with_task'] = $subo_with_task;
            }


            $data['menuactivo'] = 'pt';
            $data['controlador'] = 'users';
            $data['title'] = 'pt_update';
            $data['vista'] = $this->load->view('plan_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
    }
    public function elimina_usuario_tarea($pt_id,$user_id)
    {
        //elimino la tarea para el usuario seleccionado
        $this->mdl_pt->elimina_usuario_tarea($pt_id,$user_id);
        $this->update_plan($pt_id);
    }

    public function validatarea($id) //id del plan de trabajo
    {
        $result = false;
        $query = $this->mdl_pt->get_by_id($id);
        if ($query->tarea_superior) $result = true;
        return $result;
    }

    /**
     * @param $users_id
     * @param $fecha
     * @param $hora
     * @param $hora_fin
     * @param $parent_id
     * @return mixed
     */
    public function find_task_subor_tempo($users_id, $fecha, $hora, $hora_fin, $parent_id)
    {
        $query = $this->mdl_pt->find_task_subor_tempo($users_id, $fecha, $hora, $hora_fin, $parent_id);
        return $query;
    }

    public function _update_temppt($id, $data)
    {
        $this->load->model('mdl_pt');
        $this->mdl_pt->_update_temppt($id, $data);
    }

    public function valida_dia_hora_tempo($fecha, $hora, $hora_fin, $user_id)//existe una actividad para esta fecha y hora en temppt?
    {
        $result = false;
        $query = $this->mdl_pt->get_dia_hora_tempo($fecha, $hora, $hora_fin, $user_id);
        if (count($query) > 0) $result = true;
        return $result;
    }

    public function find_task_subor_directo_tempo($users_id, $fecha, $hora, $hora_fin)//tarea del hijo directo y me importa un carajo lo que tenga
    {
        $query = $this->mdl_pt->find_task_subor_directo_tempo($users_id, $fecha, $hora, $hora_fin);
        return $query;
    }

    public function show_task() //aqui voy mostrar un formulario para mostrar las tareas que se han cumplido
    {
        $this->load->model('mdl_pt');
        $data['menuactivo'] = 'cumplimiento';
        $data['controlador'] = 'users';
        $data['years'] = $this->mdl_pt->get_years();
        $data['vista'] = $this->load->view('pt/show_task', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function listar_tareas($fecha)
    {
        $this->load->model('mdl_pt');

        $user = $this->session->userdata('usuario');
        $list_task = $this->mdl_pt->listar_tareas($user->id, $fecha);
        return $list_task;
    }

    public function list_task()
    {
        $this->load->model('mdl_pt');
        $user = $this->session->userdata('usuario');
        $date = getdate(time());
        $month = $date['mon'] - 1;
        $year = $date['year'];
        $data['list_date'] = $this->mdl_pt->listar_fechas($user->id, $month, $year);
        $this->load->view('list_task', $data);
    }

    public function listar_fechas()
    {
        $this->load->model('mdl_pt');
        $month = $this->input->post('month_task');
        $year = $this->input->post('year_task');
        $data['menuactivo'] = 'cumplimiento';
        $data['controlador'] = 'users';
        $data['mes'] = $month;
        $data['ano'] = $year;
        $user = $this->session->userdata('usuario');
        $data['list_date'] = $this->mdl_pt->listar_fechas($user->id, $month, $year);
        $data['years'] = $this->mdl_pt->get_years();
        $data['vista'] = $this->load->view('pt/show_task', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function tareas_mes($user_id, $month, $year)
    {
        $this->load->model('mdl_pt');
        $tareas = $this->mdl_pt->listar_fechas($user_id, $month, $year);
        return count($tareas);
    }

    public function check_month($month, $year) //esto es para chequear si ya el mes en curso tiene datos
    {
        $this->load->model('mdl_pt');
        $user = $this->session->userdata('usuario');
        $query = $this->mdl_pt->check_month($month, $user->id, $year);
        $result = false;
        if (count($query) > 0) {
            $result = true;
        }
        return $result;
    }

    public function check_vaction($month, $year)//este metodo es para comprobar que solo haya vacaciones en el pt
    {
        $this->load->model('mdl_pt');
        $user = $this->session->userdata('usuario');
        $query = $this->mdl_pt->check_month($month, $user->id, $year);
        $result = false;
        if (count($query) > 0) {
            foreach ($query as $row) {
                if ($row->actividad != "VACACIONES") {
                    $result = true;
                    break;
                }
            }
        }
        return $result;
    }

    public function valida_activity_date($data)
    {
        $this->load->model('mdl_pt');
        return $this->mdl_pt->valida_activity_date($data);
    }

    public function delete_plan() //aqui hay que detenerse porque no todas las tareas se pueden eliminar
    {
        $id = $this->uri->segment(3);
        $date = $this->uri->segment(4);
        $month = $this->session->userdata('mes');
        $year = $this->session->userdata('ano');
        //esto es momentaneo porque las tareas de orden superior no se pueden eliminar.
        /* if ($this->validatarea($id)) {
             $this->session->set_flashdata('message', 'Esta tarea no puede ser eliminada por ser de orden superior');
             redirect('welcome/index/' . $month . '/' . $year);
         }*/
        $row = $this->mdl_pt->get_by_id($id);
        $user = $this->session->userdata('usuario');
        if ($user->rol_id == 3) {
            $subordinados = Modules::run('users/get_subordinados', $user->id);//get_subordinados\',$user->id
            foreach ($subordinados as $sub) {
                $task = $this->find_task_subor($sub->id, $row->fecha, $row->hora, $row->hora_fin, $user->id);
                if ($task != null) {
                    if ($task->tarea_superior) {
                        $this->_delete($task->id);
                    }
                }
            }
        }
        $this->_delete($id);
        $this->session->set_flashdata('message', 'Tarea Eliminada');
        redirect('pt/update_plan_list/' . $date);
    }

    public function _delete($id)
    {
        $this->load->model('mdl_pt');
        $this->mdl_pt->_delete($id);
    }

    public function delete_data_date($data) //borro los datos de este dia para este usuario.
    {
        $this->mdl_pt->delete_data_date($data);
    }

    public function get_task_superior($user_id)
    {
        return $this->mdl_pt->get_task_superior($user_id);
    }

    /**
     * @throws HTML2PDF_exception
     */
    function report()
    {

        //  require_once APPPATH.'./third_party/mpdf/vendor/autoload.php';
       // require_once APPPATH.'./third_party/dompdf_new/autoload.inc.php';
       // $pdf = new mPDF('utf-8','A4',11); //$this->m_pdf->load();

       // $mpdf = new \Mpdf\Mpdf();
        // create new PDF document
        require_once APPPATH.'./third_party/mpdf60/mpdf.php';

        $pdf = new mPDF('utf-8','A3',10);
       // $this->html2pdf->folder('./files/');
       // $this->html2pdf->filename('plan_trabajo.pdf');

        //establecemos el tipo de papel
       // $this->html2pdf->paper('A4', 'landscape');


       // require_once APPPATH.'third_party/dompdf/dompdf_config.inc.php';

       // $dompdf = new DOMPDF();
       // $dompdf->set_paper("A4", "landscape");


        //hacemos que coja la vista como datos a imprimir
        $this->form_validation->set_rules('planes', 'Plan', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', 'Seleccione la fecha por favor.');
            redirect(base_url('tareas_incumplidas/reportes'));
        } else {
            $submit = $this->input->post('submit', TRUE);
            if ($submit == 'Modelo1') {

                    $id_aprobacion = $this->input->post('planes');
                    $aprobacion = Modules::run('aprobacionpt/get_by_id', $id_aprobacion);
                    $data['mespt'] = $aprobacion->month;
                    $data['yearpt'] = $aprobacion->year;
                    //  $data['table_pt'] = $this->_test($aprobacion->month,$aprobacion->year);
                    //importante utf8_decode para mostrar bien las tildes, ñ y demás
                    $view = $this->load->view('report', $data, true);
                    //  $this->html2pdf->html($this->load->view('report', $data, true));
                $pdf->AddPage('L');
                $pdf->WriteHTML($view);
                $pdf->Output();
               // $this->html2pdf->html($view);
               // $this->html2pdf->create();

               // $dompdf->load_html($view);
              //  $dompdf->render();
               // $dompdf->stream("Plan de Trabajo",array('Attachment'=>0));

                //$mpdf->AddPage('L');
               // $mpdf->WriteHTML($view);
               // $mpdf->Output();

            }
            else {
                $id_aprobacion = $this->input->post('planes');
                $aprobacion = Modules::run('aprobacionpt/get_by_id', $id_aprobacion);
                redirect(base_url('tareas_incumplidas/model_pdf/' . $aprobacion->month . '/' . $aprobacion->year));
            }

        }


        /*  $current_url = current_url();
          $data['form_location'] = $current_url;

          $data['menuactivo'] = 'update_plan';
          $data['controlador'] = 'users';
          $data['vista'] = $this->load->view('pt/get_pt', $data, true);
          echo Modules::run('admintemplate/one_col', $data);*/

    }
    public function report_preview($month,$year)
    {
        require_once APPPATH.'./third_party/mpdf60/mpdf.php';
        $pdf = new mPDF('utf-8','A3',10);

        $data['mespt'] = $month;
        $data['yearpt'] = $year;

        $view = $this->load->view('report_preview', $data, true);
        $pdf->AddPage('L');
        $pdf->WriteHTML($view);
        $pdf->Output();
    }

    public function tempo()
    {
        $data['menuactivo'] = 'update_plan';
        $data['controlador'] = 'users';
        $data['vista'] = $this->load->view('pt/report', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function pdf_report()
    {
        $date = getdate(time());
        $month = $date['mon'];
        $day_month = days_in_month($month);
        $month_count = 1; //este es el numero del mes para un dia
        //  setlocale(LC_ALL, 'es_MX.UTF-8');
        $lunes = "Lunes";
        $martes = "Martes";
        $miercoles = "Miércoles";
        $jueves = "Jueves";
        $viernes = "Viernes";
        $sabado = "Sábado";
        $domingo = "Domingo";
        $usuario = $this->session->userdata('usuario');

        //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
        $day = mktime(0, 0, 0, $month, $month_count);
        $dia = strftime('%A', $day);

        if ($dia == "Tuesday") $day_month += 1;
        if ($dia == "Wednesday") $day_month += 2;
        if ($dia == "Thursday") $day_month += 3;
        if ($dia == "Friday") $day_month += 4;
        if ($dia == "Saturday") $day_month += 5;
        if ($dia == "Sunday") $day_month += 6;

        $year = strftime('%Y', mktime(0, 0, 0, $month));
        $mes = strftime('%B', mktime(0, 0, 0, $month));

        if ($mes == "January") $mes = "Enero";
        if ($mes == "February") $mes = "Febrero";
        if ($mes == "March") $mes = "Marzo";
        if ($mes == "April") $mes = "Abril";
        if ($mes == "May") $mes = "Mayo";
        if ($mes == "June") $mes = "Junio";
        if ($mes == "July") $mes = "Julio";
        if ($mes == "August") $mes = "Agosto";
        if ($mes == "September") $mes = "Setiembre";
        if ($mes == "October") $mes = "Octubre";
        if ($mes == "November") $mes = "Noviembre";
        if ($mes == "December") $mes = "Diciembre";


        // Creacion del PDF

        /*
         * Se crea un objeto de la clase Pdf, recuerda que la clase Pdf
         * heredó todos las variables y métodos de fpdf
         */
        $pdf = new PDF('L', 'mm', 'Letter');
        // Agregamos una página
        $pdf->AddPage();
        // Define el alias para el número de página que se imprimirá en el pie
        $pdf->AliasNbPages();

        /* Se define el titulo, márgenes izquierdo, derecho y
         * el color de relleno predeterminado
         */
        $pdf->SetTitle("Plan de Trabajo");
        $pdf->SetLeftMargin(15);
        $pdf->SetRightMargin(15);
        $pdf->SetFillColor(200, 200, 200);

        // Se define el formato de fuente: Arial, negritas, tamaño 9
        $this->pdf->SetFont('Arial', 'B', 9);
        /*
         * TITULOS DE COLUMNAS
         *
         * $this->pdf->Cell(Ancho, Alto,texto,borde,posición,alineación,relleno);
         */


        for ($i = 1; $i <= $day_month; $i++) {

            $day_number = 1;
            $show = false;

            for ($k = $i; $k <= ($i + 6); $k++) {
                if ($k > $day_month) break;
                if ($day_number == 8) $day_number = 1;

                $second = mktime(0, 0, 0, $month, $month_count);
                $date = strftime('%d/%m/%Y', $second);
                $dia = strftime('%A', $second);

                if ($dia == "Monday") $dia = "Lunes";
                if ($dia == "Tuesday") $dia = "Martes";
                if ($dia == "Wednesday") $dia = "Miércoles";
                if ($dia == "Thursday") $dia = "Jueves";
                if ($dia == "Friday") $dia = "Viernes";
                if ($dia == "Saturday") $dia = "Sábado";
                if ($dia == "Sunday") $dia = "Domingo";


                if (($day_number == 1) && ($dia == $lunes)) {
                    $pdf->Cell(25, 7, 'Lunes ' . $month_count, 'TBL', 0, 'C', '1');

                    $month_count++;
                    $show = true;
                }
                if (($day_number == 2) && ($dia == $martes)) {
                    $pdf->Cell(25, 7, 'Martes ' . $month_count, 'TBL', 0, 'C', '1');

                    $month_count++;
                    $show = true;
                }
                if (($day_number == 3) && ($dia == $miercoles)) {
                    $pdf->Cell(25, 7, utf8_decode("Miércoles") . ' ' . $month_count, 'TBL', 0, 'C', '1');

                    $month_count++;
                    $show = true;
                }
                if (($day_number == 4) && ($dia == $jueves)) {
                    $pdf->Cell(25, 7, 'Jueves' . ' ' . $month_count, 'TBL', 0, 'C', '1');

                    $month_count++;
                    $show = true;
                }
                if (($day_number == 5) && ($dia == $viernes)) {
                    $pdf->Cell(25, 7, 'Viernes' . ' ' . $month_count, 'TBL', 0, 'C', '1');

                    $month_count++;
                    $show = true;
                }
                if (($day_number == 6) && ($dia == $sabado)) {
                    $pdf->Cell(25, 7, 'Sabado' . ' ' . $month_count, 'TBL', 0, 'C', '1');

                    $month_count++;
                    $show = true;
                }
                if (($day_number == 7) && ($dia == $domingo)) {
                    $pdf->Cell(25, 7, 'Domingo' . ' ' . $month_count, 'TBL', 0, 'C', '1');

                    $month_count++;
                    $show = true;

                }


                if ($show) {

                    $datos = utf8_decode(Modules::run('pt/get_planpdf', $date));

                    // $pdf->MultiCell(35, 7, $datos, 0);
                    // $pdf->Cell(25, 7, $datos, 'TBL', 0, 'C', '1');
                }
                $day_number++;


            }
            if ($month_count == $day_month) break;
            $i = $k - 1;
            $pdf->Ln();

        }
        $pdf->Output();
    }

    public function _test($mes, $ano)
    {

        $prefs = array(
            'start_day' => 'monday',
            'show_next_prev' => false,
            'next_prev_url' => 'http://example.com/index.php/calendar/show/'
        );

        $prefs['template'] = '

        {table_open}<table width="100%" border="1" cellpadding="0" cellspacing="0">{/table_open}

        {heading_row_start}<tr>{/heading_row_start}

        {heading_previous_cell}{/heading_previous_cell}
        {heading_title_cell}{/heading_title_cell}
        {heading_next_cell}{/heading_next_cell}

        {heading_row_end}</tr>{/heading_row_end}

        {week_row_start}<tr>{/week_row_start}
        {week_day_cell}<td></td>{/week_day_cell}
        {week_row_end}</tr>{/week_row_end}

        {cal_row_start}<tr>{/cal_row_start}
        {cal_cell_start}<td>{/cal_cell_start}
        {cal_cell_start_today}<td>{/cal_cell_start_today}
        {cal_cell_start_other}<td class="other-month">{/cal_cell_start_other}

        {cal_cell_content}{content}{/cal_cell_content}
        {cal_cell_content_today}{content}{/cal_cell_content_today}

        {cal_cell_no_content}{/cal_cell_no_content}
        {cal_cell_no_content_today}{/cal_cell_no_content_today}

        {cal_cell_blank}&nbsp;{/cal_cell_blank}

        {cal_cell_other}{day}{/cal_cel_other}

        {cal_cell_end}</td>{/cal_cell_end}
        {cal_cell_end_today}</td>{/cal_cell_end_today}
        {cal_cell_end_other}</td>{/cal_cell_end_other}
        {cal_row_end}</tr>{/cal_row_end}

        {table_close}</table>{/table_close}
';

        $this->load->library('calendar', $prefs);
        $data = array();
        for ($day = 1; $day <= days_in_month($mes, $ano); $day++) {
            $second = mktime(0, 0, 0, $mes, $day, $ano);
            $diasemana = strftime('%A', $second);
            $date = strftime('%d-%m-%Y', $second);

            if ($diasemana == "Monday") $diasemana = "Lunes";
            if ($diasemana == "Tuesday") $diasemana = "Martes";
            if ($diasemana == "Wednesday") $diasemana = "Miércoles";
            if ($diasemana == "Thursday") $diasemana = "Jueves";
            if ($diasemana == "Friday") $diasemana = "Viernes";
            if ($diasemana == "Saturday") $diasemana = "Sábado";
            if ($diasemana == "Sunday") $diasemana = "Domingo";


            $data[$day] = '<div class="row" style="text-align: center;margin-top: 0px;background-color: #9e9e9e">
                <div class="col-lg-12"> ' . $diasemana . ' ' . $day . ' </div></div>' . utf8_decode(Modules::run('pt/get_planpdf', $date));
        }
        return $this->calendar->generate($ano, $mes, $data);
    }

    public function txt()
    {
        $filecontent = "Aqui va el mensaje de texto ... ";
        $downloadfile = "nombre.pdf";

        header("Content-disposition: attachment; filename=$downloadfile");
        header("Content-Type: application/force-download");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: " . strlen($filecontent));
        header("Pragma: no-cache");
        header("Expires: 0");

        echo $filecontent;
    }

    public function get_all()
    {
        $this->load->model('mdl_pt');
        $user = $this->session->userdata('usuario');
        $query = $this->mdl_pt->get_tareas_json($user->id);
        $json = array();
        foreach ($query as $row) {
            $json[] = array(
                'id' => $row->id,
                'title' => $row->actividad,
                'start' => $row->fecha,
                'url' => ""
            );
        }
        header('Content-Type: application/json');

        echo json_encode($json);
    }

    function get($order_by)
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by)
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id)
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value)
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->get_where_custom($col, $value);
        return $query;
    }

    function _delete_temppt($id)
    {
        $this->load->model('mdl_pt');
        $this->mdl_pt->_delete_temppt($id);
    }

    function count_where($column, $value)
    {
        $this->load->model('mdl_pt');
        $count = $this->mdl_pt->count_where($column, $value);
        return $count;
    }

    function get_max()
    {
        $this->load->model('mdl_pt');
        $max_id = $this->mdl_pt->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query)
    {
        $this->load->model('mdl_pt');
        $query = $this->mdl_pt->_custom_query($mysql_query);
        return $query;
    }

    public function insert_vacation($month, $year, $user_id)
    {
        $vacaciones = Modules::run('vacaciones/get_vacation', $month, $year, $user_id);
        if (count($vacaciones) > 0) {
            foreach ($vacaciones as $vac) {
                $day_dif = Modules::run('adminsettings/date_diff', $vac->desde, $vac->hasta);
                $date = explode('-', $vac->desde);
                $dia = $date[2];
                $mes = $date[1];
                $ano = $date[0];
                $newdate = $dia . '-' . $mes . '-' . $ano;

                for ($i = 0; $i <= $day_dif; $i++) {
                    $fecha = $this->operacion_fecha($newdate, $i);

                    $data['fecha'] = Modules::run('adminsettings/convertDateToMsSQL', $fecha);
                    $data['hora'] = 'T/D';
                    $data['hora_fin'] = 'T/D';
                    $data['actividad'] = 'VACACIONES';
                    $data['user_id'] = $user_id;
                    $data['tarea_superior'] = false;
                    $this->_insert($data);
                }
            }
        }


    }

    public function delete_pt_refresh($mes, $year, $user_id) //este metodo lo llamo cuando el usuario quiere volver a cargar las tareas predefinidas
    {
        $this->mdl_pt->delete_pt_refresh($mes, $year, $user_id);
    }

    public function word()
    {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=plantrabajo.doc");

        $this->load->view('pt_doc');
    }

}