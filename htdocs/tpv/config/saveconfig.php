<?php
// Guardado configuración conexión base de datos
$file = '../../../apptpv/config/conndefault.ini';
$conn = parse_ini_file($file,true);

$file = '../../../apptpv/config/conn.ini';
// write
$data = array(
    'configured'  => 1, 
    'db'                    => $_GET['db'],
    'host'                  => $_GET['host'],
    'port'                  => $_GET['port'],
    'driver'                => $_GET['driver'],
    'user'                  => $_GET['user']
);
$data["{$_GET['user']}"] =  $_GET['password'];

write_ini_file($data, $file);

// Configuracion mail
$file = '../../../apptpv/config/mail.ini';
write_ini_file(array(
    'host'      => $_GET['host_mail'],
    'username'  => $_GET['username_mail'],
    'fromname'  => $_GET['fromname_mail'],
    'password'  => $_GET['password_mail'],
    'secure'    => $_GET['secure_mail'],
    'port'      => $_GET['port_mail'],
), $file);

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
