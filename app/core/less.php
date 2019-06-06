<?php 
$less = new \app\models\Mylessc;
$less->setFormatter("compressed");

// Carga de la configuración de cada empresa
if(defined('\FILE\CONFIG_COMPANY')) $less->setVariables(array(parse_ini_file(\FILE\CONFIG_COMPANY))[0]);
else $less->setVariables(array(parse_ini_file(\FILE\CONFIG_TEMPLATE))[0]);
    
$less->compileFolder(\FOLDERS\LESS, \FPUBLIC\CSS);