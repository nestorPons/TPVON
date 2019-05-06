<?php namespace CONFIG; 
if(isset($_GET['db'])) $_SESSION['db'] = $_GET['db'];
define('NAME_COMPANY', $_SESSION['db']??null);

define('ENV', TRUE); // Entorno de desarrollo 