<?php
/**
 * Created by PhpStorm.
 * User: michellm
 * Date: 16/02/2017
 * Time: 14:07
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once('PHPMailer/PHPMailerAutoload.php');
class My_PHPMailer extends PHPMailer {
    public function __construct() {
        parent::__construct();
    }
}