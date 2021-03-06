<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Nuevas_tareas_model extends CI_Model
{

    public $table = 'nuevas_tareas';
    public $id = 'id';
    public $order = 'DESC';

    function __construct()
    {
        parent::__construct();
    }

    // get all
    function get_all()
    {
        $this->db->order_by($this->id, $this->order);
        return $this->db->get($this->table)->result();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    
    // get total rows
    function total_rows($user_id,$month,$year) {

        $this->db->where('users_id',$user_id);
        $this->db->where('mes',$month);
        $this->db->where('ano',$year);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $user_id,$month,$year) {
        $this->db->order_by($this->id, $this->order);
        $this->db->where('users_id',$user_id);
        $this->db->where('mes',$month);
        $this->db->where('ano',$year);
	    $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    function tareas_month($user_id,$month,$year) //total de tareas en el mes
    {
        $this->db->where('users_id',$user_id);
        $this->db->where('mes',$month);
        $this->db->where('ano',$year);
        return $this->db->get($this->table)->result();
    }
    //funcion que muestra las tareas extras para una determinada fecha
    function get_where_date($date,$user_id) {
        $array = array('fecha'=> $date,'users_id'=>$user_id);
        $this->db->where($array);
        $this->db->order_by('hora_ini');
        $query=$this->db->get($this->table)->result();
        return $query;
    }

    // insert data
    function insert($data)
    {
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update($this->table, $data);
    }

    // delete data
    function delete($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete($this->table);
    }

}

/* End of file Nuevas_tareas_model.php */
/* Location: ./application/models/Nuevas_tareas_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-14 14:30:30 */
/* http://harviacode.com */