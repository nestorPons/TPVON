<?php
const BR = '</br>';
function pr($arr){
    echo('<pre>');
    var_dump($arr);
    echo('</pre>');
}
function prs($arr){
    echo('<pre>');
    var_dump($arr);
    echo('</pre>');
    die();
}
function component(string $nameUtil = '', array $data = []){
    include \FOLDERS\COMPONENTS . $nameUtil . '.phtml';  
}

function randomid(string $prefix = ''){
    return  uniqid($prefix, true);
}