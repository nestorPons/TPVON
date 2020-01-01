<?php namespace app\core;
// Código ****************
$less = new \lessc;
$less->setFormatter("compressed");

// Carga de la configuración de cada empresa
$arr_conf = (file_exists(\FILE\CONFIG_COMPANY))
    ? array(parse_ini_file(\FILE\CONFIG_COMPANY))
    : array(parse_ini_file(\FILE\TEMPLATE_CONFIG));

if(defined('\FILE\CONFIG_COMPANY')) $less->setVariables($arr_conf[0]);
else $less->setVariables(array(parse_ini_file(\FILE\CONFIG_TEMPLATE))[0]);
    
compileFolder(\FOLDERS\LESS, \PUBLICF\CSS);

// Funciones *************
function compileFolder($inputDir, $outputDir){
    $files = $files = glob($inputDir . '*.{less}', GLOB_BRACE);
    foreach($files as $file) {
        $filename = explode('/', $file);
        end($filename);
        $filename = explode('.', pos($filename));
        reset($filename);
        $filename = $filename[0];
        // Carga de de cache (anulada)
        autoCompileLess($inputDir . $filename . ".less", $outputDir . $filename . ".css");
        }
}
function autoCompileLess($inputFile, $outputFile) {
    global $less;
    // load the cache
    $cacheFile = $inputFile.".cache";
    
    $cache = (file_exists($cacheFile)) 
        ? unserialize(file_get_contents($cacheFile))
        : $inputFile;
    
    $newCache = $less->cachedCompile($cache);
    
    if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
        file_put_contents($cacheFile, serialize($newCache));
        file_put_contents($outputFile, $newCache['compiled']);
    }
    }