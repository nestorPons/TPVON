<?php

namespace app\core;

/**
 * Clase de para el trabajo con patrones de sintaxis
 */
trait Sintax
{
    private function sintax_if(): self
    {
        $regex_conditional = '/@if\s*?\((.*?)\)(.*?)@endif/sim';
        $has = preg_match_all($regex_conditional, $this->body(), $matches);
        if ($has) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $prop = trim($matches[1][$i], '$$');

                if (\property_exists($this, $prop)) {
                    $condition = $this->attrs($prop);
                    $valcon = false;
                    eval('if ($condition) { $valcon = true; }');
                    if ($valcon)
                        $this->replace($matches[0][$i], $matches[2][$i]);
                    else
                        $this->replace($matches[0][$i], '');

                    // Quitamos los espacios en blanco
                    $this->replace("[\n|\r|\n\r]", "");
                } else {
                    // Si no existe la propiedad quitamos el elemento
                    $this->replace($matches[0][$i], '');
                }
            }
        }
        return $this;
    }
    private function sintax_for(): self
    {

        if (
            $len = preg_match_all('/@for\s*?\((.*?)\)(.*?)@endfor/sim', $this->body(), $matches)
        ) {
AKI :: Esta buscando en la propiedad 
            pr($matches, $this->element());
            for ($i = 0; $i < $len; $i++) {
                //m-select m-table
                $prop = trim($matches[1][$i], '$$');
                $content = '';

                if (\property_exists($this, $prop)) {
                    // valor predeterminado
                    $arr = (is_array($this->attrs($prop)))
                        // Se convierte el valor en un array
                        ? $this->attrs($prop)
                        : json_decode($this->attrs($prop));

                    $cont = $matches[2][$i];
                    foreach ($arr as $key => $value) {
                        $option = str_replace('$$key', $key, $cont);
                        if (!is_null($this->attrs($value)) && $this->attrs('value') == $value) {
                            $option = preg_replace('#\>#', ' selected>', $option);
                        }
                        $option = str_replace('$$value', $value, $option);
                        $content .= $option;
                    }
                    $this->replace($matches[0][$i], $content);
                } else {
                    // Si no existe la propiedad quitamos el elemento
                    $this->replace($matches[0][$i], '');
                }
            }
        }
        return $this;
    }
}
