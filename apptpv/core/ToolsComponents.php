<?php

namespace app\core;

/**
 * Clase de para el trabajo con patrones de sintaxis
 */
trait ToolsComponents
{
    private $found_components = []; 

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
        $this->str_components = rtrim($str_components, '|');
    }
    /**
     *  Buscar componentes propios en el contenido 
     *  @param parte del código donde buscar
     *  @return Array de objetos Tag [ Componente en clase Tag ,  localización ]
     */
    private function search_components($content): array
    {
        $found = [];
        if (!isset($this->str_components))  $this->search_exist_components();
        // Buscar el primer componente

        if (
            preg_match(
                "/<([\/])?({$this->str_components})(\s[^>\/]*)?(>|\/>)?(.*)/si",
                $content,
                $matches
            )
        ) {
            // Comprobar si el componente puede anidar a otros 
            // Si encuentra 3 indices es simple si tiene 4 es compuesto
            //prs($matches);
            $close_composed = $matches[1] == '/';
            $close_simple = strpbrk($matches[4], '/');
            $name_component = $matches[2];
            $content = $matches[5];

            if ($close_simple) {
                $this->found_components[] = [$name_component, true];
            } else {
                // Guardo los componentes principales [ nombre, estado ]
                if($close_composed){
                    $f = array_filter($this->found_components ,function(){
  AKI ::                       
                    }); 
                    $a = array_reverse(array_column($this->found_components,0), true);
                    $b = array_search($name_component, $a); 

                    if($b)
                    $this->found_components[$b][1] = true;
                }else{
                    $this->found_components[] = [$name_component, false];
                }
                // Obtenemos la posición de la coincidencia
                
                // Buscar el c  erre del componente teniendo encuenta que puede tener anidados   
            }
            $this->search_components($content);

            // Si no puede guardar y seguir con la busqueda 
            // Si puede 
            // Devolver el componente convertido en tag y la localización del mismo  

           // $found[] = [new Tag($matches[0][$i]), $matches[0][$i]];
        }
        prs($this->found_components);
        return $found;
    }
}
