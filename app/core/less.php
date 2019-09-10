<?php 
$less = new \app\models\Mylessc;
$less->setFormatter("compressed");

// Carga de la configuración de cada empresa
$arr_conf = (file_exists(\FILE\CONFIG_COMPANY))
    ? array(parse_ini_file(\FILE\CONFIG_COMPANY))
    : array(parse_ini_file(\FILE\TEMPLATE_CONFIG));

if(defined('\FILE\CONFIG_COMPANY')) $less->setVariables($arr_conf[0]);
else $less->setVariables(array(parse_ini_file(\FILE\CONFIG_TEMPLATE))[0]);
    
$less->compileFolder(\FOLDERS\LESS, \PUBLICF\CSS);