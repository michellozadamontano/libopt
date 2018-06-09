<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Flujo_informativo extends MX_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Mdl_flujo_informativo');
        $this->load->library('form_validation');
        $this->load->library('excel');
    }

    public function get_all()
    {
        return $this->Mdl_flujo_informativo->get_all();
    }

    public function duplicar($id)
    {
        $prod = $this->get_by_id($id);
        $prod2 = $prod;
        unset($prod2->id);

        foreach ($prod2 as $key => $item) {
            if (!$this->Mdl_flujo_informativo->field_exists($key)) {
                unset($prod2->$key);
            }
        }
        $this->Mdl_flujo_informativo->insert($prod2);
        redirect(site_url('flujo_informativo'));
    }


    public function get_by_id($id)
    {
        return $this->Mdl_flujo_informativo->get_by_id($id);
    }
    //procediminetos para el reporte del flujo.
    public function get_flujo_by_month_year($month,$year,$categoria_id)
    {
        return $this->Mdl_flujo_informativo->get_flujo_by_month_year($month,$year,$categoria_id);
    }
    public function get_fecha_flujo($month,$year,$flujo_id)
    {
        return $this->Mdl_flujo_informativo->get_fecha_flujo($month,$year,$flujo_id);
    }
    public function get_participantes_flujo($flujo_id)
    {
        return $this->Mdl_flujo_informativo->get_participantes_flujo($flujo_id);
    }
    public function get_flujo_by_grupo_participantes($group_id,$month,$year)
    {
        return $this->Mdl_flujo_informativo->get_flujo_by_grupo_participantes($group_id,$month,$year);
    }
    public function get_date_by_activity($flujo)
    {
        return $this->Mdl_flujo_informativo->get_date_by_activity($flujo);
    }
    public function get_all_by_year($categoria_id)
    {
        $date = getdate(time());
        return $this->Mdl_flujo_informativo->get_all_by_year($date['year'],$categoria_id);
    }


    public function index()
    {
        $user = $this->session->userdata('usuario');
        if($user != null)
        {
            $q = urldecode($this->input->get('q', TRUE));
            $start = intval($this->input->get('start'));

            if ($q <> '') {
                $config['base_url'] = site_url('flujo_informativo?q=' . urlencode($q));
                $config['first_url'] = site_url('flujo_informativo?q=' . urlencode($q));
            } else {
                $config['base_url'] = site_url('flujo_informativo');
                $config['first_url'] = site_url('flujo_informativo');
            }

            $config['per_page'] = 100;
            $config['page_query_string'] = TRUE;
            $config['total_rows'] = $this->Mdl_flujo_informativo->total_rows($q);
            $flujo_informativo = $this->Mdl_flujo_informativo->get_limit_data($config['per_page'], $start, $q);

            $this->load->library('pagination');
            $this->pagination->initialize($config);

            $data = array(
                'flujo_informativo_data' => $flujo_informativo,
                'q' => $q,
                'pagination' => $this->pagination->create_links(),
                'total_rows' => $config['total_rows'],
                'start' => $start,
            );

            $data['categoria'] = Modules::run('categoria_flujo/get_all');
            $data['menuactivo'] = 'flujo_informativo';
           // $data['controlador'] = 'directivo';
            $data['title'] = 'flujo_informativo';
            $data['vista'] = $this->load->view('flujo_informativo_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else
        {
            redirect('auth/login', 'refresh');
        }

    }
    public function import_excel()
    {
        $data['page'] = 'import';
        $data['menuactivo'] = 'import';
        $data['title'] = 'Import XLSX | TechArise';
        $data['vista'] = $this->load->view('import', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function to_mysql()
    {

        //obtenemos el archivo subido mediante el formulario
        $file = $_FILES['excel']['name'];

        //comprobamos si existe un directorio para subir el excel
        //si no es así, lo creamos
        if(!is_dir("./excel_files/"))
            mkdir("./excel_files/", 0777);

        //comprobamos si el archivo ha subido para poder utilizarlo
        if ($file && copy($_FILES['excel']['tmp_name'],"./excel_files/".$file))
        {

            //queremos obtener la extensión del archivo
            $trozos = explode(".", $file);

            //solo queremos archivos excel
            if($trozos[1] != "xlsx" && $trozos[1] != "xls") return;

            /** archivos necesarios */
            require_once APPPATH . './third_party/Classes/PHPExcel.php';
            require_once APPPATH . './third_party/Classes/PHPExcel/Reader/Excel2007.php';

            //creamos el objeto que debe leer el excel
            $objReader = new PHPExcel_Reader_Excel2007();
            $objPHPExcel = $objReader->load('./excel_files/'.$file);

            //número de filas del archivo excel
            $rows = $objPHPExcel->getActiveSheet()->getHighestRow();

            //obtenemos el nombre de la tabla que el usuario quiere insertar el excel
            $table_name = 'import';//trim($this->security->xss_clean($this->input->post("table")));

            //obtenemos los nombres que el usuario ha introducido en el campo text del formulario,
            //se supone que deben ser los campos de la tabla de la base de datos.
            $fields_table = ['A','B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];//explode(",", $this->security->xss_clean($this->input->post("fields")));



            //array con las letras de la cabecera de un archivo excel
            $letras = array(
                'A','B', 'C', 'D', 'E', 'F', 'G',
                'H', 'I', 'J', 'K', 'L', 'M', 'N',
                'O', 'P', 'Q', 'R', 'S', 'T', 'U',
                'V', 'W', 'X', 'Y', 'Z'
            );
            $cantidad = count($fields_table);
            $categoria = 1;
            $this->Mdl_flujo_informativo->delete_import_plan_anual();
            //recorremos el excel y creamos un array para después insertarlo en la base de datos
            for($i = 7;$i <= $rows; $i++)
            {
                //ahora recorremos los campos del formulario para ir creando el array de forma dinámica
                $value = '';
                //inicializamos sql como un array
                $sql = array();
                for($z = 0; $z < $cantidad; $z++)
                {
                    if($value == '')
                    {
                        $value = $objPHPExcel->getActiveSheet()->getCell($letras[$z].$i)->getCalculatedValue();

                        if(substr($value,0,2) == 'I.')
                        {
                            $categoria = 1;
                        }
                        if(substr($value,0,3) == 'II.')
                        {
                            $categoria = 2;
                        }
                        if(substr($value,0,4) == 'III.')
                        {
                            $categoria = 3;
                        }
                        if(substr($value,0,3) == 'IV.')
                        {
                            $categoria = 4;
                        }
                        if(substr($value,0,2) == 'V.')
                        {
                            $categoria = 5;
                        }
                        if(substr($value,0,3) == 'VI.')
                        {
                            $categoria = 6;
                        }
                    }

                    $sql[$i][trim($fields_table[$z])] = $objPHPExcel->getActiveSheet()->getCell($letras[$z].$i)->getCalculatedValue();
                    $Actividad = '';
                    foreach ($sql as $item)
                    {
                        $cant = count($item);
                        if($cant > 1 && $cant < 3) {
                            $Actividad = $item['B'];
                        }
                    }
                    if($Actividad === null) {
                        break;
                    }
                    if($cant === 16)
                    {
                        foreach ($sql as $item)
                        {
                            $data['categoria_flujo_id'] = $categoria;
                            $data['actividad'] = $item['B'];
                            $data['dirige'] = $item['O'];
                            $data['participantes'] = $item['P'];

                           /* $data['Ene'] = $item['C'];
                            $data['Feb'] = $item['D'];
                            $data['Mar'] = $item['E'];
                            $data['Abr'] = $item['F'];
                            $data['May'] = $item['G'];
                            $data['Jun'] = $item['H'];
                            $data['Jul'] = $item['I'];
                            $data['Ago'] = $item['J'];
                            $data['Sep'] = $item['K'];
                            $data['Oct'] = $item['L'];
                            $data['Nov'] = $item['M'];
                            $data['Dic'] = $item['N'];*/

                            $plan_id = $this->Mdl_flujo_informativo->insert_plan_anual($data);

                            $datafecha['plan_id'] = $plan_id;

                            $datafecha['mes'] = 'Ene';
                            $datafecha['dias'] = $item['C'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);

                            $datafecha['mes'] = 'Feb';
                            $datafecha['dias'] = $item['D'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Mar';
                            $datafecha['dias'] = $item['E'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Abr';
                            $datafecha['dias'] = $item['F'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'May';
                            $datafecha['dias'] = $item['G'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Jun';
                            $datafecha['dias'] = $item['H'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Jul';
                            $datafecha['dias'] = $item['I'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Ago';
                            $datafecha['dias'] = $item['J'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Sep';
                            $datafecha['dias'] = $item['K'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Oct';
                            $datafecha['dias'] = $item['L'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Nov';
                            $datafecha['dias'] = $item['M'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);


                            $datafecha['mes'] = 'Dic';
                            $datafecha['dias'] = $item['N'];
                            $this->Mdl_flujo_informativo->insert_plan_fechas($datafecha);
                        }


                    }

                }

            }
            $this->session->set_flashdata('message', 'Datos importado correctamente');
            redirect(site_url('flujo_informativo/import_excel'));

            /*echo "<pre>";
            var_dump($sql); exit();
            */

            //insertamos los datos del excel en la base de datos
          /*  $import_excel = $this->Mdl_flujo_informativo->insert_task_import($sql);

            //comprobamos si se ha guardado bien
            if($import_excel == TRUE)
            {
               // echo "El archivo ha sido importado correctamente";
                $this->session->set_flashdata('message', 'El archivo ha sido importado correctamente');
                redirect(site_url('flujo_informativo/import_excel'));
            }else{
               // echo "Ha ocurrido un error";
                $this->session->set_flashdata('message', 'Ha ocurrido un error');
                redirect(site_url('flujo_informativo/import_excel'));
            }*/

            //finalmente, eliminamos el archivo pase lo que pase
            unlink("./excel_files/".$file);

        }else{
            $this->session->set_flashdata('message', 'Debes subir un archivo');
            redirect(site_url('flujo_informativo/import_excel'));
        }

    }

    public function show_view_report()
    {
        $data['menuactivo'] = 'flujo_mes_report';
        $data['vista'] = $this->load->view('show_view_report', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function flujo_mensual_report()
    {
        $data['categoria'] = Modules::run('categoria_flujo/get_all');
        $data['menuactivo'] = 'flujo_mes_report';
        $data['presidente'] = Modules::run('users/get_by_rol_id',4);
        $data['month'] = $this->input->post('month', TRUE);
        $view = $this->load->view('flujo_mensual_report',$data,true);
        require_once APPPATH.'./third_party/mpdf60/mpdf.php';

        $pdf = new mPDF('utf-8','A3',12);
        $pdf->AddPage('L');
        $pdf->WriteHTML($view);
        $pdf->Output();

       /* $file="demo.xls";

        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=$file");
        echo $view;*/
    }
    public function export_excel()
    {
        $data['categoria'] = Modules::run('categoria_flujo/get_all');
        $data['presidente'] = Modules::run('users/get_by_rol_id',4);
        $data['menuactivo'] = 'flujo_mes_report';
        $data['month'] = $this->input->post('month', TRUE);
        $view = $this->load->view('flujo_mensual_report',$data,true);

         $file="plan_mensual.xls";

       /*  header("Content-type: application/vnd.ms-excel");
         header("Content-Disposition: attachment; filename=$file");
         echo utf8_encode($view) ;*/

        header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
        header("Content-Disposition: filename=$file");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo utf8_decode($view);
    }
    public function plan_anual()
    {
        $data['categoria'] = Modules::run('categoria_flujo/get_all');
        $data['presidente'] = Modules::run('users/get_by_rol_id',4);
        $data['menuactivo'] = 'flujo_mes_report';
        $data['month'] = $this->input->post('month', TRUE);
        $view = $this->load->view('flujo_anual_report',$data,true);

        $file="plan_anual.xls";

        header("Content-type: application/vnd.ms-excel; name='excel'; charset=utf-8");
        header("Content-Disposition: filename=$file");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo utf8_decode($view);
    }

    public function read($id)
    {
        $row = $this->Mdl_flujo_informativo->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'actividad' => $row->actividad,
                'hora_inicio' => $row->hora_inicio,
                'hora_fin' => $row->hora_fin,
                'dirigente' => $row->dirigente,
                'categoria_id' => $row->nombre,
                'fechas'=> $this->get_date_by_activity($row->id),
                'participantes'=> Modules::run('grupo_participantes/get_by_flujo_id',$row->id),

            );


            $data['menuactivo'] = 'flujo_informativo';
          //  $data['controlador'] = 'directivo';
            $data['title'] = 'flujo_informativo';
            $data['vista'] = $this->load->view('flujo_informativo_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('flujo_informativo'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Adicionar',
            'action' => site_url('flujo_informativo/create_action'),
            'id' => set_value('id'),
            'actividad' => set_value('actividad'),
            'hora_inicio' => set_value('hora_inicio'),
            'hora_fin' => set_value('hora_fin'),
            'fechas' => set_value('fechas'),
            'grupoFlujo' => set_value('grupoFlujo'),
            'dirigente' => set_value('dirigente'),
            'categoria_id' => Modules::run('categoria_flujo/get_all'),
        );
        $data['grupo_participantes'] = Modules::run('grupo_participantes/get_all');


        $data['menuactivo'] = 'flujo_informativo';
        //$data['controlador'] = 'directivo';
        $data['title'] = 'flujo_informativo';
        $data['vista'] = $this->load->view('flujo_informativo_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'actividad' => $this->input->post('actividad', TRUE),
                'hora_inicio' => $this->input->post('hora_inicio', TRUE),
                'hora_fin' => $this->input->post('hora_fin', TRUE),
                'dirigente' => $this->input->post('dirigente', TRUE),
                'categoria_id' => $this->input->post('categoria_id', TRUE),
            );

            $inserid = $this->Mdl_flujo_informativo->insert($data);
            //compruebo primero si se introdujo un rango de fecha.
            if($this->input->post('flujo_rango'))
            {
                $fecha_desde = $this->input->post('flujo_desde', TRUE);
                $fecha_hasta = $this->input->post('flujo_hasta', TRUE);
                //calculo la diferencia de dias.
                //cambio el formato de las fechas al Y-m-d para poder calcular los dias
                $fecha_d = Modules::run('adminsettings/fecha_americana',$fecha_desde);
                $fecha_h = Modules::run('adminsettings/fecha_americana',$fecha_hasta);
                $dif = Modules::run('adminsettings/date_diff',$fecha_d,$fecha_h);

                //ahora agrego todas las fechas para esta actividad en este rango
                for($i = 0; $i <= $dif; $i++)
                {
                    $new_date = Modules::run('adminsettings/operacion_fecha',$fecha_desde,$i);
                    $data_fecha['fecha'] = Modules::run('adminsettings/convertDateToMsSQL',trim($new_date));
                    $data_fecha['flujo_informativo_id'] = $inserid;
                    $this->Mdl_flujo_informativo->insert_fecha_flujo($data_fecha);
                }

            }
            else{
                $fecha_flujo = explode(',',$this->input->post('fecha_flujo', TRUE)) ;
                if($fecha_flujo != null){
                    foreach($fecha_flujo as $item){
                        $data_fecha['fecha'] = Modules::run('adminsettings/convertDateToMsSQL',trim($item));
                        $data_fecha['flujo_informativo_id'] = $inserid;
                        $this->Mdl_flujo_informativo->insert_fecha_flujo($data_fecha);
                    }
                }
            }

            $grupo_particip = $this->input->post('grupo_participantes', TRUE);
            if($grupo_particip != null){
                foreach($grupo_particip as $value){
                    $data_grupo['grupo_participantes_id'] = $value;
                    $data_grupo['flujo_informativo_id'] = $inserid;
                    $this->Mdl_flujo_informativo->insert_flujo_grupo($data_grupo);
                }
            }
            $this->session->set_flashdata('message', 'Registro Insertado Correctamente');


            if ($this->input->post('btnsubmit') == 'Guardar y Continuar') {
                redirect(site_url('flujo_informativo/update/' . $inserid));
            } else {
                redirect(site_url('flujo_informativo'));
            }


        }
    }
    public function actividad_import()
    {
        //con este metodo voy a mostrar la vista de las tareas anuales a importar
        $data['categoria_id'] = Modules::run('categoria_flujo/get_all');
        $data['menuactivo'] = 'flujo_informativo';
        //$data['controlador'] = 'directivo';
        $data['title'] = 'flujo_informativo';
        $data['vista'] = $this->load->view('flujo_import_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function get_actividad_import()
    {
        //esta funcion me va a cargar las tareas segun una categoría.
        $catId = $this->input->post('categoria_id', TRUE);
        $data['cat_id'] = $catId;
        $data['categoria_id'] = Modules::run('categoria_flujo/get_all');
        $data['menuactivo'] = 'flujo_informativo';
        $data['actividades'] = $this->Mdl_flujo_informativo->get_actividad_import_by_category($catId);
        $data['vista'] = $this->load->view('flujo_import_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function get_fecha_actividad_import($actividadId)
    {
        return $this->Mdl_flujo_informativo->get_fecha_actividad_import($actividadId);
    }
    public function form_insert_plan($importId,$categId)
    {
        $data['menuactivo'] = 'flujo_informativo';
        $data['categId'] = $categId;
        $data['importId'] = $importId;
        $data['grupo_participantes'] = Modules::run('grupo_participantes/get_all');
        $data['vista'] = $this->load->view('form_insert_plan', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function form_insert_plan_action()
    {
        //esta funcion inserta las actividades que el usuario selecciona del plan anual
        $categId = $this->input->post('categId', TRUE);
        $importId = $this->input->post('importId', TRUE);
        $fechas = $this->get_fecha_actividad_import($importId);
        $actividad = $this->Mdl_flujo_informativo->get_actividad_import_by_id($importId);

        $data = array(
            'actividad' => $actividad->actividad,
            'hora_inicio' => $this->input->post('hora_inicio', TRUE),
            'hora_fin' => $this->input->post('hora_fin', TRUE),
            'dirigente' => $actividad->dirige,
            'categoria_id' => $categId,
        );
        $inserid = $this->Mdl_flujo_informativo->insert($data);
        $dataU['agregada'] = 1;
        $this->Mdl_flujo_informativo->update_import_plan($importId, $dataU);
        $date = getdate(time());
        $mes = 1;
        foreach ($fechas as $f)
        {
            if($f->dias != null)
            {
                if(strlen($f->dias) == 1)
                {
                    $fecha = trim($f->dias).'/'.$mes.'/'.$date['year'];
                    $datafecha['fecha'] = Modules::run('Adminsettings/convertDateToMsSQL',$fecha);
                    $datafecha['flujo_informativo_id'] = $inserid;
                    $this->Mdl_flujo_informativo->insert_fecha_flujo($datafecha);
                }
                if(strlen($f->dias) > 1)
                {
                    //voy a remplazar si existe las y por comas
                    $stringFecha = str_replace('y',',',$f->dias);
                    $coma = explode(',',$stringFecha);
                    $desde_hasta = explode('-',$f->dias);

                    if(count($coma) > 1)
                    {
                        foreach ($coma as $value)
                        {
                            $fecha = trim($value).'/'.$mes.'/'.$date['year'];
                            //$datafecha['plan_id'] = $plan_id;
                            $datafecha['fecha'] = Modules::run('Adminsettings/convertDateToMsSQL',$fecha);
                            $datafecha['flujo_informativo_id'] = $inserid;
                            $this->Mdl_flujo_informativo->insert_fecha_flujo($datafecha);
                        }
                    }
                    if(count($desde_hasta) === 2)
                    {
                        for ($i = trim($desde_hasta[0]), $iMax = trim($desde_hasta[1]); $i<= $iMax; $i++)
                        {
                            $fecha = $i.'/'.$mes.'/'.$date['year'];
                            $datafecha['fecha'] = Modules::run('Adminsettings/convertDateToMsSQL',$fecha);
                            $datafecha['flujo_informativo_id'] = $inserid;
                            $this->Mdl_flujo_informativo->insert_fecha_flujo($datafecha);
                        }
                    }
                }
            }
            $mes++;
        }
        $grupo_particip = $this->input->post('grupo_participantes', TRUE);
        if($grupo_particip != null){
            foreach($grupo_particip as $value){
                $data_grupo['grupo_participantes_id'] = $value;
                $data_grupo['flujo_informativo_id'] = $inserid;
                $this->Mdl_flujo_informativo->insert_flujo_grupo($data_grupo);
            }
        }
        $this->session->set_flashdata('message', 'Registro Insertado Correctamente');

        $data['cat_id'] = $categId;
        $data['categoria_id'] = Modules::run('categoria_flujo/get_all');
        $data['menuactivo'] = 'flujo_informativo';
        $data['actividades'] = $this->Mdl_flujo_informativo->get_actividad_import_by_category($categId);
        $data['vista'] = $this->load->view('flujo_import_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);

    }

    public function update($id)
    {
        $row = $this->Mdl_flujo_informativo->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Editar',
                'action' => site_url('flujo_informativo/update_action'),
                'id' => set_value('id', $row->id),
                'actividad' => set_value('actividad', $row->actividad),
                'hora_inicio' => set_value('hora_inicio', $row->hora_inicio),
                'hora_fin' => set_value('hora_fin', $row->hora_fin),
                'dirigente' => set_value('dirigente', $row->dirigente),
                'categoria_id' => Modules::run('categoria_flujo/get_all'),
            );
            $data['grupo_participantes'] = Modules::run('grupo_participantes/get_all');
            $data['cat_id']= $row->categoria_id;
            $grupo_by_flujo = Modules::run('grupo_participantes/get_by_flujo_id',$row->id);
            $fechas_flujo = $this->Mdl_flujo_informativo->get_fechas_by_flujoId($row->id);
            $fechas = "";
            $i = 1;
            foreach ($fechas_flujo as $item)
            {
                $fechas .= Modules::run('adminsettings/fecha_formato_esp',$item->fecha);
                if($i < count($fechas_flujo))$fechas .= ',';
                $i++;
            }
            $grupoFlujo = "";
            $k=1;
            foreach ($grupo_by_flujo as $g)
            {
                $grupoFlujo .= $g->grupo_participantes_id;
                if($k < count($grupo_by_flujo))$grupoFlujo .= ',';
            }
            $data['fechas'] = $fechas;
            $data['grupoFlujo'] = $grupoFlujo;
            $data['menuactivo'] = 'flujo_informativo';
           // $data['controlador'] = 'directivo';
            $data['title'] = 'flujo_informativo';
            $data['vista'] = $this->load->view('flujo_informativo_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('flujo_informativo'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'actividad' => $this->input->post('actividad', TRUE),
                'hora_inicio' => $this->input->post('hora_inicio', TRUE),
                'hora_fin' => $this->input->post('hora_fin', TRUE),
                'dirigente' => $this->input->post('dirigente', TRUE),
                'categoria_id' => $this->input->post('categoria_id', TRUE),
            );

            $this->Mdl_flujo_informativo->update($this->input->post('id', TRUE), $data);
            //compruebo primero si se introdujo un rango de fecha.
            if($this->input->post('flujo_rango'))
            {
                // primero borro las fechas de este flujo e inserto las nuevas.
                $this->Mdl_flujo_informativo->delete_fecha_flujo($this->input->post('id', TRUE));

                $fecha_desde = $this->input->post('flujo_desde', TRUE);
                $fecha_hasta = $this->input->post('flujo_hasta', TRUE);
                //calculo la diferencia de dias.
                //cambio el formato de las fechas al Y-m-d para poder calcular los dias
                $fecha_d = Modules::run('adminsettings/fecha_americana',$fecha_desde);
                $fecha_h = Modules::run('adminsettings/fecha_americana',$fecha_hasta);
                $dif = Modules::run('adminsettings/date_diff',$fecha_d,$fecha_h);

                //ahora agrego todas las fechas para esta actividad en este rango
                for($i = 0; $i <= $dif; $i++)
                {
                    $new_date = Modules::run('adminsettings/operacion_fecha',$fecha_desde,$i);
                    $data_fecha['fecha'] = Modules::run('adminsettings/convertDateToMsSQL',trim($new_date));
                    $data_fecha['flujo_informativo_id'] = $this->input->post('id', TRUE);
                    $this->Mdl_flujo_informativo->insert_fecha_flujo($data_fecha);
                }

            }
            else{
                $fecha_flujo = explode(',',$this->input->post('fecha_flujo', TRUE)) ;
                if($fecha_flujo != null){
                    // primero borro las fechas de este flujo e inserto las nuevas.
                    $this->Mdl_flujo_informativo->delete_fecha_flujo($this->input->post('id', TRUE));
                    foreach($fecha_flujo as $item){
                        $data_fecha['fecha'] = Modules::run('adminsettings/convertDateToMsSQL',trim($item));
                        $data_fecha['flujo_informativo_id'] = $this->input->post('id', TRUE);
                        $this->Mdl_flujo_informativo->insert_fecha_flujo($data_fecha);
                    }
                }
            }

            $grupo_particip = $this->input->post('grupo_participantes', TRUE);
            if($grupo_particip != null){
                //borro los grupos asociados y creo los nuevos
                $this->Mdl_flujo_informativo->delete_flujo_grupo($this->input->post('id', TRUE));
                foreach($grupo_particip as $value){
                    $data_grupo['grupo_participantes_id'] = $value;
                    $data_grupo['flujo_informativo_id'] = $this->input->post('id', TRUE);
                    $this->Mdl_flujo_informativo->insert_flujo_grupo($data_grupo);
                }
            }

            $this->session->set_flashdata('message', 'Registro Actualizado');


            if ($this->input->post('btnsubmit') == 'Save and Continue') {
                redirect(site_url('flujo_informativo/update/' . $this->input->post('id', TRUE)));
            } else {
                redirect(site_url('flujo_informativo'));
            }


        }
    }

    public function delete($id)
    {
        $row = $this->Mdl_flujo_informativo->get_by_id($id);

        if ($row) {
            $resultado = $this->Mdl_flujo_informativo->delete($id);
            if ($resultado["code"] != 0) {
                $this->session->set_flashdata("message", $resultado["code"] . ": " . $resultado["message"]);
            } else {
                $this->session->set_flashdata('message', 'Registro Eliminado');
            }
            redirect(site_url('flujo_informativo'));
        } else {
            $this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('flujo_informativo'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('actividad', 'actividad', 'trim|required');
        $this->form_validation->set_rules('hora_inicio', 'hora inicio', 'trim|required');
        $this->form_validation->set_rules('hora_fin', 'hora fin', 'trim|required');
        $this->form_validation->set_rules('dirigente', 'dirigente', 'trim|required');
        $this->form_validation->set_rules('categoria_id', 'categoria id', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Flujo_informativo.php */
/* Location: ./application/controllers/Flujo_informativo.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-03-22 01:54:07 */
/* http://harviacode.com */