<?php
const BR = '</br>';
function pr(...$args){
    foreach($args as $arr){
        echo('<pre>');
        var_dump($arr);
        echo('</pre>');
    }
}
function prs(...$args){
    foreach($args as $arr){
        echo('<pre>');
        var_dump($arr);
        echo('</pre>');
    }
    die();
}
function included($path, $data){
    include $path;
}