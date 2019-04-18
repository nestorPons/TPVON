<?php
const BR = '</br>';
function pr($arr){
    echo('<pre>');
    print_r($arr);
    echo('</pre>');
}
function prs($arr){
    echo('<pre>');
    print_r($arr);
    echo('</pre>');
    die();
}
function loadUtils(string $nameUtil = '', array $data = []){
    include \FOLDERS\VIEWS . 'utils/' . $nameUtil . '.phtml';  
}

function randomid(string $prefix = ''){
    return  uniqid($prefix, true);
}