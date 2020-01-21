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
        autoCompileLess($inputDir, $outputDir, $filename);
        }
}
// Compila y cachea los archivos lessc
function autoCompileLess($inputDir, $outputDir, $filename) {
    global $less;
    $inputFile = $inputDir . $filename . ".less"; 
    $outputFile =  $outputDir . $filename . ".css"; 
    $cacheFolder = \FOLDERS\CACHES;

    // load the cache
    $cacheFile = $cacheFolder.$filename.".cache";
    
    $cache = (file_exists($cacheFile) && !ENV) 
        ? unserialize(file_get_contents($cacheFile))
        : $inputFile;
    
    $newCache = $less->cachedCompile($cache);
    
    if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
        file_put_contents($cacheFile, serialize($newCache));
        file_put_contents($outputFile, $newCache['compiled']);
    }
    }