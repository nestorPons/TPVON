<?php
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
const BR = '</br>';