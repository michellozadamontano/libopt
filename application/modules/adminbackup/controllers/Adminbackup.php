<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminbackup extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $prefs = array(
            'format'        => 'gz',                       // gzip, zip, txt
            'add_drop'      => TRUE,                        // Whether to add DROP TABLE statements to backup file
            'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
            'newline'       => "\n"                         // Newline character used in backup file
        );

        $this->load->dbutil($prefs);
        $this->load->helper('file');
        $this->load->helper('download');
        $this->load->library('zip');
    }

    private function folder(){
        if (!file_exists($_SERVER["DOCUMENT_ROOT"]."/backups/") &&
            !is_dir($_SERVER["DOCUMENT_ROOT"]."/backups/")
        ) {
            mkdir($_SERVER["DOCUMENT_ROOT"]."/backups/");
        }
    }

    public function zipDirectory($path,$filename){
        $result = $this->zip->read_dir($path, FALSE);
        $this->zip->archive($_SERVER["DOCUMENT_ROOT"]."/backups/".$filename);
    }

    public function backup()
    {
        ini_set('memory_limit', '-1');
        $this->folder();
        $path = $_SERVER["DOCUMENT_ROOT"]."/backups/";
        $backup = $this->dbutil->backup();

        $date = date("Y-m-d_h:i:s");
        $file = $path.$date.'.gz';
        $res = write_file($file, $backup);

        if ($res){
            $result = exec("gzip -d ".$file);
            $file = substr($file,0,strlen($file)-3);
            $content = read_file($file);

            $content = 'SET FOREIGN_KEY_CHECKS=0;'.$content.'SET FOREIGN_KEY_CHECKS=1;';
            if(write_file($file, $content)){
                //compacto la carpeta imgs
                $this->session->set_flashdata('message', 'Backup creado satisfactoriamente');

                $this->zipDirectory($_SERVER["DOCUMENT_ROOT"]."/images/", "backupimgs".$date.".zip");

            }else{
                $this->session->set_flashdata('errormessage', 'No se pudo crear el backup');
            }

        } else {
            $this->session->set_flashdata('errormessage', 'No se pudo crear el backup');

        }
        redirect("adminbackup");
    }

    public function delete($file)
    {
        $this->folder();
        $path = $_SERVER["DOCUMENT_ROOT"]."/backups/";
        unlink($path.$file);
        unlink($path."backupimgs".$file.".zip");
        redirect("adminbackup");
    }

    public function download($file)
    {
        $path = $_SERVER["DOCUMENT_ROOT"]."/backups/";
        $this->zip->read_file($path.$file);
        $this->downloadZipDirectory($path."backupimgs".$file.".zip");
    }

    /** Descarga el fichero sql backup
     * @param $filename
     */
    public function downloadDbBackup($filename){
        $file = $_SERVER["DOCUMENT_ROOT"]."/backups/".$filename;

        if(file_exists($file)){
            force_download($file, NULL);
        }else{
            $this->session->set_flashdata('errormessage', 'Fichero inexistente');
            redirect("adminbackup");
        }
    }

    /** Descarga el compactado del directorio imgs del proyecto
     * @param $filename
     */
    public function downloadZipBackup($filename){
        $file = $_SERVER["DOCUMENT_ROOT"]."/backups/"."backupimgs".$filename.".zip";

        if(file_exists($file)){
            force_download($file, NULL);
        }else{
            $this->session->set_flashdata('errormessage', 'Fichero inexistente');
            redirect("adminbackup");
        }

    }

    /** Compacta una directorio especifico y permite descargarlo
     * @param $filename
     */
    public function downloadZipDirectory($filename){
        $this->zip->read_file($filename);
        $this->zip->download('my_backup.zip');
    }

    public function restore($file)
    {
        $this->restoreDb($file);
        $path = $_SERVER["DOCUMENT_ROOT"]."/backups/";
        $path1 = $_SERVER["DOCUMENT_ROOT"];

        $zip = new ZipArchive();
        if($zip->open($path."backupimgs".$file.".zip")){
            $zip->extractTo($path1);
            $zip->close();

            $this->session->set_flashdata('message', 'Backup restaurado satisfactoriamente');
        }else{
            $this->session->set_flashdata('errormessage', 'No se pudo restaurar el backup');
        }
        redirect("adminbackup");
    }

    public function restoreDb($file){
        $path = $_SERVER["DOCUMENT_ROOT"]."/backups/";

        $user = $this->db->username;
        $pass = $this->db->password;
        $db = $this->db->database;

        $command = "mysql -u ".$user." -p".$pass." ".$db ." --default-character-set=\"utf8\" < ".$path.$file;

        $result = exec($command);
        $this->session->set_flashdata('message', 'Backup restaurado satisfactoriamente');
        //redirect("adminbackup");
    }

    public function restoreDirectoryImgs($file){
        $pathBackups = $_SERVER["DOCUMENT_ROOT"]."/backups/";
        $pathImgs = $_SERVER["DOCUMENT_ROOT"]."/imgs/";

    }

    public function index(){
        $this->folder();
        $path = $_SERVER["DOCUMENT_ROOT"]."/backups/";
        $files = get_filenames($path);

        foreach($files as $itemFile){
            $info = pathinfo($path.$itemFile);
            if(!isset($info["extension"]) || $info["extension"] != "zip" ){
                $data["files"][] = $itemFile;
            }
        }

        if(isset($data["files"])){
            rsort($data["files"]);
        }


        $data['menuactivo'] = 'adminbackup';
        $data['title'] = 'adminbackup';
        $data['vista'] = $this->load->view('backups_list', $data, true);
        echo Modules::run('admintemplate/one_col', $data);
    }
}

/* End of file Admincategoriagasto.php */
/* Location: ./application/controllers/Admincategoriagasto.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-05-02 15:33:51 */
/* http://harviacode.com */