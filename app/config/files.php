<?php namespace FILE; 

const CONN = \FOLDERS\CONFIG . 'conn.ini';
const MAIL = \FOLDERS\CONFIG . 'mail.php';
const TPV =  \VIEWS\ADMIN\SECTIONS . 'tpv.phtml';

define(__NAMESPACE__ . '\CONFIG_COMPANY', \PUBLICF\COMPANIES . CODE_COMPANY . '/config.ini');
define(__NAMESPACE__ . '\TEMPLATE_CONFIG',  \PUBLICF\TEMPLATE . '/config.ini');

