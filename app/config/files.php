<?php namespace FILE; 

const CONN = \FOLDERS\CONFIG . 'conn.ini';
const MAIL = \FOLDERS\CONFIG . 'mail.php';
const TPV =  \FOLDERS\ADMIN_SECTIONS . 'tpv.phtml';
if(defined('CODE_COMPANY')) define(__NAMESPACE__ . '\CONFIG_COMPANY', \FOLDERS\COMPANIES . CODE_COMPANY .  '/config.ini');
