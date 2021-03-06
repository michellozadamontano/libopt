<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Analisis_model extends CI_Model
{

    public $table = 'analisis';
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

    // get data by id
    public function get_by_id($id)
    {
        $this->db->where($this->id, $id);
        return $this->db->get($this->table)->row();
    }

    // get total rows
    public function total_rows($user_id, $year)
    {
        $array = array('users_id' => $user_id, 'ano' => $year);
        $this->db->where($array);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }
    public function CheckAnalisis($userId,$month,$year)
    {
        $array = array('users_id' => $userId,'mes'=>$month, 'ano' => $year);
        $this->db->where($array);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function get_analisis($user_id, $month, $year)
    {
        $array = array('users_id' => $user_id, 'mes' => $month, 'ano' => $year);
        $this->db->where($array);
        return $this->db->get($this->table)->row();
    }

    // get data with limit and search
    public function get_limit_data($limit, $start = 0, $user_id, $year)
    {
        $this->db->order_by('mes', $this->order);
        $array = array('users_id' => $user_id, 'ano' => $year);
        $this->db->where($array);
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

}

/* End of file Analisis_model.php */
/* Location: ./application/models/Analisis_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-12 20:11:20 */
/* http://harviacode.com */