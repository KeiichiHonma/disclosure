<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Email
| -------------------------------------------------------------------------
| This file lets you define parameters for sending emails.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/libraries/email.html
|
*/

//$config['mailtype'] = 'text';
//$config['charset'] = 'utf-8';
//$config['newline'] = "\r\n";
//$config['wordwrap'] = FALSE;

$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['protocol'] = 'smtp';
$config['smtp_host'] = 'smtp.sendgrid.net';
$config['smtp_user'] = 'popapps';//your_sendgrid_username_here
$config['smtp_pass'] = 'k-honma2638';//your_sendgrid_password_here
$config['smtp_port'] = 587;
$config['crlf'] = "\r\n";
$config['newline'] = "\r\n";

/* End of file email.php */
/* Location: ./application/config/email.php */