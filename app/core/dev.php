<?php
const BR = '</br>';
function pr(...$args){
    foreach($args as $arr){
        echo('<pre>');
        print_r($arr);
        echo('</pre>');
    }
}
function prs(...$args){
    foreach($args as $arr){
        echo('<pre>');
        print_r($arr);
        echo('</pre>');
    }
    die();
}
function included($path, $data){
    include $path;
}