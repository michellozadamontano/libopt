<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Aprobacionpt_model extends CI_Model
{

    public $table = 'aprobacionpt';
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
    function validaAprobacion($user_id,$month,$year)
    {
        $data = array('users_id'=>$user_id,'month'=>$month,'year'=>$year);
        $this->db->where($data);
        return $this->db->get($this->table)->result();
    }
    function get_user_plan($user_id)//devuelve el listado de planes aprobado hasta la fecha para un usuario.
    {
        $this->db->order_by($this->id, $this->order);
        $this->db->where('users_id',$user_id);
        return $this->db->get($this->table)->result();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->like('id', $q);
	$this->db->or_like('users_id', $q);
	$this->db->or_like('fecha', $q);
	$this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->order_by($this->id, $this->order);
        $this->db->like('id', $q);
	$this->db->or_like('users_id', $q);
	$this->db->or_like('fecha', $q);
	$this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
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

/* End of file Aprobacionpt_model.php */
/* Location: ./application/models/Aprobacionpt_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-26 12:04:48 */
/* http://harviacode.com */