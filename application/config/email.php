<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['protocol']     = 'smtp';
$config['smtp_host']    = 'mail.mati.gov.ph';
$config['smtp_user']    = 'trabawho@mati.gov.ph';
$config['smtp_pass']    = 'moth34board';
$config['smtp_port']    = 465;
$config['smtp_crypto']  = 'ssl';

$config['charset']      = 'utf-8';
$config['mailtype']     = 'html';
$config['newline']      = "\r\n";
$config['crlf']         = "\r\n";
$config['useragent']    = 'CodeIgniter';

$config['smtp_timeout'] = 5;
$config['wordwrap']     = TRUE;
$config['validate']     = TRUE;
