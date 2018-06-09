<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class M_pdf {

    function __construct()
    {
        $this->CI = & get_instance();
        log_message('Debug', 'mPDF class is loaded.');
    }

    function load($param=NULL)
    {
        require_once APPPATH.'./third_party/mpdf/vendor/autoload.php';

        if ($param == NULL)
        {
            $param = '"en-GB-x","A4","","",10,10,10,10,6,3';
        }

        return new mPDF($param);
    }
}