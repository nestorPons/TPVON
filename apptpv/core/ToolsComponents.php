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
     *  Buscar componentes propios en el contenido 
     *  @param parte del código donde buscar
     *  @return Array de objetos Tag [ Componente en clase Tag ,  localización ]
     */
    private function search_components($content): array
    {

        $found = [];
        if(!isset($this->str_components))  $this->search_exist_components();
        // Buscar el primer componente
        if (
            $len = preg_match(
                "/<({$this->str_components})(\s[^>\/]*)?(>(.*?)<\/\\1|\/)>?/si",
                $content,
                $matches
            )
        ) {
            prs($matches);
        // Comprobar si el componente puede anidar a otros 
            // Si no puede guardar y seguir con la busqueda 
            // Si puede 
        // Buscar el cierre del componente teniendo encuenta si puede tener anidados 
        // Devolver el componente convertido en tag y la localización del mismo  
            for ($i = 0; $i < $len; $i++) {
                $found[] = [new Tag($matches[0][$i]), $matches[0][$i]];
            }
        }
        return $found;
    }
}
