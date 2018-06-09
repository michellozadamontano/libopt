<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Aprobacionpt extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Aprobacionpt_model');
        $this->load->library('form_validation');
    }

    public function subordinados()
    {
        $user = $this->session->userdata('usuario');
        $date = getdate(time());
        $data['month'] = $date['mon'];
        $ano = $this->session->userdata('ano');
        $year = $date['year'];
        if ($ano != null) $year = $ano;
        $data['current_year'] = $date['year'];
        $data['last_year'] = $date['year'] - 1;

       // $aprobadopt = $this->validaAprobacion($user->id, $date['mon'], $year);
      //  if ($aprobadopt || $user->parent_id == 0) {
            $data['next_year'] = $date['year'] + 1;
            $data['users_id'] = $user->id;
            $data['month_list'] = array(
                '01' => 'Enero',
                '02' => 'Febrebro',
                '03' => 'Marzo',
                '04' => 'Abril',
                '05' => 'Mayo',
                '06' => 'Junio',
                '07' => 'Julio',
                '08' => 'Agosto',
                '09' => 'Septiembre',
                '10' => 'Octubre',
                '11' => 'Noviembre',
                '12' => 'Diciembre',
            );

            $data['menuactivo'] = 'aprobacionpt';
            $data['controlador'] = 'directivo';
            $data['vista'] = $this->load->view('aprobacionpt_user', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
      /*  } else {
            $this->session->set_flashdata('message', 'Su plan de trabajo aún no ha sido aprobado, por tanto no puede aprobar el de sus subordinados.');
            redirect('welcome', 'refresh');
        }*/

    }

    public function showptuser()
    {
        $this->form_validation->set_rules('users_id', 'usuario', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $this->subordinados();
        } else {
            $data['user_id'] = $this->input->post('users_id');
            $data['user'] = Modules::run('users/get_user', $this->input->post('users_id'));
            $data['mes'] = $this->input->post('month');
            $data['year'] = $this->input->post('year');
            $visited = Modules::run('pt/check_month_subor', $data['mes'], $data['user_id'], $data['year']);
            if (!$visited) {
                $this->session->set_flashdata('message', 'Este usuario no tiene PT para este mes');
                redirect('aprobacionpt/subordinados');
            }
            if ($this->validaAprobacion($data['user_id'], $data['mes'], $data['year'])) {
                $data['aproved'] = true;
            }
            $data['menuactivo'] = 'aprobacionpt';
            $data['controlador'] = 'directivo';
            $data['vista'] = $this->load->view('userplan', $data, true);
            echo Modules::run('admintemplate/one_col', $data);
        }


    }

    public function validaAprobacion($user_id, $month, $year)
    {
        $query = $this->Aprobacionpt_model->validaAprobacion($user_id, $month, $year);
        if (count($query) > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_by_id($id)
    {
        return $this->Aprobacionpt_model->get_by_id($id);
    }

    public function get_user_plan($user_id)
    {
        return $this->Aprobacionpt_model->get_user_plan($user_id);
    }

    public function index()
    {
        $q = urldecode($this->input->get('q', TRUE));
        $start = intval($this->input->get('start'));

        if ($q <> '') {
            $config['base_url'] = site_url('aprobacionpt?q=' . urlencode($q));
            $config['first_url'] = site_url('aprobacionpt?q=' . urlencode($q));
        } else {
            $config['base_url'] = site_url('aprobacionpt');
            $config['first_url'] = site_url('aprobacionpt');
        }

        $config['per_page'] = 30;
        $config['page_query_string'] = TRUE;
        $config['total_rows'] = $this->Aprobacionpt_model->total_rows($q);
        $aprobacionpt = $this->Aprobacionpt_model->get_limit_data($config['per_page'], $start, $q);

        $this->load->library('pagination');
        $this->pagination->initialize($config);

        $data = array(
            'aprobacionpt_data' => $aprobacionpt,
            'q' => $q,
            'pagination' => $this->pagination->create_links(),
            'total_rows' => $config['total_rows'],
            'start' => $start,
        );

        $data['menuactivo'] = 'aprobacionpt';
        $data['title'] = 'aprobacionpt';
        $data['vista'] = $this->load->view('aprobacionpt_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }

    public function read($id)
    {
        $row = $this->Aprobacionpt_model->get_by_id($id);
        if ($row) {
            $data = array(
                'id' => $row->id,
                'users_id' => $row->users_id,
                'fecha' => $row->fecha,
            );


            $data['menuactivo'] = 'aprobacionpt';
            $data['title'] = 'aprobacionpt';
            $data['vista'] = $this->load->view('aprobacionpt_read', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('aprobacionpt'));
        }
    }

    public function create()
    {
        $data = array(
            'button' => 'Create',
            'action' => site_url('aprobacionpt/create_action'),
            'id' => set_value('id'),
            'users_id' => set_value('users_id'),
            'fecha' => set_value('fecha'),
        );


        $data['menuactivo'] = 'aprobacionpt';
        $data['title'] = 'aprobacionpt';
        $data['vista'] = $this->load->view('aprobacionpt_form', $data, true);
        echo Modules::run('admintemplate/one_col', $data);


    }

    public function create_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->create();
        } else {
            $data = array(
                'users_id' => $this->input->post('users_id', TRUE),
                'month' => $this->input->post('mes', TRUE),
                'year' => $this->input->post('year', TRUE),
            );

            $this->Aprobacionpt_model->insert($data);
            $this->session->set_flashdata('message', 'Plan Aprobado Correctamente,no se podra hacer ningun cambio en el mismo');

            $user = Modules::run('users/get_user', $data['users_id']);
            $subordinados = Modules::run('users/get_jefe_subordinados', $user->id);

            if (count($subordinados) > 0) {
                foreach ($subordinados as $sub) {
                    $data_email['email'] = $sub->email;
                    $data_email['name'] = $user->name;
                    $data_email['mes'] = $data['month'];
                    $this->send_email_new_task_subor($data_email);
                }
            }
            redirect(site_url('aprobacionpt/subordinados'));
        }
    }

    public function send_email_new_task_subor($data)
    {
        //$from = Modules::run('adminsettings/email_from');
       // $from = $this->email->smtp_user;
       // $this->email->from($from,'Libopt');
       // $this->email->to($data['email']);
       // $this->email->subject('Crear PT');

        $message = '<p>El plan de trabajo de ' . $data['name'] .' del mes '.$data['mes']. ' fue aprobado, ya usted puede crear su plan de trabajo.</p>';
       // $this->email->message($message);
       // $this->email->send();
        Modules::run('adminsettings/send_mail',$data['email'],"Crear PT",$message);
    }
    public function notifica_revision()
    {
        $user = Modules::run('users/get_user',$this->input->post('user'));

       // $from = Modules::run('adminsettings/email_from');
       // $from = $this->email->smtp_user;
       // $this->email->from($from,'Libopt');
       // $this->email->to($user->email);
       // $this->email->subject('Revision de LiboPT');

        $message = "Su jefe dice: ".$this->input->post('text_email');
       // $this->email->message($message);
       // $this->email->send();
        Modules::run('adminsettings/send_mail',$user->email,"Crear PT",$message);
    }

    public function update($id)
    {
        $row = $this->Aprobacionpt_model->get_by_id($id);

        if ($row) {
            $data = array(
                'button' => 'Actualizar',
                'action' => site_url('aprobacionpt/update_action'),
                'id' => set_value('id', $row->id),
                'users_id' => set_value('users_id', $row->users_id),
                'fecha' => set_value('fecha', $row->fecha),
            );


            $data['menuactivo'] = 'aprobacionpt';
            $data['title'] = 'aprobacionpt';
            $data['vista'] = $this->load->view('aprobacionpt_form', $data, true);
            echo Modules::run('admintemplate/one_col', $data);


        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('aprobacionpt'));
        }
    }

    public function update_action()
    {
        $this->_rules();

        if ($this->form_validation->run() == FALSE) {
            $this->update($this->input->post('id', TRUE));
        } else {
            $data = array(
                'users_id' => $this->input->post('users_id', TRUE),
                'fecha' => $this->input->post('fecha', TRUE),
            );

            $this->Aprobacionpt_model->update($this->input->post('id', TRUE), $data);
            $this->session->set_flashdata('message', 'Actualizado conrrectamente');
            redirect(site_url('aprobacionpt'));
        }
    }

    public function delete($id)
    {
        $row = $this->Aprobacionpt_model->get_by_id($id);

        if ($row) {
            $this->Aprobacionpt_model->delete($id);
            $this->session->set_flashdata('message', 'Delete Record Success');
            redirect(site_url('aprobacionpt'));
        } else {
            $this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('aprobacionpt'));
        }
    }

    public function _rules()
    {
        $this->form_validation->set_rules('users_id', 'Usuario', 'trim|required');
        //$this->form_validation->set_rules('fecha', 'fecha', 'trim|required');

        //$this->form_validation->set_rules('id', 'id', 'trim');
        $this->form_validation->set_error_delimiters('<span class="text-danger">', '</span>');
    }

}

/* End of file Aprobacionpt.php */
/* Location: ./application/controllers/Aprobacionpt.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-06-26 12:04:48 */
/* http://harviacode.com */