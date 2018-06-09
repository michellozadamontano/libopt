<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mdl_auth extends CI_Model {

    function __construct() {
        parent::__construct();
    }
    function get_by_correo_password($correo, $password){
       // $password = md5($password);
        $table = $this->get_table();
        $this->db->where(array('email' => $correo ,'password' => $password ));
        $query = $this->db->get($table);
        return $query;
    }

    function update($id, $data) {
        $table = $this->get_table();
      //  $data['password'] = md5($data['password']);
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }

    function password($id, $data) {
        $table = $this->get_table();
       // $data['password'] = md5($data['password']);
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }

    function get_table() {
        $table = "users";
        return $table;
    }

    function get($order_by) {
        $table = $this->get_table();
        $this->db->order_by($order_by);
        $query = $this->db->get($table);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by) {
        $table = $this->get_table();
        $this->db->limit($limit, $offset);
        $this->db->order_by($order_by);
        $query = $this->db->get($table);
        return $query;
    }

    function get_where($id) {
        $table = $this->get_table();
        $this->db->where('id', $id);
        $query = $this->db->get($table);
        return $query;
    }

    function get_where_custom($col, $value) {
        $this->db->select('users.*,rol.rol,cargos.nombre_cargo');
        $this->db->join('rol','rol.id = users.rol_id');
        $this->db->join('cargos','cargos.id = users.cargo');


        $table = $this->get_table();
        $this->db->where($col, $value);
        $query = $this->db->get($table);
        return $query;
    }

    function _insert($data) {
        $table = $this->get_table();
        $this->db->insert($table, $data);
    }

    function _update($id, $data) {
        $table = $this->get_table();
        $this->db->where('id', $id);
        $this->db->update($table, $data);
    }

    function _delete($id) {
        $table = $this->get_table();
        $this->db->where('id', $id);
        $this->db->delete($table);
    }
    function get_cod()
    {
        return $this->db->get("systema")->row();
    }
    function insertcod($data){
        $this->db->insert('systema', $data);
    }
    function updateCod($id,$data)
    {
        $this->db->where('id', $id);
        $this->db->update('systema', $data);
    }

    function count_where($column, $value) {
        $table = $this->get_table();
        $this->db->where($column, $value);
        $query = $this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function count_all() {
        $table = $this->get_table();
        $query = $this->db->get($table);
        $num_rows = $query->num_rows();
        return $num_rows;
    }

    function get_max() {
        $table = $this->get_table();
        $this->db->select_max('id');
        $query = $this->db->get($table);
        $row = $query->row();
        $id = $row->id;
        return $id;
    }

    function _custom_query($mysql_query) {
        $query = $this->db->query($mysql_query);
        return $query;
    }

}
