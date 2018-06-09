<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth extends MX_Controller
{

    function __construct()
    {
        parent::__construct();
    }

    public function admin()
    {
        $data["headpage"] = Modules::run("admintemplate/getHeader");
        $data["javascripts"] = Modules::run("admintemplate/getJavascripts");
        echo $data['vista'] = $this->load->view('auth/login_admin', $data, true);
    }

    public function index()
    {
        $this->login();
    }

    function encriptar($cadena)
    {
        $key = 'michel';
        $encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $cadena, MCRYPT_MODE_CBC, md5(md5($key))));
        return $encrypted; //Devuelve el string encriptado
    }

    function desencriptar($cadena)
    {
        $key = 'michel';
        $decrypted = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($cadena), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
        return $decrypted;  //Devuelve el string desencriptado
    }

    public function create()
    {

        $data['controlador'] = 'auth';
        $data['metodo'] = 'create';
        $data['title'] = 'adminauth';
        $data['menuactivo'] = 'auth_change_password';
        $form = array(
            'button' => 'Crear',
            'action' => site_url('auth/create_action'),
            'id' => set_value('id'),
            'nombre' => set_value('nombre'),
            'apellidos' => set_value('apellidos'),
            'telefono' => set_value('telefono'),
            'correo' => set_value('correo'),
            'password' => set_value('password'),
            'confirmpassword' => set_value('confirmpassword'),
            'rol_id' => set_value('rol_id'),
        );

        $data = array_merge($data, $form);

        $data['menuactivo'] = 'auth_create';
        $data['title'] = 'auth_create';
        $data['vista'] = $this->load->view('auth/create', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {


            $data = array(
                'nombre' => $this->input->post('nombre', TRUE),
                'apellidos' => $this->input->post('apellidos', TRUE),
                'telefono' => $this->input->post('telefono', TRUE),
                'correo' => $this->input->post('correo', TRUE),
                'password' => $this->encriptar($this->input->post('password', TRUE)),
                'rol_id' => $this->input->post('rol_id', TRUE),
                // 'confirmpassword' => $this->input->post('confirmpassword', TRUE),
            );


            $this->_insert($data);

            $datauser = $this->get_where_custom('correo', $this->input->post('correo', TRUE))->row();
            $this->savedata($datauser);
            $this->session->set_flashdata('message', 'Registrado y autenticado correctamente');
            redirect(site_url('adminusuarios'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('apellidos', 'apellidos', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');
        $this->form_validation->set_rules('correo', 'correo', 'trim|required|valid_email|is_unique[usuarios.correo]');
        $this->form_validation->set_rules('password', 'password', 'trim|required');
        $this->form_validation->set_rules('confirmpassword', 'confirm password', 'trim|required|matches[password]');
        $this->form_validation->set_rules('rol_id', 'rol id', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function perfil()
    {

        $usuario = $this->session->userdata('usuario');
        $row = $this->get_where($usuario->id)->row();
        if ($row) {

            $data['controlador'] = 'auth';
            $data['metodo'] = 'perfil';


            $data['title'] = 'adminauth';


            $data['menuactivo'] = 'auth_change_password';

            $form = array(
                'button' => 'Perfil',
                'action' => site_url('auth/perfil_action'),
                'id' => set_value('id', $row->id),
                'nombre' => set_value('nombre', $row->nombre),
                'apellidos' => set_value('apellidos', $row->apellidos),
                'telefono' => set_value('telefono', $row->telefono),
                'correo' => set_value('correo', $row->correo),
                'password' => set_value('password', $row->password),
                'rol_id' => set_value('rol_id', $row->rol_id),
            );
            $data = array_merge($data, $form);

            $data['menuactivo'] = 'auth_perfil';
            $data['title'] = 'auth_perfil';
            $data['vista'] = $this->load->view('auth/perfil_update', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('adminusuarios'));
        }
    }

    public function perfil_action()
    {

        $this->_rules_perfil();
        if ($this->form_validation->run($this) == FALSE) {
            $this->perfil();
        } else {
            $data = array(
                'nombre' => $this->input->post('nombre', TRUE),
                'apellidos' => $this->input->post('apellidos', TRUE),
                'telefono' => $this->input->post('telefono', TRUE),
                'correo' => $this->input->post('correo', TRUE),
                //'password' => $this->input->post('password', TRUE),
                //'rol_id' => $this->input->post('rol_id', TRUE),
            );
            $this->load->model('mdl_auth');
            $usuario = $this->session->userdata('usuario');
            $this->mdl_auth->update($usuario->id, $data);
            $this->session->set_flashdata('message', 'Actualizado correctamente');
            $usuarionew = $this->get_where($usuario->id)->row();
            $this->savedata($usuarionew);
            redirect(site_url('adminusuarios'));
        }
    }

    public function _rules_perfil()
    {
        $this->form_validation->set_rules('nombre', 'nombre', 'trim|required');
        $this->form_validation->set_rules('apellidos', 'apellidos', 'trim|required');
        $this->form_validation->set_rules('telefono', 'telefono', 'trim|required');
        $this->form_validation->set_rules('correo', 'correo', 'trim|required|valid_email');
        //    $this->form_validation->set_rules('password', 'password', 'trim|required');
        //   $this->form_validation->set_rules('rol_id', 'rol id', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function change_password()
    {
        $usuario = $this->session->userdata('usuario');
        $row = $this->get_where($usuario->id)->row();
        if ($row) {

            $data['controlador'] = 'users';
            $data['metodo'] = 'change_password';

            $data['title'] = 'adminauth';
            $data['menuactivo'] = 'auth_change_password';

            $form = array(
                'button' => 'Cambiar contraseña',
                'action' => site_url('auth/change_password_action'),
                'id' => set_value('id', $row->id),
            );
            $data = array_merge($data, $form);

            $data['menuactivo'] = 'auth_change_password';
            $data['title'] = 'auth_change_password';
            $data['vista'] = $this->load->view('auth/change_password', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            $this->session->set_flashdata('message', 'No encontrado');
            redirect(site_url('auth/login'));
        }
    }

    public function change_password_action()
    {
        $this->_rules_password();
        if ($this->form_validation->run($this) == FALSE) {
            $this->change_password();
        } else {
            $data = array(
                'password' => $this->encriptar($this->input->post('newpassword', TRUE)),
                //  'oldpassword' => $this->input->post('oldpassword', TRUE),
                //  'confirmpassword' => $this->input->post('confirmpassword', TRUE),
            );
            $this->load->model('mdl_auth');
            $usuario = $this->session->userdata('usuario');
            $this->mdl_auth->password($usuario->id, $data);
            $usuarionew = $this->get_where($usuario->id)->row();
            $this->savedata($usuarionew);

            $this->session->set_flashdata('message', 'Password Cambiado');
            redirect(site_url('welcome'));
        }
    }

    public function clavesiguales($oldpassword)
    {
        $id = $this->input->post('id');
        $usuario = $this->session->userdata('usuario');
        $oldpassword = $this->encriptar($oldpassword); //md5($oldpassword);
        if ($oldpassword != $usuario->password) {
            $this->form_validation->set_message('clavesiguales', 'La contrasena antigua no coincide.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function _rules_password()
    {

        $this->form_validation->set_rules('oldpassword', 'Old Password', 'required|callback_clavesiguales');
        $this->form_validation->set_rules('confirmpassword', 'Confirm Password', 'required|matches[newpassword]');
        $this->form_validation->set_rules('newpassword', 'New Password', 'required');
        $this->form_validation->set_message('clavesiguales', 'La contrasena antigua no coincide.');
        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function login()
    {

        if (!$this->system()) {
            $data['cadena'] = $cadena = $_SERVER['SERVER_NAME'] . $_SERVER['SERVER_SOFTWARE'];
            // $this->load->view('security',$data);
            $data['vista'] = $this->load->view('auth/security', $data, true);
            echo Modules::run('admintemplate/security', $data);
        } else {
            $data['controlador'] = 'auth';
            $data['metodo'] = 'login';

            $data['title'] = 'adminauth';

            $data['menuactivo'] = 'auth_login';
            $form = array(
                'button' => 'Autenticar',
                'action' => site_url('auth/login_action'),
                'correo' => set_value('correo'),
                'password' => set_value('password'),
            );
            $data = array_merge($data, $form);

//        $data["headpage"] = Modules::run("admintemplate/getHeader");
//        $data["javascripts"] = Modules::run("admintemplate/getJavascripts");
//        $this->load->view('auth/login_admin', $data);

            $data['vista'] = $this->load->view('auth/login', $data, true);
            echo Modules::run('admintemplate/solologin', $data);
        }

    }

    public function system()
    {
        $result = false;
        $cadena = $_SERVER['SERVER_NAME'] . $_SERVER['SERVER_SOFTWARE'];
        $this->load->model('mdl_auth');
        $codigo = $this->mdl_auth->get_cod();
        $crypto = $this->encriptar($cadena);
        if ($codigo != null) {
            if ($crypto == $codigo->cod) {
                $result = true;
            }
        }
        return true;
        return $result;
    }

    public function insertcod()
    {
        $cadena = $_SERVER['SERVER_NAME'] . $_SERVER['SERVER_SOFTWARE'];
        $data['cod'] = $this->input->post('codigo');
        $this->load->model('Mdl_auth');
        $crypto = $this->encriptar($cadena);
        if ($crypto == $data['cod']) {
            $code = $this->Mdl_auth->get_cod();
            if ($code != null) {
                $this->Mdl_auth->updateCod($code->id, $data);
                $this->session->set_flashdata('message', 'Clave registrada exitosamente');
                $this->login();
            } else {
                $this->Mdl_auth->insertcod($data);
                $this->session->set_flashdata('message', 'Clave registrada exitosamente');
                $this->login();
            }

        } else {
            $this->session->set_flashdata('message', 'Codigo No valido');
            $this->login();
        }
    }

    public function login_action()
    {
        $this->_rules_login();

        if ($this->form_validation->run($this) == FALSE) {
            //$error = validation_errors();
            $this->login();
        } else {
            $datauser = $this->get_where_custom('email', $this->input->post('correo', TRUE))->row();
            $this->savedata($datauser);
            $this->session->set_flashdata('message', 'Autenticado correctamente');
            redirect(site_url('welcome'), 'refresh');
        }
    }

    public function isadmin()
    {
        $usuario = $this->session->userdata('usuario');
        if ($usuario->rol_id == 1) {
            return true;
        } else {
            return false;
        }
    }
    public function isempresa()
    {
        $usuario = $this->session->userdata('usuario');
        if ($usuario->rol_id == 5) {
            return true;
        } else {
            return false;
        }
    }

    public function isoperador()
    {
        $user = $this->session->userdata('usuario');
        if ($user->rol_id == 2) {
            return true;
        } else {
            return false;
        }
    }

    public function isdirectivo()
    {
        $user = $this->session->userdata('usuario');
        if (($user->rol_id == 3)||($user->rol_id == 5)) {
            return true;
        } else {
            return false;
        }
    }


    public function islogged()
    {
        $usuario = $this->session->userdata('usuario');
        if ($usuario == NULL) {
            return false;
        } else {
            return $usuario;
        }
    }

    public function logout()
    {
        $this->unsavedata();
        redirect(site_url('auth/login'));
    }

    private function unsavedata()
    {
        $this->session->unset_userdata('usuario');
        $this->session->unset_userdata('ano');
        $this->session->unset_userdata('mes');
    }

    private function savedata($datauser)
    {
        $this->session->set_userdata('usuario', $datauser);
    }

    public function check_password($password)
    {
        $correo = $this->input->post('correo', TRUE);
        $password = $this->encriptar($password);//md5($password);
        $this->load->model('mdl_auth');
        $result = $this->mdl_auth->get_by_correo_password($correo, $password)->row();
        $this->session->set_userdata('result', $correo);
        if (!$result) {
            $this->form_validation->set_message('correo_password', 'El correo o la contrasena son incorrectos.');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function _rules_login()
    {
        $this->form_validation->set_rules('password', 'Password', 'required|callback_check_password');
        $this->form_validation->set_rules('correo', 'Correo', 'required|valid_email');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    function get($order_by)
    {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get($order_by);
        return $query;
    }

    function get_with_limit($limit, $offset, $order_by)
    {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_with_limit($limit, $offset, $order_by);
        return $query;
    }

    function get_where($id)
    {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_where($id);
        return $query;
    }

    function get_where_custom($col, $value)
    {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->get_where_custom($col, $value);
        return $query;
    }

    function _insert($data)
    {
        $this->load->model('mdl_auth');
        $this->mdl_auth->_insert($data);
    }

    function _update($id, $data)
    {
        $this->load->model('mdl_auth');
        $this->mdl_auth->_update($id, $data);
    }

    function _delete($id)
    {
        $this->load->model('mdl_auth');
        $this->mdl_auth->_delete($id);
    }

    function count_where($column, $value)
    {
        $this->load->model('mdl_auth');
        $count = $this->mdl_auth->count_where($column, $value);
        return $count;
    }

    function get_max()
    {
        $this->load->model('mdl_auth');
        $max_id = $this->mdl_auth->get_max();
        return $max_id;
    }

    function _custom_query($mysql_query)
    {
        $this->load->model('mdl_auth');
        $query = $this->mdl_auth->_custom_query($mysql_query);
        return $query;
    }

}
