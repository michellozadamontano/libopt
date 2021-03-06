<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tareas_incumplidas_model extends CI_Model
{

    public $table = 'tareas_incumplidas';
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
        $this->db->select('tareas_incumplidas.*,pt.hora,pt.actividad');
        $this->db->join('pt','tareas_incumplidas.pt_id = pt.id','inner');
        $this->db->where('tareas_incumplidas.users_id',$user_id);
        $this->db->where('month(pt.fecha)',$month);
        $this->db->where('year(pt.fecha)',$year);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0,$user_id,$month,$year) {
        $this->db->select('tareas_incumplidas.*,pt.hora,pt.actividad');
        $this->db->join('pt','tareas_incumplidas.pt_id = pt.id','inner');
        $this->db->where('tareas_incumplidas.users_id',$user_id);
        $this->db->where('month(pt.fecha)',$month);
        $this->db->where('year(pt.fecha)',$year);
        $this->db->order_by('tareas_incumplidas.id', $this->order);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    function total_incumplidas($user_id,$month,$year)
    {
        $this->db->select('tareas_incumplidas.*,pt.hora,pt.actividad');
        $this->db->join('pt','tareas_incumplidas.pt_id = pt.id','inner');
        $this->db->where('tareas_incumplidas.users_id',$user_id);
        $this->db->where('tareas_incumplidas.incumplida',1);
        $this->db->where('month(pt.fecha)',$month);
        $this->db->where('year(pt.fecha)',$year);
      //  $this->db->from($this->table);
        return $this->db->get($this->table)->result();
    }
    function total_suspendidas($user_id,$month,$year)
    {
        $this->db->select('tareas_incumplidas.*,pt.hora,pt.actividad');
        $this->db->join('pt','tareas_incumplidas.pt_id = pt.id','inner');
        $this->db->where('tareas_incumplidas.users_id',$user_id);
        $this->db->where('tareas_incumplidas.suspendida',1);
        $this->db->where('month(pt.fecha)',$month);
        $this->db->where('year(pt.fecha)',$year);
       // $this->db->from($this->table);
        return $this->db->get($this->table)->result();
    }
    function valida_incumplida($pt_id)
    {
        $this->db->where('pt_id',$pt_id);
        return $this->db->get($this->table)->row();
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

/* End of file Tareas_incumplidas_model.php */
/* Location: ./application/models/Tareas_incumplidas_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-11 03:27:45 */
/* http://harviacode.com */