<?php 
$less = new lessc;
$less->setFormatter("compressed");

$less->setVariables(array(
    "rojo" => "red",
    "base" => "960px"
  ));
$files = $files = glob(\FOLDERS\LESS . '*.{less}', GLOB_BRACE);
foreach($files as $file) {
  $filename = explode('/', $file);
  end($filename);
  $filename = explode('.', pos($filename));
  reset($filename);
//AKI :: error al convertir less a css
  $less->checkedCompile(\FOLDERS\LESS . "main.less", \FOLDERS\CSS . $filename[0] . ".min.css");
}