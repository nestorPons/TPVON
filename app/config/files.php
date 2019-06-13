<?php namespace FILE; 

const CONN = \FOLDERS\CONFIG . 'conn.ini';
const MAIL = \FOLDERS\CONFIG . 'mail.php';
const TPV =  \VIEWS\ADMIN\SECTIONS . 'tpv.phtml';
$config_company = defined('CODE_COMPANY') && CODE_COMPANY != 'newcompany' ? \PUBLICF\COMPANIES . CODE_COMPANY . '/': \PUBLICF\TEMPLATE;
define(__NAMESPACE__ . '\CONFIG_COMPANY', $config_company . 'config.ini');
