<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminsettings extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->library("my_phpmailer");

    }
    public function convertDateToMsSQL($date)
    {
      //  if($this->validateDateEs($date))
     //   {
            $values=preg_split('/(\/|-)/',$date);
            $values[0]=(strlen($values[0])==2?$values[0]:"0".$values[0]);
            $values[1]=(strlen($values[1])==2?$values[1]:"0".$values[1]);
            $values[2]=(strlen($values[2])==4?$values[2]:substr(date("Y"),0,2).$values[4]);
            return $values[2].$values[1].$values[0];
    //    }
    //    return "";
    }
    function fechaesp($date) {//convierte la fecha español que viene con este formato Y-m-d
        $dia = explode("-", $date, 3);
        $year = $dia[0];
        $month = (string)(int)$dia[1];
        $day = (string)(int)$dia[2];

        $dias = array("Domingo","Lunes","Martes","Mi&eacute;rcoles" ,"Jueves","Viernes","S&aacute;bado");
        $tomadia = $dias[intval((date("w",mktime(0,0,0,$month,$day,$year))))];

        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

      //  return $tomadia.", ".$day." de ".$meses[$month]." de ".$year;
        return $tomadia.", ".$day." de ".$meses[$month];
    }
    function fecha_esp($date) {//convierte la fecha español que viene con este formato d-m-Y
        $dia = explode("-", $date, 3);
        $year = $dia[2];
        $month = (string)(int)$dia[1];
        $day = (string)(int)$dia[0];

        $dias = array("Domingo","Lunes","Martes","Mi&eacute;rcoles" ,"Jueves","Viernes","S&aacute;bado");
        $tomadia = $dias[intval((date("w",mktime(0,0,0,$month,$day,$year))))];

        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        //  return $tomadia.", ".$day." de ".$meses[$month]." de ".$year;
        return $tomadia.", ".$day." de ".$meses[$month];
    }
    function fecha_vacaciones($date) {//convierte la fecha español que viene con este formato Y-m-d
        $dia = explode("-", $date, 3);
        $year = $dia[0];
        $month = (string)(int)$dia[1];
        $day = (string)(int)$dia[2];

        $dias = array("Domingo","Lunes","Martes","Mi&eacute;rcoles" ,"Jueves","Viernes","S&aacute;bado");
        $tomadia = $dias[intval((date("w",mktime(0,0,0,$month,$day,$year))))];

        $meses = array("", "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

          return $tomadia.", ".$day." de ".$meses[$month]." de ".$year;

    }
    function fecha_formato_esp($date) {//convierte la fecha al formato d/m/Y que viene con este formato Y-m-d
        $dia = explode("-", $date, 3);
        $year = $dia[0];
        $month = (string)(int)$dia[1];
        $day = (string)(int)$dia[2];


        if($dia[2] < 10)$day = '0'.$day;
        if($dia[1] < 10)$month = '0'.$month;

        return $day."/".$month."/".$year;

    }
    function fecha_americana($date) {//convierte la fecha al formato Y-m-d que viene con este formato d-m-Y
        $dia = explode("-", $date, 3);
        $year = $dia[2];
        $month = (string)(int)$dia[1];
        $day = (string)(int)$dia[0];

        if($dia[0] < 10)$day = '0'.$day;
        if($dia[1] < 10)$month = '0'.$month;

        return $year."-".$month."-".$day;

    }

    function operacion_fecha($fecha, $dias) //sumar y restar dias.
    {//para restar y sumar dias
        list ($dia, $mes, $ano) = explode("-", $fecha);
        if (!checkdate($mes, $dia, $ano)) {
            return false;
        }
        $dia = $dia + $dias;
        $fecha = date("d-m-Y", mktime(0, 0, 0, $mes, $dia, $ano));
        return $fecha;
    }
    function translate_month($mes)
    {
        $month = $mes;
        if($mes == 'January')$month = 'Enero';
        if($mes == 'February')$month = 'Febrero';
        if($mes == 'March')$month = 'Marzo';
        if($mes == 'April')$month = 'Abril';
        if($mes == 'May')$month = 'Mayo';
        if($mes == 'June')$month = 'Junio';
        if($mes == 'July')$month = 'Julio';
        if($mes == 'August')$month = 'Agosto';
        if($mes == 'September')$month = 'Septiembre';
        if($mes == 'October')$month = 'Octubre';
        if($mes == 'November')$month = 'Noviembre';
        if($mes == 'December')$month = 'Diciembre';
        return $month;
    }
    function translate_month_number($mes)
    {
        $month = "";
        if($mes == 1)$month = 'Enero';
        if($mes == 2)$month = 'Febrero';
        if($mes == 3)$month = 'Marzo';
        if($mes == 4)$month = 'Abril';
        if($mes == 5)$month = 'Mayo';
        if($mes == 6)$month = 'Junio';
        if($mes == 7)$month = 'Julio';
        if($mes == 8)$month = 'Agosto';
        if($mes == 9)$month = 'Septiembre';
        if($mes == 10)$month = 'Octubre';
        if($mes == 11)$month = 'Noviembre';
        if($mes == 12)$month = 'Diciembre';
        return $month;
    }

    /**
     * Funcion para validar una fecha en formato dd/mm/yyyy
     */
    public function validateDateEs($date)
    {
        $pattern="/^(0?[1-9]|[12][0-9]|[3][01])[\/|-](0?[1-9]|[1][12])[\/|-]((19|20)?[0-9]{2})$/";
        if(preg_match($pattern,$date))
            return true;
        return false;
    }
    function date_diff($start_date,$end_date,$format = 'd')
    {
        $start_date = strtotime($start_date);
        $end_date = strtotime($end_date);

        switch ($format)
        {
            //seconds
            case "s":
                return floor(($end_date-$start_date));
            //minutes
            case "i":
                return floor(($end_date-$start_date)/60);
            //hours
            case "h":
                return floor(($end_date-$start_date)/3600);
            //days
            case "d":
                return floor(($end_date-$start_date)/86400);
            //months
            case "m":
                return floor(($end_date-$start_date)/2628000);
            //years
            case "y":
                return floor(($end_date-$start_date)/31536000);
            //days
            default:
                return floor(($end_date-$start_date)/86400);
        }
    }

    function email_from()
    {
        $email  = 'michellm@get.mrn.tur.cu';
        return $email;
    }
    function send_mail($email, $subject, $body)
    {
        $mail = new My_PHPMailer();
        $mail->isSMTP();
        $mail->SMTPDebug = 0;
        $mail->Debugoutput = 'html';
        $mail->Host = "exch2016.get.cu";
        $mail->Port = 465;
        $mail->SMTPAuth = true;
        $mail->Username = "zunpt";
        $mail->Password = "Workplan2017";
        $mail->setFrom("zunpt@get.tur.cu","LibotPT");
        $mail->addAddress($email);
        $mail->Subject = $subject;
        $mail->Body = $body;
        if (!$mail->send()) {
            print_r("Mailer Error: " . $mail->ErrorInfo);
        }
    }

    

}

/* End of file Admincontacto.php */
/* Location: ./application/controllers/Admincontacto.php */
/* Please DO NOT modify this information : */
/* Generated by Harviacode Codeigniter CRUD Generator 2016-03-10 20:35:29 */
/* http://harviacode.com */