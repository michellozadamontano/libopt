<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdl_flujo_informativo extends CI_Model
{

    public $table = 'flujo_informativo';
    public $id = 'id';
    public $order = 'ASC';

    public function __construct()
    {
        parent::__construct();
    }

    // get all
    public function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }
    public function get_all_by_year($year,$categoria_id)
    {
        $this->db->distinct();
        $this->db->select('flujo_informativo.id,flujo_informativo.actividad,flujo_informativo.dirigente');
        $this->db->join('fecha_flujo','flujo_informativo.id = fecha_flujo.flujo_informativo_id','inner');
        $this->db->join('categoria_flujo','flujo_informativo.categoria_id = categoria_flujo.id','inner');
        $this->db->where('year(fecha_flujo.fecha)',$year);
        $this->db->where('categoria_flujo.id',$categoria_id);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    public function get_by_id($id)
    {
        $this->db->select('flujo_informativo.id,flujo_informativo.actividad,
        flujo_informativo.hora_inicio,flujo_informativo.hora_fin,
        flujo_informativo.dirigente,flujo_informativo.categoria_id,categoria_flujo.nombre');
        $this->db->join('categoria_flujo','flujo_informativo.categoria_id = categoria_flujo.id');
        $this->db->where('flujo_informativo.id', $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    public function total_rows($q = NULL)
    {
        $this->db->like('id', $q);
       // $this->db->or_like('actividad', $q);
       // $this->db->or_like('hora_inicio', $q);
      //  $this->db->or_like('hora_fin', $q);
      //  $this->db->or_like('dirigente', $q);
      //  $this->db->or_like('categoria_id', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    public function get_limit_data($limit, $start = 0, $q = NULL)
    {
        $this->db->select('flujo_informativo.id,flujo_informativo.actividad,
        flujo_informativo.hora_inicio,flujo_informativo.hora_fin,
        flujo_informativo.dirigente,categoria_flujo.nombre');
        $this->db->join('categoria_flujo','flujo_informativo.categoria_id = categoria_flujo.id');
        $this->db->order_by($this->id, $this->order);
        $this->db->like('flujo_informativo.id', $q);
        $this->db->or_like('flujo_informativo.actividad', $q);
        $this->db->or_like('flujo_informativo.hora_inicio', $q);
        $this->db->or_like('flujo_informativo.hora_fin', $q);
        $this->db->or_like('flujo_informativo.dirigente', $q);
        $this->db->or_like('categoria_flujo.nombre', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }
    //insert into fecha_flujo
    public function insert_fecha_flujo($data)
    {
        $this->db->insert('fecha_flujo',$data);
    }
    // eliminar las fechas de un determinada activad del flujo
    public function delete_fecha_flujo($flujo_id)
    {
        $this->db->where('flujo_informativo_id', $flujo_id);
        $this->db->delete('fecha_flujo');
    }
    //insert into flujo_grupo
    public function insert_flujo_grupo($data)
    {
        $this->db->insert('flujo_grupo',$data);
    }
    public function delete_flujo_grupo($flujo_id)
    {
        $this->db->where('flujo_informativo_id', $flujo_id);
        $this->db->delete('flujo_grupo');
    }
    //obtener las fechas segun el flujo
    public function get_fechas_by_flujoId($flujoId)
    {
        $this->db->where('flujo_informativo_id',$flujoId);
        return $this->db->get('fecha_flujo')->result();
    }

    // update data
    public function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    public function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
        return $this->db->error();
    }

    public function field_exists($field)
    {
        return $this->db->field_exists($field, $this->table);
    }
    /*TRATAMIENTO CON EL FLUJO DE INFORMACION*/
    public function get_flujo_by_month_year($month,$year,$categoria_id)
    {
        $this->db->distinct();
        $this->db->select('flujo_informativo.actividad,flujo_informativo.id,flujo_informativo.dirigente');
        $this->db->join('fecha_flujo','flujo_informativo.id = fecha_flujo.flujo_informativo_id','left');
        $query = array('month(fecha_flujo.fecha)'=>$month,'year(fecha_flujo.fecha)'=>$year,'flujo_informativo.categoria_id'=>$categoria_id);
        $this->db->where($query);
        return $this->db->get($this->table)->result();
    }
    public function get_fecha_flujo($month,$year,$flujo_id)
    {
        $this->db->select('fecha_flujo.fecha');
        $this->db->join('flujo_informativo','fecha_flujo.flujo_informativo_id = flujo_informativo.id');
        $query = array('month(fecha_flujo.fecha)'=>$month,'year(fecha_flujo.fecha)'=>$year,
            'fecha_flujo.flujo_informativo_id'=>$flujo_id);
        $this->db->where($query);
        return $this->db->get('fecha_flujo')->result();
    }
    public function get_participantes_flujo($flujo_id)
    {
        $this->db->select('grupo_participantes.titulo');
        $this->db->join('flujo_grupo','grupo_participantes.id = flujo_grupo.grupo_participantes_id','inner');
        $this->db->join('flujo_informativo ','flujo_grupo.flujo_informativo_id = flujo_informativo.id','inner');
        $this->db->where('flujo_informativo.id',$flujo_id);
        $query = $this->db->get('grupo_participantes')->result();
        return $query;
    }
    public function get_flujo_by_grupo_participantes($group_id,$month,$year)
    {
        $this->db->select('flujo_informativo.actividad,flujo_informativo.hora_inicio,
        flujo_informativo.hora_fin,fecha_flujo.fecha');
        $this->db->join('fecha_flujo','flujo_informativo.id = fecha_flujo.flujo_informativo_id','inner');
        $this->db->join('flujo_grupo','flujo_informativo.id = flujo_grupo.flujo_informativo_id','inner');
        $query = array('month(fecha_flujo.fecha)'=>$month,'year(fecha_flujo.fecha)'=>$year,
            'flujo_grupo.grupo_participantes_id'=>$group_id);
       // $query = array('month(fecha_flujo.fecha)'=>$month,'flujo_grupo.grupo_participantes_id'=>$group_id);
        $this->db->where($query);
        $result = $this->db->get($this->table)->result();
        return $result ;
    }
    //todas las fechas que pertenecen a una misma atividad del flujo
    public function get_date_by_activity($flujo)
    {
        $this->db->where('fecha_flujo.flujo_informativo_id',$flujo);
        return $this->db->get('fecha_flujo')->result();
    }
    // funcion que inserta las tareas importadas
    public function insert_task_import($data)
    {
        //si es un array y no está vacio
        if(!empty($data) && is_array($data))
        {
            //si se lleva a cabo la inserción
            if($this->db->insert_batch('import',$data))
            {
                return TRUE;
            }else{
                return FALSE;
            }
        }
    }
    public function insert_plan_anual($data)
    {
        $this->db->insert('import_plan_act_anual',$data);
        return $this->db->insert_id();
    }
    public function insert_plan_fechas($data)
    {
        $this->db->insert('import_plan_fechas',$data);
    }
    public function delete_import_plan_anual()
    {
        $this->db->empty_table('import_plan_act_anual');
        return $this->db->error();
    }
    public function get_actividad_import_by_category($catId)
    {
        //obtengo las actividades segun la categoria que le estoy pasando

        $this->db->where('categoria_flujo_id',$catId);
        return $this->db->get('import_plan_act_anual')->result();
    }
    public function get_actividad_import_by_id($id)
    {
        $this->db->where('id',$id);
        return $this->db->get('import_plan_act_anual')->row();
    }
    public function get_fecha_actividad_import($actividadId)
    {
        $this->db->where('plan_id',$actividadId);
        return $this->db->get('import_plan_fechas')->result();
    }
    public function update_import_plan($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update('import_plan_act_anual', $data);
    }

}

/* End of file Mdl_flujo_informativo.php */
/* Location: ./application/models/Mdl_flujo_informativo.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2017-03-22 01:54:07 */
/* http://harviacode.com */