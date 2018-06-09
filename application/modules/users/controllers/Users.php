<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model');
        $this->load->library('form_validation');
    }
    function encriptar($cadena){
        $key='michel';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encrypted; //Devuelve el string encriptado
       // return base64_encode($cadena);

    }

    function desencriptar($cadena){
        $key='michel';  // Una clave de codificacion, debe usarse la misma para encriptar y desencriptar
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $decrypted;  //Devuelve el string desencriptado
       // return base64_decode($cadena);
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = site_url('users?q=' . urlencode($q));
            $config['first_url'] = site_url('users?q=' . urlencode($q));
        } else {
            $config['base_url'] = site_url('users');
            $config['first_url'] = site_url('users');
        }

        $config['per_page'] = 30;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Users_model->total_rows($q);
        $users = $this->Users_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'users_data' => $users,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $data['menuactivo'] = 'users';
        $data['controlador'] = 'directivo';
        $data['title'] = 'users';
        $data['vista'] = $this->load->view('users_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function get_all(){
        return $this->Users_model->get_all();
    }

    public function user_select() //este metodo es para seleccionar los directivos
    {
        $user = $this->session->userdata('usuario');
        $data['users'] = $this->Users_model->get_directivos();
        $this->load->view('draw_dropdown', $data);
    }


    public function read($id)
    {
        $row = $this->Users_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'name' => $row->name,
                'email' => $row->email,
                'cargo' => $row->cargo,
                'login' => $row->login,
                'password' => $row->password,
                'last_login' => $row->last_login,
                'rol_id' => $row->rol_id,
            );


            $data['menuactivo'] = 'users';
            $data['title'] = 'users';
            $data['vista'] = $this->load->view('users_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('users'));
        }
    }

    public function valida_user($email)
    {
        return $this->Users_model->valida_user($email);
    }

    public function create()
    {
        $data = array(
            'button' => 'Crear',
            'action' => site_url('users/create_action'),
            'id' => set_value('id'),
            'name' => set_value('name'),
            'email' => set_value('email'),
            'cargo' => set_value('cargo'),
            'password' => set_value('password'),
            'rol_id' => set_value('rol_id'),
            'parent_id' => set_value('parent_id'),

        );
        $flash = $this->session->flashdata('error');
        if ($flash != "") {
            $data['error'] = $flash;
        }


        $data['menuactivo'] = 'users';
        $data['controlador'] = 'directivo';
        $data['title'] = 'users';
        $data['vista'] = $this->load->view('users_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'name' => $this->input->post('name', TRUE),
                'email' => $this->input->post('email', TRUE),
                'cargo' => $this->input->post('cargo', TRUE),
                'rol_id' => $this->input->post('rol_id', TRUE),
            );
            $checkRol = $this->comprobar_rol_id($this->input->post('rol_id', TRUE));
            if($checkRol!= null)
            {
                if($checkRol->rol_id == 5)
                {
                    $value = "Solo Puede existir un usuario con el rol Empresa";
                    $this->session->set_flashdata('error', $value);
                    $this->create();
                }
            }
            if ($this->valida_user($this->input->post('email', TRUE))) {
                $value = "Este email ya existe";
                $this->session->set_flashdata('error', $value);
                $this->create();
            } else {
                $data['parent_id'] = $this->input->post('parent_id', TRUE);
                if ($data['parent_id'] == "") {
                    $data['parent_id'] = 0;
                }
                $data['password'] = $this->encriptar($this->input->post('password', TRUE));
                $this->Users_model->insert($data);
                $this->session->set_flashdata('message', 'Usuario Creado');
                redirect(site_url('users'));
            }
        }
    }
    public function comprobar_rol_id($rol_id)
    {
        return $this->Users_model->get_by_rol_id($rol_id);
    }

    public function update($id)
    {
        $row = $this->Users_model->get_by_id($id);
        $pass = $this->desencriptar($row->password);
        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('users/update_action'),
                'id' => set_value('id', $row->id),
                'name' => set_value('name', $row->name),
                'email' => set_value('email', $row->email),
                'cargo' => set_value('cargo', $row->cargo),
                'password' => set_value('password', $this->desencriptar($row->password)),
                'rol_id' => set_value('rol_id', $row->rol_id),
                'parent_id' => set_value('parent_id', $row->parent_id),
            );

            $data['menuactivo'] = 'users';
            $data['controlador'] = 'directivo';
            $data['title'] = 'users';
            $data['vista'] = $this->load->view('users_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('users'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'name' => $this->input->post('name', TRUE),
                'email' => $this->input->post('email', TRUE),
                'cargo' => $this->input->post('cargo', TRUE),
                'password' => $this->encriptar($this->input->post('password', TRUE)),
                'rol_id' => $this->input->post('rol_id', TRUE),
            );
            $data['parent_id'] = $this->input->post('parent_id', TRUE);
            if ($data['parent_id'] == "") {
                $data['parent_id'] = 0;
            }

            $this->Users_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Usuario Actualizado');
            redirect(site_url('users'));
        }
    }

    public function delete($id)
    {
        $row = $this->Users_model->get_by_id($id);

        if ($row) {
            $this->Users_model->delete($id);
            $this->session->set_flashdata('message', 'Usuario Eliminado');
            redirect(site_url('users'));
        } else {
            $this->session->set_flashdata('message', 'Usuario no encontrado');
            redirect(site_url('users'));
        }
    }

    public function subordinados()
    {
        $user = $this->session->userdata('usuario');
        if($user!= null)
        {
            $data['users_data'] = $this->get_todos_los_subordinados($user->id);
            $data['menuactivo'] = 'subordinados';
            $data['title'] = 'subordinados';
            $data['controlador'] = 'directivo';
            $data['vista'] = $this->load->view('subordinado_list', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else{
            redirect('auth/login', 'refresh');
        }
    }

    public function create_subordinado()
    {
        $user = $this->session->userdata('usuario');
        $data['users'] = $this->Users_model->get_user_free($user->id);
        $data['action'] = site_url('users/create_subordinado_action');
        $data['menuactivo'] = 'subordinados';
        $data['controlador'] = 'directivo';
        $data['vista'] = $this->load->view('subordinado_create', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function create_subordinado_action()
    {
        $subordinados = $this->input->post('subordinado_check');
        if (count($subordinados) > 0) {
            $data['parent_id'] = $this->session->userdata('usuario')->id;
            foreach ($subordinados as $row) {
                $this->Users_model->update_subordinado($row, $data);
            }
        }
        redirect(site_url('users/subordinados'));
    }

    public function subordinado_delete($id)
    {
        $data['parent_id'] = 0;
        $this->Users_model->update_subordinado($id, $data);
        $this->session->set_flashdata('message', 'Usuario Actualizado');
        redirect(site_url('users/subordinados'));
    }

    public function subordinado_select($user_id)
    {
        $data['users'] = $this->get_todos_los_subordinados($user_id);
        $this->load->view('subordinado_select', $data);
    }
    public function subordinados_list($user_id)
    {
        $has_superior = Modules::run('users/has_superior',$user_id);
        if(count($has_superior)>0)
        {
            $data['jefe_supremo'] = $this->get_user($user_id);
        }
        //aqui voy a verificar que por si acaso el parent_id de este usuario no sea la empresa.
        $user = $this->session->userdata('usuario');
        $user_boss = Modules::run('users/get_user',$user->parent_id);
        if(($user_boss !=null)&&($user_boss->rol_id == 5))
            $data['jefe_supremo'] = $this->get_user($user_id);
        $data['users'] = $this->Users_model->get_user_subordinados($user_id);
        $this->load->view('subordinado_dropdown',$data);
    }
    public function get_jefe_subordinados($parent_id)
    {
        return $this->Users_model->get_user_subordinados($parent_id);
    }
    public function get_subordinados($user_id)
    {
        return $this->get_todos_los_subordinados($user_id);
    }
    public function get_user($user_id)
    {
        $query = $this->Users_model->get_by_id($user_id);
        return $query;
    }
    public function get_by_rol_id($rol_id)//obtengo el usuario segun el rol
    {
        $query = $this->Users_model->get_by_rol_id($rol_id);
        return $query;
    }
    public function has_superior($user_id)
    {
        return $this->Users_model->has_superior($user_id);
    }
    public function get_subor_directo($user_id,$parent_id)//obtengo el subordinado directo.
    {
        return $this->Users_model->get_subor_directo($user_id,$parent_id);
    }


    public function _rules()
    {
        $this->form_validation->set_rules('name', 'name', 'trim|required');
        $this->form_validation->set_rules('email', 'email', 'trim|required');
        $this->form_validation->set_rules('cargo', 'cargo', 'trim|required');
        $this->form_validation->set_rules('password', 'password', 'trim|required');
        $this->form_validation->set_rules('rol_id', 'Rol', 'trim|required');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    private function get_todos_los_subordinados($id)
    {
        $todos = $this->Users_model->get_user_subordinados($id);


        foreach ($todos as $t) {
            $subord = $this->get_todos_los_subordinados($t->id);
            if ($subord != null) {
                foreach ($subord as $s) {
                    $todos[] = $s;
                }
            }
        }

        return $todos;
    }
    public function get_jefes($user_id)
    {
        $user = $this->get_user($user_id);
        $parent = $this->get_user($user->parent_id);
        $todos = array();
        $i = 1;
        $todos[0] = $parent;
        while($parent->parent_id > 0)
        {
            $parent = $this->get_user($parent->parent_id);
            $todos[$i] = $parent;
            $i++;
        }
        return $todos;
    }

    public function insert_info($data)
    {
        $this->Users_model->info($data);
    }
    public function valida_info($month,$year)
    {
       return $this->Users_model->validaInfo($month,$year);
    }

}

/* End of file Users.php */
/* Location: ./application/controllers/Users.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-01 22:59:16 */
/* http://harviacode.com */