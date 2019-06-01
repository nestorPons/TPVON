<?php namespace FILE; 

const CONN = \FOLDERS\CONFIG . 'conn.ini';
const MAIL = \FOLDERS\CONFIG . 'mail.php';
const TPV =  \FOLDERS\ADMIN_SECTIONS . 'tpv.phtml';
$config_company = defined('CODE_COMPANY') && CODE_COMPANY != 'newcompany' ? \FOLDERS\COMPANIES . CODE_COMPANY . '/': \FOLDERS\TEMPLATE;
define(__NAMESPACE__ . '\CONFIG_COMPANY', $config_company . 'config.ini');
