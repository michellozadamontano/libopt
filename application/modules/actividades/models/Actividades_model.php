<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Actividades_model extends CI_Model
{

    public $table = 'actividades';
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
    function get_activity_by_group($id)
    {
        $this->db->where('grupo_id' , $id);
        $this->db->order_by('nombre_actividad');
        $query = $this->db->get($this->table)->result();
        return $query;
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->select('actividades.*,nombre_grupo');
        $this->db->join('grupo','grupo.id = actividades.grupo_id','inner');
        $this->db->like('actividades.id', $q);
        $this->db->or_like('grupo_id', $q);
        $this->db->or_like('nombre_actividad', $q);
        $this->db->or_like('nombre_grupo', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->select('actividades.*,nombre_grupo');
        $this->db->join('grupo','grupo.id = actividades.grupo_id','inner');
        $this->db->order_by("grupo_id", $this->order);
        $this->db->like('actividades.id', $q);
        $this->db->or_like('grupo_id', $q);
        $this->db->or_like('nombre_actividad', $q);
        $this->db->or_like('nombre_grupo', $q);
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

/* End of file Actividades_model.php */
/* Location: ./application/models/Actividades_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-31 13:20:29 */
/* http://harviacode.com */