<?php 
$less = new \app\models\Mylessc;
$less->setFormatter("compressed");

$less->setVariables(array(
    "rojo" => "red",
    "azul" => "blue",
    "base" => "960px"
  ));

$less->compileFolder(\FOLDERS\LESS, \FOLDERS\CSS);