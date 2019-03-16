<?php 
$path = explode('/', $_SERVER['DOCUMENT_ROOT'] ); 
define('FOLDER_ROOT' , str_replace(array_pop($path),'',$_SERVER['DOCUMENT_ROOT']) );

const FOLDER_APP =  FOLDER_ROOT . 'app/';
const FOLDER_CORE =  FOLDER_APP . 'core/';
const FOLDER_MODELS = FOLDER_APP . 'models/';
const FOLDER_CONFIG = FOLDER_APP . 'config/';
const FOLDER_PUBLIC = FOLDER_ROOT . 'htdocs/';
const FOLDER_CSS = FOLDER_PUBLIC . 'css/';
const FOLDER_JS = FOLDER_PUBLIC . 'js/';
const FOLDER_BUILD = FOLDER_PUBLIC . 'build/';
 
