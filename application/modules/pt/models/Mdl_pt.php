<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Mdl_pt extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_table() {
        $table = "pt";
        return $table;
    }

    public function get($order_by) {
        $table = $this->get_table();
        $this->db->order_by($order_by);
        $query=$this->db->get($table)->result();
        return $query;
    }
    public function get_by_id($id)
    {
        $table = $this->get_table();
        $this->db->where('id', $id);
        return $this->db->get($table)->row();
    }
    public function get_tareas($user_id,$fecha)
    {
        $table = $this->get_table();
        $this->db->where('user_id',$user_id);
        $this->db->where('fecha',$fecha);
        return $this->db->get($table)->result();
    }
    public function get_task_superior($user_id) // las tareas del jefe supremo
    {
        $table = $this->get_table();
        $this->db->select('distinct MONTH(fecha)as month,YEAR(fecha)as year');
        $this->db->where('user_id',$user_id);
        return $this->db->get($table)->result();
    }
    public function get_tareas_json($user_id)
    {
        $table = $this->get_table();
        $this->db->where('user_id',$user_id);
        return $this->db->get($table)->result();
    }
    public function get_with_limit($limit, $offset, $order_by) {
        $table = $this->get_table();
        $this->db->limit($limit, $offset);
        $this->db->order_by($order_by);
        $query=$this->db->get($table);
        return $query;
    }
    public function get_dias_vencidos($day,$month,$year,$user_id)
    {
        $table = $this->get_table();
        $datos = array('day(fecha)<='=>$day,'month(fecha)'=>$month,'year(fecha)'=>$year,'user_id'=>$user_id);
        $this->db->where($datos);
        return $this->db->get($table)->result();
    }

    public function get_where($id) {
        $table = $this->get_table();
        $this->db->where('id', $id);
        $query=$this->db->get($table);
        return $query;
    }
    public function get_where_date($date,$user_id) {
        $table = $this->get_table();
        $array = array('fecha'=> $date,'user_id'=>$user_id);
        $this->db->where($array);
        $this->db->order_by('hora');
        $query=$this->db->get($table)->result();
        return $query;
    }

    public function find_task_subor($user_id,$fecha,$hora,$hora_fin,$parent_id)
    {
        $table = $this->get_table();
        $arreglo = array('user_id'=>$user_id,'fecha'=>$fecha,'hora'=>$hora,'hora_fin'=>$hora_fin,'parent_id'=>$parent_id);
        $this->db->where($arreglo);
        return $this->db->get($table)->row();
    }
    public function find_task_subor_directo($user_id,$fecha,$hora,$hora_fin)
    {
        $table = $this->get_table();
        $arreglo = array('user_id'=>$user_id,'fecha'=>$fecha,'hora'=>$hora,'hora_fin'=>$hora_fin);
        $this->db->where($arreglo);
        return $this->db->get($table)->row();
    }
    public function find_task_subor_directo_tempo($user_id,$fecha,$hora,$hora_fin) //tabla temporal
    {
        $table = 'temppt';
        $arreglo = array('user_id'=>$user_id,'fecha'=>$fecha,'hora'=>$hora,'hora_fin'=>$hora_fin);
        $this->db->where($arreglo);
        return $this->db->get($table)->row();
    }
    public function get_dia_hora($fecha,$hora,$hora_fin,$user_id)
    {
        //compruebo de que exista tarea en esta fecha y hora.
        $table = $this->get_table();
        $array = array('fecha'=>$fecha,'hora'=>$hora,'hora_fin'=>$hora_fin,'user_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($table)->result();
    }
    public function get_dia($fecha,$user_id)
    {
        $table = $this->get_table();
        $array = array('fecha'=>$fecha,'user_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($table)->result();
    }
    public function get_dia_tempo($fecha,$user_id)
    {
        $table = 'temppt';
        $array = array('fecha'=>$fecha,'user_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($table)->result();
    }
    public function get_where_custom($col, $value) {
        $table = $this->get_table();
        $this->db->where($col, $value);
        $query=$this->db->get($table);
        return $query;
    }

    public function _insert($data) {
        $table = $this->get_table();
        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function _update($id, $data) {
        $table = $this->get_table();
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }

    public function _delete($id) {
        $table = $this->get_table();
        $this->db->where('id', $id);
        $this->db->delete($table);
    }
    public function delete_task_subor($data)
    {
        $table = $this->get_table();
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('parent_id', $data['parent_id']);
        $this->db->where('hora', $data['hora']);
        $this->db->where('hora_fin', $data['hora_fin']);
        $this->db->where('fecha', $data['fecha']);
        $this->db->delete($table);
    }
    /* insertar en la tabla pt_subor las tareas a subordinados del plan*/
    public function insert_pt_subor($data)
    {
        $table = 'pt_subor';
        $this->db->insert($table, $data);
    }
    public function usuarios_con_tareas($pt_id)
    {
        //obtengo los usuarios que tienen la tarea del jefe.
        $table = 'pt_subor';
        $this->db->where('pt_id',$pt_id);
        return $this->db->get($table)->result();
    }
    public function verificar_usuario_con_tarea($pt_id,$user_id)
    {
        //obtengo el usuario que tiene la tarea del jefe.
        $table = 'pt_subor';
        $this->db->where('pt_id',$pt_id);
        $this->db->where('user_id',$user_id);
        return $this->db->get($table)->row();
    }

    /**
     * @param $pt_id
     * @param $fecha
     */
    public function actualiza_usuario_tarea($pt_id, $fecha)
    {
        //aquí solo actualizo la fecha para todos los user que tiene esta tarea
        $table = 'pt_subor';
        $this->db->where('pt_id',$pt_id);
        $this->db->update($table,['fecha'=>$fecha]);
    }
    public function elimina_usuario_tarea($pt_id,$user_id)
    {
        $table = 'pt_subor';
        $this->db->where('pt_id',$pt_id);
        $this->db->where('user_id',$user_id);
        $this->db->delete($table);
    }
    public function usuario_tareas_mes($month,$year,$user_id)
    {
        $table = 'pt_subor';
        $array = array('month(fecha)'=>$month,'year(fecha)'=>$year,'user_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($table)->result();
    }

    /*funcionalidas para la tabla temporar temppt*/
    public function _insert_temppt($data) {
        $table = 'temppt';
        $this->db->insert($table, $data);
    }
    public function _update_temppt($id, $data) {
        $table = 'temppt';
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }
    public function _delete_temppt($id) {
        $table = 'temppt';
        $this->db->where('id', $id);
        $this->db->delete($table);
    }
    public function delete_data_date($data)
    {
        $table = $this->get_table();
        $array = array('fecha'=>$data['fecha'],'user_id'=>$data['user_id']);
        $this->db->where($array);
        $this->db->delete($table);
    }
    public function _find_data_tempo($month, $user_id)//encuentra los datos temporales de este usuario
    {
        $table = 'temppt';
        $array = array('month(fecha)'=>$month,'user_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($table)->result();
    }
    public function find_task_subor_tempo($user_id,$fecha,$hora,$hora_fin,$parent_id)
    {
        // aqui verificando los usuarios subordinados con esta tarea en la tabla pt
        $table = 'pt';
        $arreglo = array('user_id'=>$user_id,'fecha'=>$fecha,'hora'=>$hora,'hora_fin'=>$hora_fin,'parent_id'=>$parent_id);
        $this->db->where($arreglo);
        return $this->db->get($table)->row();
    }
    public function get_dia_hora_tempo($fecha, $hora, $hora_fin, $user_id)
    {
        $table = 'temppt';
        $array = array('fecha'=>$fecha,'hora'=>$hora,'hora_fin'=>$hora_fin,'user_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($table)->result();
    }

    /*******************/
    public function check_month($month,$user_id,$year)
    {
        $table = $this->get_table();
        $this->db->where('month(fecha)',$month);
        $this->db->where('year(fecha)',$year);
        $this->db->where('user_id',$user_id);
        return $this->db->get($table)->result();
    }
    public function check_month_subor($month,$user_id,$year)
    {
        $table = $this->get_table();
        $this->db->where('month(fecha)',$month);
        $this->db->where('year(fecha)',$year);
        $this->db->where('user_id',$user_id);
        return $this->db->get($table)->result();
    }

    public function get_years() //listado de los años que se ha venido trabajando
    {
        $table = $this->get_table();
          $this->db->select('distinct year(fecha)as fecha');
        $this->db->order_by('fecha','DESC');
        return  $this->db->get($table)->result();
    }
    public function listar_tareas($user_id,$fecha)//listar las tareas de los usuarios
    {
        $table = $this->get_table();
        $this->db->where('user_id',$user_id);
        $this->db->where('fecha',$fecha);
        return $this->db->get($table)->result();
    }
    public function listar_fechas($user_id,$month,$year)//fechas de tareas
    {
        $table = $this->get_table();
       // $this->db->select('fecha')->distinct();
        $this->db->where('user_id',$user_id);
        $this->db->where('month(fecha)',$month);
        $this->db->where('year(fecha)',$year);
        //$this->db->group_by('fecha');
        return $this->db->get($table)->result();
    }
    public function dias_hasta_hora($user_id,$fecha_ini,$fecha_fin)//devuelve las fechas hasta el dia actual de consulta
    {
        $table = $this->get_table();
        $this->db->select('fecha')->distinct();
        $datos = array('user_id'=>$user_id,'fecha >='=>$fecha_ini,'fecha <='=>$fecha_fin);
        $this->db->where($datos);
        $this->db->order_by('fecha');
        return $this->db->get($table)->result();
    }
    public function valida_activity($data)
    {
        $table = $this->get_table();
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('fecha', $data['fecha']);
        $this->db->where('hora', $data['hora']);
        $this->db->where('hora_fin', $data['hora_fin']);
      //  $this->db->where('actividad', $data['actividad']);
        return $this->db->get($table);
    }
    public function check_data_date($data)//consulta para verificar que ese dia tenga algo
    {
        $table = $this->get_table();
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('fecha', $data['fecha']);
        return $this->db->get($table)->result();
    }
    public function valida_activity_date($data) //verifica si hay algo en esta fecha y hora
    {
        $table = $this->get_table();
        $this->db->where('user_id', $data['user_id']);
        $this->db->where('fecha', $data['fecha']);
        $this->db->where('hora', $data['hora']);
        $this->db->where('hora_fin', $data['hora_fin']);
        $query = $this->db->get($table)->result();
        return $this->db->get($table);
    }
    public function delete_pt_refresh($mes,$year,$user_id)
    {
        $table = $this->get_table();
        $array = array('month(fecha)'=>$mes,'year(fecha)'=>$year,'user_id'=>$user_id);
        $this->db->where($array);
        $this->db->delete($table);
    }

    public function count_where($column, $value) {
        $table = $this->get_table();
        $this->db->where($column, $value);
        $query=$this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    public function count_all() {
        $table = $this->get_table();
        $query=$this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    public function get_max() {
        $table = $this->get_table();
        $this->db->select_max('id');
        $query = $this->db->get($table);
        $row=$query->row();
        $id=$row->id;
        return $id;
    }

    public function _custom_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        return $query;
    }

}