<?php
$file = '../../../apptpv/config/conncopy.ini';
$conn = parse_ini_file($file,true);

$file = '../../../apptpv/config/conn.ini';
// write
$data = array(
    'configured'            => true, 
    'db'                    => $_GET['db'],
    'host'                  => $_GET['host'],
    'port'                  => $_GET['port'],
    'driver'                => $_GET['driver'],
    'current_user'          => $_GET['current_user'],
);
$data["{$_GET['current_user']}"] =  $_GET['password'];

write_ini_file($data, $file);

 exit("Finalizado el registro /n vuelva a recargar la pagina <a href='/tpv/'>Aqui</a>");
/**
 * write ini file
 * @param $assoc_arr
 * @param $path
 * @return bool
 */
function write_ini_file($assoc_arr, $path)
{
    $content = "";
    foreach ($assoc_arr as $key => $elem) {
        $content .= "[" . $key . "]\n";
        if(is_array($elem)){
            foreach ($elem as $key2 => $elem2) {
                if (is_array($elem2)) {
                    for ($i = 0; $i < count($elem2); $i++) {
                        $content .= $key2 . "[] = \"" . $elem2[$i] . "\"\n";
                    }
                } else if ($elem2 == "") {
                    $content .= $key2 . " = \n";
                } else {
                    $content .= $key2 . " = \"" . $elem2 . "\"\n";
                }
            }
        } else {
            $content .= $key . '=' . $elem . "\n"; 
        }
    }
    if (!$handle = fopen($path, 'w')) {
        return false;
    }
    if (!fwrite($handle, $content)) {
        return false;
    }
    fclose($handle);
    return true;
}
