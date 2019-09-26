<?php namespace FILE; 

const CONN              = \FOLDERS\CONFIG . 'conn.ini';
const MAIL              = \FOLDERS\CONFIG . 'mail.php';
const TPV               = \VIEWS\ADMIN\SECTIONS . 'tpv.phtml';
const CONFIG_TEMPLATE   = \PUBLICF\TEMPLATE . 'config.ini';
const CONFIG            = \PUBLICF\COMPANIES . 'config.ini';
const LOGO_TEMPLATE     = \PUBLICF\TEMPLATE . 'logo.png';
const LOGO              = \PUBLICF\COMPANIES . 'logo.png'; 

define(__NAMESPACE__ . '\CONFIG_COMPANY', \PUBLICF\COMPANIES . '/config.ini');
define(__NAMESPACE__ . '\TEMPLATE_CONFIG',  \PUBLICF\TEMPLATE . '/config.ini');
