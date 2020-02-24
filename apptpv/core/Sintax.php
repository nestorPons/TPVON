<?php

namespace app\core;

/**
 * Clase de para el trabajo con patrones de sintaxis
 */
trait Sintax
{
    private function sintax_if(): self
    {
        $has = preg_match_all('/@if\s*?\((.*?)\)(.*?)@endif/sim', $this->body(), $matches);

        if ($has) {
            for ($i = 0; $i < count($matches[0]); $i++) {
                $prop = trim($matches[1][$i], '$$');
                if (!is_null($this->attrs($prop))) {
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
            for ($i = 0; $i < $len; $i++) {
                $content = '';
                $cond = $this->attrs(trim($matches[1][$i], '$$'));
                $cont = $matches[2][$i];
           
                foreach ($cond as $key => $value) {
                    $option = str_replace('$$key', $key, $cont);
                    $option = str_replace('$$value', $value, $option);
                    $content .= $option;
                }
                $this->replace($matches[0][$i], $content);
            }
        }
        return $this;
    }
}
