<?php

namespace app\controllers;

use\app\core\{Query, Data, Controller};

/**
 * Estilos visuales de la aplicación
 */
class Styles extends Controller
{
    private $file, $vars;
    const 
        FILE = \FILE\CONFIG,
        AMOUNT_FONTS = 'all';

    function __construct($action, $data)
    {
        // Abrimos archivo donde se guardan la configuración de los estilos
        parent::__construct($action, null, $data);
    }
    protected function view($data = null)
    {
        $name_fonts = []; 
        // Buscamos fuentes en google
        $fonts = file(\FILE\FONTS_GOOGLE); 

        // Extraemos el valor que nos interesa
        foreach($fonts as $k => $v){
            $name_fonts[$v] = $v;
        }

        // Cargamos el archivo de configuración
        $this->vars = parse_ini_file(self::FILE);
        parent::view(array_merge($this->vars, ['fonts' => $name_fonts]));
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
               
                self::load(false);
                $devolver = $escribo;
            }

            fclose($puntero_archivo);
        } else {
            $devolver = -1;
        }


        return $devolver;
    }
    // Se recargan los estilos
    static function load()
    {
        // Volver a ejecutar less.php y recargar css con js
        $less = new \lessc;
        $less->setFormatter("compressed");

        // Carga de la configuración de cada empresa
        $arr_conf = (file_exists(\FILE\CONFIG_COMPANY))
    ? array(parse_ini_file(\FILE\CONFIG_COMPANY))
    : array(parse_ini_file(\FILE\TEMPLATE_CONFIG));

        if (defined('\FILE\CONFIG_COMPANY')) {
            $less->setVariables($arr_conf[0]);
        } else {
            $less->setVariables(array(parse_ini_file(\FILE\CONFIG_TEMPLATE))[0]);
        }

        $inputDir = \FOLDERS\LESS;
        $outputDir = \PUBLICF\CSS;

        $files = $files = glob($inputDir . '*.{less}', GLOB_BRACE);

        foreach ($files as $file) {
            $filename = explode('/', $file);
            end($filename);
            $filename = explode('.', pos($filename));
            reset($filename);
            $filename = $filename[0];

            $inputFile = $inputDir . $filename . ".less";
            $outputFile =  $outputDir . $filename . ".css";
            //$cacheFolder = \FOLDERS\CACHES;
    
            $less->compileFile($inputFile, $outputFile);
        }
    }
    /**
     * Get google fonts
     * @param mixed $amount - integer amount of fonts to get. String "all" to get all the fonts
     * @return Array of fonts
     */
    private function get_google_fonts($amount = 30) : Array
    {
        //File to cache the fonts list
        $fontFile = 'google-web-fonts.txt';
        //Replace by your public API Key
        $APIKey = 'TU_API_KEY';
        //Total time the file will be cached in seconds, set to a 30 days (86400 seonds is a day)
        $cachetime = 1;

        if (file_exists($fontFile) && time() - $cachetime < filemtime($fontFile)) {
            $content = json_decode(file_get_contents($fontFile));
        } else {
            $googlefontsurl = 'https://www.googleapis.com/webfonts/v1/webfonts?sort=popularity&key=' . urlencode($APIKey);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_URL, $googlefontsurl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $fontContent = curl_exec($ch);

            curl_close($ch);

            $fp = fopen($fontFile, 'w');
            fwrite($fp, $fontContent);
            fclose($fp);

            $content = json_decode($fontContent);
        }

        if ($amount == "all") {
            return $content->items;
        } else {
            return array_slice($content->items, 0, $amount);
        }
    }
}
