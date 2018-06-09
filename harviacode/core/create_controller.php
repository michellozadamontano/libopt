<?php

$string = "<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class " . $c . " extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        \$this->load->model('$m');
        \$this->load->library('form_validation');
    }";

$string .= "\n\n    public function get_all(){
return \$this->" . $m . "->get_all();        
}

function duplicar(\$id){
        \$prod = \$this->get_by_id(\$id);
        \$prod2 = \$prod;
        unset(\$prod2->id);

        foreach (\$prod2 as \$key=>\$item)
        {
            if(!\$this->".$m."->field_exists(\$key)){
                unset(\$prod2->\$key);
            }
        }
        \$this->" . $m . "->insert(\$prod2);
        redirect( site_url('$c_url'));
    }


public function get_by_id(\$id){
return \$this->" . $m . "->get_by_id(\$id);
}
        ";

if ($jenis_tabel == 'reguler_table') {

    $string .= "\n\n    public function index()
    {
        \$q = urldecode(\$this->input->get('q', TRUE));
        \$start = intval(\$this->input->get('start'));
        
        if (\$q <> '') {
            \$config['base_url'] = site_url('$c_url?q=' . urlencode(\$q));
            \$config['first_url'] = site_url('$c_url?q=' . urlencode(\$q));
        } else {
            \$config['base_url'] = site_url('$c_url');
            \$config['first_url'] = site_url('$c_url')  ;
        }

        \$config['per_page'] = 10;
        \$config['page_query_string'] = TRUE;
        \$config['total_rows'] = \$this->" . $m . "->total_rows(\$q);
        \$$c_url = \$this->" . $m . "->get_limit_data(\$config['per_page'], \$start, \$q);

        \$this->load->library('pagination');
        \$this->pagination->initialize(\$config);

        \$data = array(
            '" . $c_url . "_data' => \$$c_url,
            'q' => \$q,
            'pagination' => \$this->pagination->create_links(),
            'total_rows' => \$config['total_rows'],
            'start' => \$start,
        );

        \$data['menuactivo'] = '$c_url';
        \$data['title'] = '$c_url';
        \$data['vista'] = \$this->load->view('$v_list', \$data, true);
        echo Modules::run('admintemplate/one_col', \$data);
    }";
} else {

    $string .="\n\n    public function index()
    {
        \$$c_url = \$this->" . $m . "->get_all();

        \$data = array(
            '" . $c_url . "_data' => \$$c_url
        );

        
        \$data['menuactivo'] = '$c_url';
        \$data['title'] = '$c_url';
        \$data['vista'] = \$this->load->view('$v_list', \$data, true);
        echo Modules::run('admintemplate/one_col', \$data);
    }";
}

$string .= "\n\n    public function read(\$id) 
    {
        \$row = \$this->" . $m . "->get_by_id(\$id);
        if (\$row) {
            \$data = array(";
foreach ($all as $row) {
    $string .= "\n\t\t'" . $row['column_name'] . "' => \$row->" . $row['column_name'] . ",";
}
$string .= "\n\t    );
            


        \$data['menuactivo'] = '$c_url';
        \$data['title'] = '$c_url';
        \$data['vista'] = \$this->load->view('$v_read', \$data, true);
        echo Modules::run('admintemplate/one_col', \$data);


        } else {
            \$this->session->set_flashdata('message', 'Record Not Found');
            redirect(site_url('$c_url'));
        }
    }

    public function create() 
    {
        \$data = array(
            'button' => 'Adicionar',
            'action' => site_url('$c_url/create_action'),";
foreach ($all as $row) {
    $string .= "\n\t    '" . $row['column_name'] . "' => set_value('" . $row['column_name'] . "'),";
}
$string .= "\n\t);
        


        \$data['menuactivo'] = '$c_url';
        \$data['title'] = '$c_url';
        \$data['vista'] = \$this->load->view('$v_form', \$data, true);
        echo Modules::run('admintemplate/one_col', \$data);


    }
    
    public function create_action() 
    {
        \$this->_rules();

        if (\$this->form_validation->run() == FALSE) {
            \$this->create();
        } else {
            \$data = array(";
foreach ($non_pk as $row) {
    $string .= "\n\t\t'" . $row['column_name'] . "' => \$this->input->post('" . $row['column_name'] . "',TRUE),";
}
$string .= "\n\t    );

            \$inserid =  \$this->" . $m . "->insert(\$data);
            \$this->session->set_flashdata('message', 'Registro Insertado Correctamente');
            

            if (\$this->input->post('btnsubmit') == 'Guardar y Continuar'){
                redirect(site_url('$c_url/update/'.\$inserid));
            } else {
                redirect(site_url('$c_url'));
            }

                
        }
    }
    
    public function update(\$id) 
    {
        \$row = \$this->" . $m . "->get_by_id(\$id);

        if (\$row) {
            \$data = array(
                'button' => 'Editar',
                'action' => site_url('$c_url/update_action'),";
foreach ($all as $row) {
    $string .= "\n\t\t'" . $row['column_name'] . "' => set_value('" . $row['column_name'] . "', \$row->" . $row['column_name'] . "),";
}
$string .= "\n\t    );
            


        \$data['menuactivo'] = '$c_url';
        \$data['title'] = '$c_url';
        \$data['vista'] = \$this->load->view('$v_form', \$data, true);
        echo Modules::run('admintemplate/one_col', \$data);


        } else {
            \$this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('$c_url'));
        }
    }
    
    public function update_action() 
    {
        \$this->_rules();

        if (\$this->form_validation->run() == FALSE) {
            \$this->update(\$this->input->post('$pk', TRUE));
        } else {
            \$data = array(";
foreach ($non_pk as $row) {
    $string .= "\n\t\t'" . $row['column_name'] . "' => \$this->input->post('" . $row['column_name'] . "',TRUE),";
}
$string .= "\n\t    );

            \$this->" . $m . "->update(\$this->input->post('$pk', TRUE), \$data);
            \$this->session->set_flashdata('message', 'Registro Actualizado');
            

            if (\$this->input->post('btnsubmit') == 'Save and Continue'){
                redirect(site_url('$c_url/update/'.\$this->input->post('$pk', TRUE)));
            } else {
                redirect(site_url('$c_url'));
            }



        }
    }
    
    public function delete(\$id) 
    {
        \$row = \$this->" . $m . "->get_by_id(\$id);

        if (\$row) {
            \$resultado = \$this->" . $m . "->delete(\$id);
            if (\$resultado[\"code\"] != 0){
                \$this->session->set_flashdata(\"message\", \$resultado[\"code\"] .\": \" . \$resultado[\"message\"] );
            } else {
                \$this->session->set_flashdata('message', 'Registro Eliminado');
            }
            redirect(site_url('$c_url'));
        } else {
            \$this->session->set_flashdata('message', 'Registro no encontrado');
            redirect(site_url('$c_url'));
        }
    }

    public function _rules() 
    {";
foreach ($non_pk as $row) {
    $int = $row3['data_type'] == 'int' || $row['data_type'] == 'double' || $row['data_type'] == 'decimal' ? '|numeric' : '';
    $string .= "\n\t\$this->form_validation->set_rules('" . $row['column_name'] . "', '" . strtolower(label($row['column_name'])) . "', 'trim|required$int');";
}
$string .= "\n\n\t\$this->form_validation->set_rules('$pk', '$pk', 'trim');";
$string .= "\n\t\$this->form_validation->set_error_delimiters('<span class=\"text-danger\">', '</span>');
    }";

if ($export_excel == '1') {
    $string .= "\n\n    public function excel()
    {
        \$this->load->helper('exportexcel');
        \$namaFile = \"$table_name.xls\";
        \$judul = \"$table_name\";
        \$tablehead = 0;
        \$tablebody = 1;
        \$nourut = 1;
        //penulisan header
        header(\"Pragma: public\");
        header(\"Expires: 0\");
        header(\"Cache-Control: must-revalidate, post-check=0,pre-check=0\");
        header(\"Content-Type: application/force-download\");
        header(\"Content-Type: application/octet-stream\");
        header(\"Content-Type: application/download\");
        header(\"Content-Disposition: attachment;filename=\" . \$namaFile . \"\");
        header(\"Content-Transfer-Encoding: binary \");

        xlsBOF();

        \$kolomhead = 0;
        xlsWriteLabel(\$tablehead, \$kolomhead++, \"No\");";
    foreach ($non_pk as $row) {
        $column_name = label($row['column_name']);
        $string .= "\n\txlsWriteLabel(\$tablehead, \$kolomhead++, \"$column_name\");";
    }
    $string .= "\n\n\tforeach (\$this->" . $m . "->get_all() as \$data) {
            \$kolombody = 0;

            //ubah xlsWriteLabel menjadi xlsWriteNumber untuk kolom numeric
            xlsWriteNumber(\$tablebody, \$kolombody++, \$nourut);";
    foreach ($non_pk as $row) {
        $column_name = $row['column_name'];
        $xlsWrite = $row['data_type'] == 'int' || $row['data_type'] == 'double' || $row['data_type'] == 'decimal' ? 'xlsWriteNumber' : 'xlsWriteLabel';
        $string .= "\n\t    " . $xlsWrite . "(\$tablebody, \$kolombody++, \$data->$column_name);";
    }
    $string .= "\n\n\t    \$tablebody++;
            \$nourut++;
        }

        xlsEOF();
        exit();
    }";
}

if ($export_word == '1') {
    $string .= "\n\n    public function word()
    {
        header(\"Content-type: application/vnd.ms-word\");
        header(\"Content-Disposition: attachment;Filename=$table_name.doc\");

        \$data = array(
            '" . $table_name . "_data' => \$this->" . $m . "->get_all(),
            'start' => 0
        );
        
        \$this->load->view('" . $v_doc . "',\$data);
    }";
}

if ($export_pdf == '1') {
    $string .= "\n\n    function pdf()
    {
        \$data = array(
            '" . $table_name . "_data' => \$this->" . $m . "->get_all(),
            'start' => 0
        );
        
        ini_set('memory_limit', '32M');
        \$html = \$this->load->view('" . $v_pdf . "', \$data, true);
        \$this->load->library('pdf');
        \$pdf = \$this->pdf->load();
        \$pdf->WriteHTML(\$html);
        \$pdf->Output('" . $table_name . ".pdf', 'D'); 
    }";
}

$string .= "\n\n}\n\n/* End of file $c_file */
/* Location: ./application/controllers/$c_file */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator " . date('Y-m-d H:i:s') . " */
/* http://harviacode.com */";




$hasil_controller = createFile($string, $target . "controllers/" . $c_file);
?>