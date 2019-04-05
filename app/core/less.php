<?php 
$less = new lessc;
$less->setFormatter("compressed");

$less->setVariables(array(
    "rojo" => "red",
    "base" => "960px"
  ));

$less->compileFile(\FOLDERS\LESS . "main.less", \FOLDERS\CSS . "main.min.css");