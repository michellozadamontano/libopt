<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminrol extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Mdl_adminrol');
        $this->load->library('form_validation');
    }

    function get_all() {
        return $this->Mdl_adminrol->get_all();
    }

    public function index() {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = site_url('adminrol?q=' . urlencode($q));
            $config['first_url'] = site_url('adminrol?q=' . urlencode($q));
        } else {
            $config['base_url'] = site_url('adminrol');
            $config['first_url'] = site_url('adminrol');
        }

        $config['per_page'] = 30;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Mdl_adminrol->total_rows($q);
        $adminrol = $this->Mdl_adminrol->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'adminrol_data' => $adminrol,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $data['menuactivo'] = 'adminrol';
        $data['title'] = 'adminrol';
        $data['vista'] = $this->load->view('rol_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
    public function rol_select()
    {
        $data['rol'] = $this->get_all();
        $this->load->view('rol_select',$data);
    }

    public function read($id) {
        $row = $this->Mdl_adminrol->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'rol' => $row->rol,
            );



            $data['menuactivo'] = 'adminrol';
            $data['title'] = 'adminrol';
            $data['vista'] = $this->load->view('rol_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('adminrol'));
        }
    }

    public function create() {
        $data = array(
            'button' => 'Create',
            'action' => site_url('adminrol/create_action'),
            'id' => set_value('id'),
            'rol' => set_value('rol'),
        );



        $data['menuactivo'] = 'adminrol';
        $data['title'] = 'adminrol';
        $data['vista'] = $this->load->view('rol_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function create_action() {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'rol' => $this->input->post('rol', TRUE),
            );

            $this->Mdl_adminrol->insert($data);
            $this->session->set_flashdata('message', 'Create Record Success');
            redirect(site_url('adminrol'));
        }
    }

    public function update($id) {
        $row = $this->Mdl_adminrol->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Update',
                'action' => site_url('adminrol/update_action'),
                'id' => set_value('id', $row->id),
                'rol' => set_value('rol', $row->rol),
            );



            $data['menuactivo'] = 'adminrol';
            $data['title'] = 'adminrol';
            $data['vista'] = $this->load->view('rol_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('adminrol'));
        }
    }

    public function update_action() {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'rol' => $this->input->post('rol', TRUE),
            );

            $this->Mdl_adminrol->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Update Record Success');
            redirect(site_url('adminrol'));
        }
    }

    public function delete($id) {
        $row = $this->Mdl_adminrol->get_by_id($id);

        if ($row) {
             $resultado = $this->Mdl_adminrol->delete($id);
            if($resultado['code']!= 0){
                $this->session->set_flashdata('message',$resultado['code'].': '.$resultado['message']);
            }
            else
            {
                $this->session->set_flashdata('message', 'Delete Record Success');
            }

            redirect(site_url('adminrol'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('adminrol'));
        }
    }
    public function about()
    {
        $user = $this->session->userdata('usuario');
        if ($user != null) {
            $data['controlador'] = 'users';
            $data['menuactivo'] = 'predefinidas';
            $data['title'] = 'Desarrollador';
            $data['vista'] = $this->load->view('_contact', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }
        else {
            redirect('auth/login', 'refresh');
        }
    }

    public function _rules() {
        $this->form_validation->set_rules('rol', 'rol', 'trim|required');

        $this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

    public function excel() {
        $this->load->helper('exportexcel');
        $namaFile = "rol.xls";
        $judul = "rol";
        $tablehead = 0;
        $tablebody = 1;
        $nourut = 1;
        //penulisan header
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0,pre-check=0");
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header("Content-Disposition: attachment;filename=" . $namaFile . "");
        header("Content-Transfer-Encoding: binary ");

        xlsBOF();

        $kolomhead = 0;
        xlsWriteLabel($tablehead, $kolomhead++, "No");
        xlsWriteLabel($tablehead, $kolomhead++, "Rol");

        foreach ($this->Mdl_adminrol->get_all() as $data) {
            $kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber($tablebody, $kolombody++, $nourut);
            xlsWriteLabel($tablebody, $kolombody++, $data->rol);

            $tablebody++;
            $nourut++;
        }

        xlsEOF();
        exit();
    }

    public function word() {
        header("Content-type: application/vnd.ms-word");
        header("Content-Disposition: attachment;Filename=rol.doc");

        $data = array(
            'adminrol_data' => $this->Mdl_adminrol->get_all(),
            'start' => 0
        );

        $this->load->view('rol_doc', $data);
    }

}

/* End of file Adminrol.php */
/* Location: ./application/controllers/Adminrol.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-03-10 21:01:55 */
/* http://harviacode.com */