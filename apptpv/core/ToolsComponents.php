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
     *  Buscar componentes de primer nivel propios en el contenido 
     *  @param parte del código donde buscar
     *  @return Array de objetos Tag [ Componente en clase Tag ,  localización ]
     */
    private function search_components($text_process): array
    {
        if (!isset($this->str_components))  $this->search_exist_components();
        // Buscar el primer componente

        if (
            preg_match(
                "/(<({$this->str_components})\s*([^>]*)\/?>)(.*)/si",
                $text_process,
                $matches
            )
        ) {
            // Comprobar si el componente puede anidar a otros 
            // Si encuentra 3 indices es simple si tiene 4 es compuesto
            $tag_code = $matches[1];
            $name_component = $matches[2];
            $simple_tag = strpos($matches[1], '/>') != false;
            $text_process = $matches[4];

            if ($simple_tag) {
                $this->found_components[] = new Tag($tag_code);
            } else {

                // Buscamos tanto aperturas como cierres
                if (
                    $len  = preg_match_all(
                        "/\/?\s*$name_component([^>]*?>)/sim",
                        $text_process,
                        $matches,
                        PREG_OFFSET_CAPTURE
                    )
                ) {
                    $nested = 0;
                    //prs($matches);
                    for ($i = 0; $i < $len; $i++) {
                        $tag = $matches[0][$i][0];
                        $pos = $matches[0][$i][1] + strlen($tag);
                        if ($tag[0] != '/') {
                            $nested++;
                        } else {
                            if ($nested == 0) {
                                // Tenemos el componente completo
                                $rest = substr($text_process, 0, $pos);
                                $element = $tag_code . $rest;
                                $this->found_components[] = new Tag($element);
                                $text_process = \str_replace($rest, '', $text_process);
                            } else {
                                // Hay anidados
                                $nested--;
                            }
                        }
                    }
                }
            }
            $this->search_components($text_process);

            // Si no puede guardar y seguir con la busqueda 
            // Si puede 
            // Devolver el componente convertido en tag y la localización del mismo  
        }
        return $this->found_components;
    }
    // Prepara las cadenas de texto con atributos para que las reconozca json_encode
    public static function prepare(string $str): string
    {
        $str = str_replace("'", '"', $str);

    }
}
