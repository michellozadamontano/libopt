<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_model extends CI_Model
{

    public $table = 'users';
    public $id = 'id';
    public $order = 'ASC';

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
    function get_directivos()
    {
       // $this->db->where('rol_id',3);

        $roles = array(3, 5);
        $this->db->where_in('rol_id', $roles);
        return $this->db->get($this->table)->result();
    }
    function get_subor_directo($user_id,$parent_id)
    {
        $this->db->where('id',$user_id);
        $this->db->where('parent_id',$parent_id);
        return $this->db->get($this->table)->row();
    }

    // get data by id
    function get_by_id($id)
    {
        $this->db->select('users.*,rol.rol,cargos.nombre_cargo');
        $this->db->join('rol','rol.id = users.rol_id');
        $this->db->join('cargos','cargos.id = users.cargo');
        $this->db->where('users.id', $id);
        return $this->db->get($this->table)->row();
    }
    function get_by_rol_id($rol_id)//esta funcion solo la voy a llamar para saber si el usuario es el jefe general
    {
        $this->db->select('users.*,rol.rol,cargos.nombre_cargo');
        $this->db->join('rol','rol.id = users.rol_id');
        $this->db->join('cargos','cargos.id = users.cargo');
        $this->db->where('rol_id', $rol_id);
        return $this->db->get($this->table)->row();
    }
    //get user subordinados
    function get_user_subordinados($id)
    {
        $this->db->select('users.*,rol.rol,cargos.nombre_cargo');
        $this->db->join('rol','rol.id = users.rol_id');
        $this->db->join('cargos','cargos.id = users.cargo');
        $this->db->where('parent_id',$id);
        $this->db->order_by('rol', $this->order);
        return $this->db->get($this->table)->result();
    }
    function get_user_free($userId)//este metodo devuelve los usuarios que no aun no tinen jefe
    {
        $this->db->where('parent_id',0);
        $this->db->where('id <>',$userId);
        return $this->db->get($this->table)->result();
    }
    function has_superior($user_id)
    {
        $datos = array('id'=>$user_id,'parent_id'=>0);
        $this->db->where($datos);
        return $this->db->get($this->table)->result();
    }
    
    // get total rows
    function total_rows($q = NULL) {
        $this->db->select('users.*,rol.rol,cargos.nombre_cargo');
        $this->db->join('rol','rol.id = users.rol_id');
        $this->db->join('cargos','cargos.id = users.cargo');
        $this->db->or_like('name', $q);
        $this->db->or_like('email', $q);
        $this->db->or_like('cargo', $q);
        $this->db->or_like('password', $q);
        $this->db->or_like('rol', $q);
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    // get data with limit and search
    function get_limit_data($limit, $start = 0, $q = NULL) {
        $this->db->select('users.*,rol.rol,cargos.nombre_cargo');
        $this->db->join('rol','rol.id = users.rol_id');
        $this->db->join('cargos','cargos.id = users.cargo');
        $this->db->order_by('users.id', $this->order);
        $this->db->or_like('name', $q);
        $this->db->or_like('email', $q);
        $this->db->or_like('cargo', $q);
        $this->db->or_like('rol', $q);
        $this->db->limit($limit, $start);
        return $this->db->get($this->table)->result();
    }
    function valida_user($email)
    {
        $this->db->where('email',$email);
        $query = $this->db->get($this->table)->num_rows();
        $result = false;
        if($query > 0)
        {
            $result = true;
        }
        return $result;
    }

    // insert data
    function insert($data)
    {
       // $data['password'] = md5($data['password']);
        $this->db->insert($this->table, $data);
    }

    // update data
    function update($id, $data)
    {
        $this->db->where($this->id, $id);
       // $data['password'] = md5($data['password']);
        $this->db->update($this->table, $data);
    }
    function update_subordinado($id, $data)
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
    //informacion para mi
    /************/
    function info($data_info)
    {
        //table info
        $this->db->insert('info',$data_info);
    }
    function validaInfo($month,$year)
    {
        $result = false;
        $this->db->where('month',$month);
        $this->db->where('year',$year);
        $query = $this->db->get('info')->num_rows();
        if($query > 0)$result = true;
        return $result;

    }
    /****************/

}

/* End of file Users_model.php */
/* Location: ./application/models/Users_model.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-01 22:59:16 */
/* http://harviacode.com */