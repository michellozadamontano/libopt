<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MX_Controller
{


    public function __construct()
    {
        parent::__construct();
        $this->load->library("my_phpmailer");
      //  require_once "./application/libraries/PHPMailer";
    }

    public function index($month = null, $ano = null)
    {
        $date = getdate(time());
        if ($month == null) {
            $data['mes'] = $date['mon'];
        } else {
            $data['mes'] = $month;
        }
        $year = $date['year'];
        if ($ano != null) {
            $year = $ano;
            $this->session->set_userdata('ano', $ano);
        }

        $mes = $data['mes'];
        $data['year'] = $year;

        if(!Modules::run('users/valida_info',$mes,$year))
        {
            $data_info['month'] = $mes;
            $data_info['year'] = $year;
            Modules::run('users/insert_info',$data_info);
            $this->send_email_info();
        }

        $data['menuactivo'] = 'welcome';
        $data['controlador'] = 'users';
        $data['title'] = 'welcome';
        $data['user'] = $this->session->userdata('usuario');
        if ($data['user'] != null) {
            $user = $this->session->userdata('usuario');

            /*if($user->rol_id == 3)
            {
                $data['directivo']= 3; //esto es una forma de saber que el usuario es directivo
                $data['user'] = $user;
            }*/
            $user_boss = Modules::run('users/get_user',$user->parent_id);//con esto verifico que el padre sea la empresa
            if (($user->parent_id == 0)||($user_boss->rol_id == 5)) {

                $data['vista'] = $this->load->view('welcome/index', $data, true);
                echo Modules::run('admintemplate/one_col', $data);
            } else {
                //compruebo que ya su jefe tenga su plan aprobado
                $aprobadopt = Modules::run('aprobacionpt/validaAprobacion', $user->parent_id, $mes, $year);
                if ($aprobadopt) {
                    $data['soldado']=true;

                    $data['vista'] = $this->load->view('welcome/index', $data, true);
                    echo Modules::run('admintemplate/one_col', $data);
                } else {
                    $this->session->set_flashdata('message', 'Su jefe inmediato aun no tiene su plan de trabajo aprobado para este mes, por tanto no le es permitido crear su plan de trabajo.');
                    redirect('predefinidas');
                }
            }

        } else {
            redirect('auth/login', 'refresh');
        }

    }
    public function pt_anteriores()
    {
        $date = getdate(time());
        $data['month'] = $date['mon'];
        $data['month_list'] = array(
            '01' => 'Enero',
            '02' => 'Febrebro',
            '03' => 'Marzo',
            '04' => 'Abril',
            '05' => 'Mayo',
            '06' => 'Junio',
            '07' => 'Julio',
            '08' => 'Agosto',
            '09' => 'Septiembre',
            '10' => 'Octubre',
            '11' => 'Noviembre',
            '12' => 'Diciembre',
        );
        $data['current_year'] = $date['year'];
        $data['last_year'] = $date['year'] - 1;
        $data['next_year'] = $date['year'] + 1;
        $data['menuactivo'] = 'pt_anterior';
        $data['controlador'] = 'users';
        $data['vista'] = $this->load->view('welcome/pt_anterior', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function post_pt_anterior()
    {
        $month = $this->input->post('month');
        $year = $this->input->post('year');
        $this->index($month,$year);
    }
    public function send_email_info()
    {
       // $from = Modules::run('adminsettings/email_from');
      /*  $from = $this->email->smtp_user;
        $this->email->from($from,'Libopt');
        $this->email->to('michellm@nauta.cu');
        $this->email->cc('michellozadamontano@yahoo.com');
        $this->email->subject('Uso de LiboPt');
        $user = $this->session->userdata('usuario');

        $message = $user->email."\n".$_SERVER['SERVER_NAME']."\n ".$_SERVER['SERVER_ADDR']."\n ".$_SERVER['SERVER_SOFTWARE']."\n".$_SERVER['DOCUMENT_ROOT'];
        $this->email->message($message);
        $this->email->send();*/
        $user = $this->session->userdata('usuario');
        $message = $user->email."\n".$_SERVER['SERVER_NAME']."\n ".$_SERVER['SERVER_ADDR']."\n ".$_SERVER['SERVER_SOFTWARE']."\n".$_SERVER['DOCUMENT_ROOT'];
        Modules::run('adminsettings/send_mail',"michellm@nauta.cu","Uso de LiboPT",$message);
    }
    public function notification($mes,$year)
    {
        $user = $this->session->userdata('usuario');
        if (Modules::run('aprobacionpt/validaAprobacion', $user->id, $mes, $year)) {
            $this->session->set_flashdata('message', 'Ya su plan de trabajo fue aprobado.');
            redirect('welcome/index/' . $mes . '/' . $year);
        }
        $parent = Modules::run('users/get_user',$user->parent_id);
        $from = Modules::run('adminsettings/email_from');
      //  $this->email->from($user->email,'Libopt');
      //  $this->email->to($parent->email);
      //  $this->email->cc('michellm@get.mrn.tur.cu');
        $message = "Ya puedes revisar el plan de trabajo de ".$user->name." para ser aprobado";
      //  $this->email->message($message);
      //  $this->email->send();
        Modules::run('adminsettings/send_mail',$parent->email,$user->mail,$message);
        $this->session->set_flashdata('message', 'Notificacion enviada a su superior');
        redirect('welcome/index/' . $mes . '/' . $year);
    }

    public function refresh_plan($mes)
    {
        $user = $this->session->userdata('usuario');

        $date = getdate(time());
        $ano = $this->session->userdata('ano');
        $year = $date['year'];
        if ($ano != null) $year = $ano;
        if (Modules::run('aprobacionpt/validaAprobacion', $user->id, $mes, $year)) {
            $this->session->set_flashdata('message', 'Ya este PT fue aprobado y no se puede modificar');
            redirect('welcome/index/' . $mes . '/' . $year);
        }
        Modules::run('pt/delete_pt_refresh', $mes, $year, $user->id);
        $this->iniciaPlan($mes);
    }

    public function iniciaPlan($mes)
    {
        $user = $this->session->userdata('usuario');
        $data['user'] = $user;
        $data['mes'] = $mes;
        $date = getdate(time());
        $ano = $this->session->userdata('ano');
        $year = $date['year'];
        if ($ano != null) $year = $ano;
        $data['year'] = $year;
        $this->insert_vacation($mes, $year);
        $this->load->view('inicia_plan', $data, true);
        if($user->parent_id > 0){
            $this->insert_pre_subor($mes);//tareas predefinidas que vienen de ancladas con las del jefe
        }
        $this->start_task_by_date($mes);
        if($user->parent_id > 0){
            $this->insert_pre_fecha_subor($mes);//tareas predefinidas que vienen  con las del jefe
        }
        $this->start_task_by_day_date($mes);
        if($user->parent_id > 0){
            $this->insert_pre_fecha_dia_subor($mes);//tareas predefinidas que vienen  con las del jefe
        }
        $this->insert_predate($mes);
        if($user->parent_id > 0){
            $this->insert_predate_subor($mes);//tareas predefinidas que vienen  con las del jefe
        }
        $this->insert_datos_jefe($mes,$data['year']);
        $this->insert_datos_empresa($mes);
        redirect('welcome/index/' . $mes . '/' . $year);
    }
    public function insert_pre_subor($month) //aqui solo entro si tengo jefe
    {

        $user = $this->session->userdata('usuario');
        $parents = Modules::run('users/get_jefes',$user->id);
        foreach($parents as $parent)
        {
            $date = getdate(time());
            $ano = $this->session->userdata('ano');
            $year = $date['year'];
            if ($ano != null) $year = $ano;
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

            //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
            $day = mktime(0, 0, 0, $month, $month_count, $year);
            $dia = strftime('%A', $day);

            if ($dia == "Tuesday") $day_month += 1;
            if ($dia == "Wednesday") $day_month += 2;
            if ($dia == "Thursday") $day_month += 3;
            if ($dia == "Friday") $day_month += 4;
            if ($dia == "Saturday") $day_month += 5;
            if ($dia == "Sunday") $day_month += 6;

            for ($i = 1; $i <= $day_month; $i++) {
                $day_number = 1;
                $show = false;

                for ($k = $i; $k <= ($i + 6); $k++) {
                    if ($k > $day_month) break;
                    if ($day_number == 8) $day_number = 1;


                    $second = mktime(0, 0, 0, $month, $month_count, $year);
                    $date = strftime('%d/%m/%Y', $second);
                    $dia = strftime('%A', $second);

                    if ($dia == "Monday") $dia = "Lunes";
                    if ($dia == "Tuesday") $dia = "Martes";
                    if ($dia == "Wednesday") $dia = "Miércoles";
                    if ($dia == "Thursday") $dia = "Jueves";
                    if ($dia == "Friday") $dia = "Viernes";
                    if ($dia == "Saturday") $dia = "Sábado";
                    if ($dia == "Sunday") $dia = "Domingo";

                    if ($day_number == 1) {
                        echo "Lunes";
                        if ($dia == $lunes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 2) {
                        echo "Martes";
                        if ($dia == $martes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 3) {
                        echo "Miércoles";
                        if ($dia == $miercoles) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 4) {
                        echo "Jueves";
                        if ($dia == $jueves) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 5) {
                        echo "Viernes";
                        if ($dia == $viernes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 6) {
                        echo "Sábado";
                        if ($dia == $sabado) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 7) {
                        echo "Domingo";
                        if ($dia == $domingo) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    $day_number++;

                    if ($show) {

                        echo Modules::run('predefinidas/insert_pre_subor',$parent->id, $user->id, $dia, $date);

                    }
                }

                if ($month_count == $day_month) break;
                $i = $k - 1;
            }
        }

    }

    public function start_task_by_date($month) //inicia las tareas del mes por fechas
    {
        $user = $this->session->userdata('usuario');
        $date = getdate(time());
        $ano = $this->session->userdata('ano');
        $year = $date['year'];
        if ($ano != null) $year = $ano;
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

        //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
        $day = mktime(0, 0, 0, $month, $month_count, $year);
        $dia = strftime('%A', $day);

        if ($dia == "Tuesday") $day_month += 1;
        if ($dia == "Wednesday") $day_month += 2;
        if ($dia == "Thursday") $day_month += 3;
        if ($dia == "Friday") $day_month += 4;
        if ($dia == "Saturday") $day_month += 5;
        if ($dia == "Sunday") $day_month += 6;

        for ($i = 1; $i <= $day_month; $i++) {
            $day_number = 1;
            $show = false;

            for ($k = $i; $k <= ($i + 6); $k++) {
                if ($k > $day_month) break;
                if ($day_number == 8) $day_number = 1;


                $second = mktime(0, 0, 0, $month, $month_count, $year);
                $date = strftime('%d/%m/%Y', $second);
                $dia = strftime('%A', $second);

                if ($dia == "Monday") $dia = "Lunes";
                if ($dia == "Tuesday") $dia = "Martes";
                if ($dia == "Wednesday") $dia = "Miércoles";
                if ($dia == "Thursday") $dia = "Jueves";
                if ($dia == "Friday") $dia = "Viernes";
                if ($dia == "Saturday") $dia = "Sábado";
                if ($dia == "Sunday") $dia = "Domingo";

                if ($day_number == 1) {
                    echo "Lunes";
                    if ($dia == $lunes) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $show = true;
                    }
                }
                if ($day_number == 2) {
                    echo "Martes";
                    if ($dia == $martes) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $show = true;
                    }
                }
                if ($day_number == 3) {
                    echo "Miércoles";
                    if ($dia == $miercoles) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $show = true;
                    }
                }
                if ($day_number == 4) {
                    echo "Jueves";
                    if ($dia == $jueves) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $show = true;
                    }
                }
                if ($day_number == 5) {
                    echo "Viernes";
                    if ($dia == $viernes) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $show = true;
                    }
                }
                if ($day_number == 6) {
                    echo "Sábado";
                    if ($dia == $sabado) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $show = true;
                    }
                }
                if ($day_number == 7) {
                    echo "Domingo";
                    if ($dia == $domingo) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $show = true;
                    }
                }
                $day_number++;

                if ($show) {

                    echo Modules::run('predefinida_fecha/get_activity_by_user', $user->id, $month_count - 1, $date);

                }
            }

            if ($month_count == $day_month) break;
            $i = $k - 1;
        }
    }
    public function insert_pre_fecha_subor($month) //inicia las tareas del mes por fechas para subordinados
    {
        $user = $this->session->userdata('usuario');
        $parents = Modules::run('users/get_jefes',$user->id);
        foreach($parents as $parent)
        {
            $date = getdate(time());
            $ano = $this->session->userdata('ano');
            $year = $date['year'];
            if ($ano != null) $year = $ano;
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

            //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
            $day = mktime(0, 0, 0, $month, $month_count, $year);
            $dia = strftime('%A', $day);

            if ($dia == "Tuesday") $day_month += 1;
            if ($dia == "Wednesday") $day_month += 2;
            if ($dia == "Thursday") $day_month += 3;
            if ($dia == "Friday") $day_month += 4;
            if ($dia == "Saturday") $day_month += 5;
            if ($dia == "Sunday") $day_month += 6;

            for ($i = 1; $i <= $day_month; $i++) {
                $day_number = 1;
                $show = false;

                for ($k = $i; $k <= ($i + 6); $k++) {
                    if ($k > $day_month) break;
                    if ($day_number == 8) $day_number = 1;


                    $second = mktime(0, 0, 0, $month, $month_count, $year);
                    $date = strftime('%d/%m/%Y', $second);
                    $dia = strftime('%A', $second);

                    if ($dia == "Monday") $dia = "Lunes";
                    if ($dia == "Tuesday") $dia = "Martes";
                    if ($dia == "Wednesday") $dia = "Miércoles";
                    if ($dia == "Thursday") $dia = "Jueves";
                    if ($dia == "Friday") $dia = "Viernes";
                    if ($dia == "Saturday") $dia = "Sábado";
                    if ($dia == "Sunday") $dia = "Domingo";

                    if ($day_number == 1) {
                        echo "Lunes";
                        if ($dia == $lunes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 2) {
                        echo "Martes";
                        if ($dia == $martes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 3) {
                        echo "Miércoles";
                        if ($dia == $miercoles) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 4) {
                        echo "Jueves";
                        if ($dia == $jueves) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 5) {
                        echo "Viernes";
                        if ($dia == $viernes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 6) {
                        echo "Sábado";
                        if ($dia == $sabado) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    if ($day_number == 7) {
                        echo "Domingo";
                        if ($dia == $domingo) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $show = true;
                        }
                    }
                    $day_number++;

                    if ($show) {

                        echo Modules::run('predefinida_fecha/insert_pre_subor',$parent->id, $user->id, $month_count - 1, $date);

                    }
                }

                if ($month_count == $day_month) break;
                $i = $k - 1;
            }
        }

    }

    public function start_task_by_day_date($month) //inicia las tareas segun dias(ej: 2dos lunes de cada mes)
    {
        $user = $this->session->userdata('usuario');
        $date = getdate(time());
        $ano = $this->session->userdata('ano');
        $year = $date['year'];
        if ($ano != null) $year = $ano;
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

        //contadores para ir determinando la cantidad de dias de la semana que tiene el mes
        $dataday['lu'] = 0;
        $dataday['ma'] = 0;
        $dataday['mi'] = 0;
        $dataday['ju'] = 0;
        $dataday['vi'] = 0;
        $dataday['sa'] = 0;
        $dataday['do'] = 0;


        //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
        $day = mktime(0, 0, 0, $month, $month_count, $year);
        $dia = strftime('%A', $day);

        if ($dia == "Tuesday") $day_month += 1;
        if ($dia == "Wednesday") $day_month += 2;
        if ($dia == "Thursday") $day_month += 3;
        if ($dia == "Friday") $day_month += 4;
        if ($dia == "Saturday") $day_month += 5;
        if ($dia == "Sunday") $day_month += 6;

        for ($i = 1; $i <= $day_month; $i++) {
            $day_number = 1;
            $show = false;

            for ($k = $i; $k <= ($i + 6); $k++) {
                if ($k > $day_month) break;
                if ($day_number == 8) $day_number = 1;


                $second = mktime(0, 0, 0, $month, $month_count, $year);
                $date = strftime('%d/%m/%Y', $second);
                $dia = strftime('%A', $second);

                if ($dia == "Monday") $dia = "Lunes";
                if ($dia == "Tuesday") $dia = "Martes";
                if ($dia == "Wednesday") $dia = "Miércoles";
                if ($dia == "Thursday") $dia = "Jueves";
                if ($dia == "Friday") $dia = "Viernes";
                if ($dia == "Saturday") $dia = "Sábado";
                if ($dia == "Sunday") $dia = "Domingo";

                if ($day_number == 1) {
                    if ($dia == $lunes) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $dataday['lu']++;
                        $show = true;
                    }
                }
                if ($day_number == 2) {
                    if ($dia == $martes) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $dataday['ma']++;
                        $show = true;
                    }
                }
                if ($day_number == 3) {
                    if ($dia == $miercoles) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $dataday['mi']++;
                        $show = true;
                    }
                }
                if ($day_number == 4) {
                    if ($dia == $jueves) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $dataday['ju']++;
                        $show = true;
                    }
                }
                if ($day_number == 5) {
                    if ($dia == $viernes) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $dataday['vi']++;
                        $show = true;
                    }
                }
                if ($day_number == 6) {
                    if ($dia == $sabado) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $dataday['sa']++;
                        $show = true;
                    }
                }
                if ($day_number == 7) {
                    if ($dia == $domingo) {
                        echo ' ' . $month_count;
                        $month_count++;
                        $dataday['do']++;
                        $show = true;
                    }
                }
                $day_number++;

                if ($show) {
                    echo Modules::run('predefinidas_dias_fecha/get_activity_by_user', $user->id, $dia, $date, $dataday);
                }
            }

            if ($month_count == $day_month) break;
            $i = $k - 1;
        }
    }
    public function insert_pre_fecha_dia_subor($month)
    {
        $user = $this->session->userdata('usuario');
        $parents = Modules::run('users/get_jefes',$user->id);
        foreach($parents as $parent)
        {
            $date = getdate(time());
            $ano = $this->session->userdata('ano');
            $year = $date['year'];
            if ($ano != null) $year = $ano;
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

            //contadores para ir determinando la cantidad de dias de la semana que tiene el mes
            $dataday['lu'] = 0;
            $dataday['ma'] = 0;
            $dataday['mi'] = 0;
            $dataday['ju'] = 0;
            $dataday['vi'] = 0;
            $dataday['sa'] = 0;
            $dataday['do'] = 0;


            //esto aqui es para parar el ciclo el ultimo dia del mes cumpliendo con el pt
            $day = mktime(0, 0, 0, $month, $month_count, $year);
            $dia = strftime('%A', $day);

            if ($dia == "Tuesday") $day_month += 1;
            if ($dia == "Wednesday") $day_month += 2;
            if ($dia == "Thursday") $day_month += 3;
            if ($dia == "Friday") $day_month += 4;
            if ($dia == "Saturday") $day_month += 5;
            if ($dia == "Sunday") $day_month += 6;

            for ($i = 1; $i <= $day_month; $i++) {
                $day_number = 1;
                $show = false;

                for ($k = $i; $k <= ($i + 6); $k++) {
                    if ($k > $day_month) break;
                    if ($day_number == 8) $day_number = 1;


                    $second = mktime(0, 0, 0, $month, $month_count, $year);
                    $date = strftime('%d/%m/%Y', $second);
                    $dia = strftime('%A', $second);

                    if ($dia == "Monday") $dia = "Lunes";
                    if ($dia == "Tuesday") $dia = "Martes";
                    if ($dia == "Wednesday") $dia = "Miércoles";
                    if ($dia == "Thursday") $dia = "Jueves";
                    if ($dia == "Friday") $dia = "Viernes";
                    if ($dia == "Saturday") $dia = "Sábado";
                    if ($dia == "Sunday") $dia = "Domingo";

                    if ($day_number == 1) {
                        if ($dia == $lunes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $dataday['lu']++;
                            $show = true;
                        }
                    }
                    if ($day_number == 2) {
                        if ($dia == $martes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $dataday['ma']++;
                            $show = true;
                        }
                    }
                    if ($day_number == 3) {
                        if ($dia == $miercoles) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $dataday['mi']++;
                            $show = true;
                        }
                    }
                    if ($day_number == 4) {
                        if ($dia == $jueves) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $dataday['ju']++;
                            $show = true;
                        }
                    }
                    if ($day_number == 5) {
                        if ($dia == $viernes) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $dataday['vi']++;
                            $show = true;
                        }
                    }
                    if ($day_number == 6) {
                        if ($dia == $sabado) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $dataday['sa']++;
                            $show = true;
                        }
                    }
                    if ($day_number == 7) {
                        if ($dia == $domingo) {
                            echo ' ' . $month_count;
                            $month_count++;
                            $dataday['do']++;
                            $show = true;
                        }
                    }
                    $day_number++;

                    if ($show) {
                        echo Modules::run('predefinidas_dias_fecha/insert_pre_subor',$parent->id ,$user->id, $dia, $date, $dataday);
                    }
                }

                if ($month_count == $day_month) break;
                $i = $k - 1;
            }
        }

    }

    public function insert_predate($month)
    {
        $user = $this->session->userdata('usuario');
        Modules::run('predate/insert_date_pt',$month,$user->id);
    }
    public function insert_predate_subor($month)
    {
        $user = $this->session->userdata('usuario');
        $parents = Modules::run('users/get_jefes',$user->id);
        foreach($parents as $parent)
        {
            Modules::run('predate/insert_predate_subor_pt',$month,$parent->id,$user->id);
        }

    }

    public function insert_datos_jefe($month,$year)
    {
        $user = $this->session->userdata('usuario');
        Modules::run('pt/insert_datos_jefes', $month,$year, $user->id);
    }
    public function insert_datos_empresa($month)
    {
        $user = $this->session->userdata('usuario');
        Modules::run('pt/insert_datos_empresa', $month, $user->id);
    }

    public function insert_vacation($month, $year)
    {
        $user = $this->session->userdata('usuario');
        Modules::run('pt/insert_vacation', $month, $year, $user->id);
    }
}
