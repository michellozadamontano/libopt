<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Predefinidas_model extends CI_Model
{

    public $table = 'predefinidas';
    public $id = 'id';
    public $order = 'DESC';
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

    // get data by id
    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }
    public function get_activity_by_user($user_id)
    {
        $this->db->where('users_id',$user_id);
        return $this->db->get($this->table)->result();
    }
    public function get_dia_hora($dia,$hora,$hora_fin,$user_id)
    {
        $array = array('dia'=>$dia,'hora'=>$hora,'hora_fin'=>$hora_fin,'users_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($this->table)->result();
    }
    public function validaTarea($dia,$hora,$user_id)
    {
        $array = array('dia'=>$dia,'hora'=>$hora,'users_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($this->table)->result();
    }
    public function get_dia($dia,$user_id)
    {
        $array = array('dia'=>$dia,'users_id'=>$user_id);
        $this->db->where($array);
        return $this->db->get($this->table)->result();
    }
    
    // get total rows
    public function total_rows($q = NULL) {
        $user = $this->session->userdata('usuario');
       /* $this->db->like('id', $q);
        $this->db->or_like('tarea', $q);
        $this->db->or_like('dia', $q);
        $this->db->or_like('hora', $q);*/
        $this->db->where('users_id',$user->id);
       // $this->db->or_like('users_id', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    public function get_limit_data($limit, $start = 0, $q = NULL) {
        $user = $this->session->userdata('usuario');
        $this->db->order_by('orden', 'ASC');
        $this->db->order_by('hora', 'ASC');
      /*  $this->db->like('id', $q);
        $this->db->or_like('tarea', $q);
        $this->db->or_like('dia', $q);
        $this->db->or_like('hora', $q);*/
        $this->db->like('tarea', $q);
        $this->db->where('users_id',$user->id);
     //   $this->db->or_like('users_id', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }

    // insert data
    public function insert($data)
    {
        $this->db->insert($this->table, $data);
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
    }
    public function get_max() {
        $this->db->select_max('id');
        $query = $this->db->get($this->table);
        $row=$query->row();
        $id=$row->id;
        return $id;
    }
    /* Trabajo con la tabla predefinidas_subor */
    public function insert_pre_sub($data)
    {
        $this->db->insert('predefinidas_subor', $data);
    }
    // update data
    public function update_pre_sub($id, $data)
    {
        $this->db->where($this->id, $id);
        $this->db->update('predefinidas_subor', $data);
    }
    // delete data
    public function delete_pre_sub($id)
    {
        $this->db->where($this->id, $id);
        $this->db->delete('predefinidas_subor');
    }
    public function delete_predef_subor($predef_id,$user_id)
    {
        $this->db->where('predefinidas_id',$predef_id);
        $this->db->where('users_id',$user_id);
        $this->db->delete('predefinidas_subor');
    }
    public function get_pre_sub($id_pre)
    {
        $this->db->where('predefinidas_id',$id_pre);
        return $this->db->get('predefinidas_subor')->result();
    }
    public function get_pre_subor_task($id_pre,$users_id)
    {
        $this->db->where('predefinidas_id',$id_pre);
        $this->db->where('users_id',$users_id);
        return $this->db->get('predefinidas_subor')->result();
    }
    public function valida_pre_sub($id_pre,$users_id)
    {
        $this->db->where('predefinidas_id',$id_pre);
        $this->db->where('users_id',$users_id);
        return $this->db->get('predefinidas_subor')->result();
    }

    /************/

}

/* End of file Predefinidas_model.php */
/* Location: ./application/models/Predefinidas_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-03 20:47:16 */
/* http://harviacode.com */