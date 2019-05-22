<?php namespace app\models;
class Mylessc extends \lessc{
    
    public function compileFolder($inputDir, $outputDir){
        $files = $files = glob($inputDir . '*.{less}', GLOB_BRACE);
        foreach($files as $file) {
            $filename = explode('/', $file);
            end($filename);
            $filename = explode('.', pos($filename));
            reset($filename);
            $filename = $filename[0];
            $this->autoCompileLess($inputDir . $filename . ".less", $outputDir . $filename . ".css");
          }
    }
    public function autoCompileLess($inputFile, $outputFile) {
        // load the cache
        $cacheFile = $inputFile.".cache";
      
        $cache = (file_exists($cacheFile)) 
            ? unserialize(file_get_contents($cacheFile))
            : $inputFile;
        
        $newCache = $this->cachedCompile($cache);
      
        if (!is_array($cache) || $newCache["updated"] > $cache["updated"]) {
          file_put_contents($cacheFile, serialize($newCache));
          file_put_contents($outputFile, $newCache['compiled']);
        }
      }
    
}