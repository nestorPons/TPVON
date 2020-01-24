<?php

namespace app\controllers;

use\app\core\{Query, Data, Controller};

/**
 * Estilos visuales de la aplicación
 */
class Styles extends Controller
{
    private $file, $vars;
    const FILE = \FILE\CONFIG;

    function __construct($action, $data)
    {
        // Abrimos archivo donde se guardan la configuración de los estilos
        parent::__construct($action, null, $data);
    }
    protected function view($data = null)
    {
        $this->vars = parse_ini_file(self::FILE);
        parent::view($this->vars);
    }
    public function save($args)
    {
        $salida = '';
        foreach ($args as $clave => $valor) {
            $salida .= "$clave=$valor \n";
        }

        $puntero_archivo = fopen(self::FILE, 'w');

        if ($puntero_archivo !== false) {
            $escribo = fwrite($puntero_archivo, $salida);

            if ($escribo === false) {
                $devolver = -2;
            } else {
                $devolver = $escribo;
                $this->reload();
            }

            fclose($puntero_archivo);
        } else {
            $devolver = -1;
        }
        

        return $devolver;
    }
    // Se recargan los estilos
    private function reload(){
// AKI ::: recargar los estilos
// Volver a ejecutar less.php y recargar css con js
    }
}
