<?php

namespace app\core;

/**
 * Clase de para el trabajo con patrones de sintaxis
 */
trait ToolsComponents 
{
    // Carga de los componentes creados en la carpeta
    private function search_exist_components()
    {
        $str_components = '';
        $folder = \APP\VIEWS\MYCOMPONENTS;
        if (!file_exists($folder)) mkdir($folder, 0777, true);
        $gestor = opendir($folder);
        // Busca los componentes en la carpeta de vistas componentes
        while (($file = readdir($gestor)) !== false) {
            if ($file != "." && $file != "..") {
                $arr = explode('.', $file);
                $str_components .= $arr[0] . '|';
            }
        }
        $this->str_components = rtrim($str_components,'|');
    }
    /**
     *  Buscar componentes existentes en el contenido 
     *  @param parte del código donde buscar
     *  @return Array de objetos Tag 
     */

    private function search_components($content): array
    {
        $found = [];
 
        if(!isset($this->str_components))  $this->search_exist_components();

        if (
            $len = preg_match_all(
                "/<({$this->str_components})(\s[^>\/]*)?(>(.*)<\/\\1|\/)>?/si",
                $content,
                $matches
            )
        ) {
            for ($i = 0; $i < $len; $i++) {
                $found[] = [new Tag($matches[0][$i]), $matches[0][$i]];
            }
        }
        
        return $found;
    }
}
