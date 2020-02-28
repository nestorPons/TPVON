<?php

namespace app\core;

/**
 * Clase de para el manuejo de tags html
 */
class Tag
{
    private
        $element = '',
        $body = '',
        $id = '',
        $attrs = [],
        $type = '',
        $prefix = 'tag',
        $code = '';

    function __construct(string $element = null)
    {
        if (!is_null($element)) {
            $this->code = $element;
            $this->element = $element;
            $this->load();
        }
    }
    /**
     * Carga de los componentes de la clase
     */
    private function load()
    {

        // Limpiamos el contenido de comentarios 
        $this->clear();
        // Primer tipo de tag <tag></tag>
        if (
            preg_match("/<([\w\-]+)\s*([^>]*?)>(.*)<\/\\1(>|\s[^>]*?>)/si", $this->element, $matches)
        ) {
            $this->body = $matches[3] ?? null;
        } else if (
            // Segundo tipo de tag <tag/>
            preg_match("/<([\w\-]+)\s*([^>]*?)\/>/si", $this->element, $matches)
        ) {
            $this->body = null;
        }
        // Valores por defecto
        $this->type = $matches[1];

        // Preparamos el string para que sea reconocido los arrays json
        $val = $matches[2];

        // Quitar las comillas en los arrays y objetos json
        $json_val = str_replace('"[', '[', $val);
        $json_val = str_replace(']"', ']', $json_val);
        $json_val = str_replace('}"', '}', $json_val);
        $json_val = str_replace('"{', '{', $json_val);
        $val = str_replace("'", '"', $json_val);
        if (
            // Regex extrae atributos de una cadena como:
            // options1={"perro1":"de", "gato1":1} class="SOEL" REQUIRED 
            $len = preg_match_all("/(\w+)\s*=?\s*([\[\{](.*?)[\]\}]|([\'\"])(.*?)\\4)?/sim", $val, $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $name_attr = $matches[1][$i];
                $value = empty($matches[2][$i]) ? true : $matches[2][$i] ;
                if (is_string($value)) $value = json_decode($value);
                
                $this->attrs($name_attr, $value);
            }
        }

        $this->id = (!empty($this->attrs['id']) && $this->attrs['id'] != '--id') ? $this->attrs['id'] :  uniqid($this->prefix);
    }
    /**
     * Obtiene los valiores de los atributos
     */
    public function get(string $key = null)
    {

        // Si no pasa argumentos se devuelven todos los attributos
        if (is_null($key)) {
            return $this->attrs;
        } else {
            return $this->attrs[$key] ?? null;
        }
    }
    /**
     * Establece valores de los atributos
     */
    public function set(string $key, $value)
    {
        return $this->attrs[$key] = $value;
    }
    /**
     * Elimina atributos del elemento
     */
    public function del(string $attr): bool
    {
        if (
            preg_match("/$attr(\s*=\s*[\"']+\w*?['\"]+?)/si", $this->element, $matches)
        ) {
            $this->replace($matches[0], '');
            unset($this->{$attr});
            return true;
        }
        return false;
    }
    /**
     * Añade atributos al elemento
     */
    public function add(string $key, $value): self
    {
        $this->replace("<{$this->type}", "<{$this->type} $key='$value'>");

        return $this;
    }
    /**
     *   Devuelve todos los argumentos de un tag
     *  @return array de la clase Tag
     */
    public function search(string $str_tag): array
    {
        $regex = "/\<($str_tag)\s*([^>]*?)>(.*?)<\/\g{1}>/si";
        /**
         * 0 -> Todo
         * 1 -> tag
         * 2 -> argimentos
         * 3 -> contenido
         */
        if (
            $len = preg_match_all($regex, $this->body, $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $a[$i] = new Tag($matches[0][$i]);
            }
        }
        return $a ?? [];
    }
    /**
     * Elimina un tag del contenido del elemento
     * @param Tag a ser eliminado
     */
    public function unset(Tag $tag): bool
    {
        return $this->replace($tag->element, '', $this->body) > 0;
    }
    /**
     * Funcion auxiliar para reemplazar partes del elemento
     */
    public function replace($arg, $val = null): int
    {
        $this->element = str_replace($arg, $val, $this->element, $count);

        if (
            preg_match("/<([\w\-]+)\s*([^>]*?)>(.*)<\/\\1>/si", $this->element, $matches)
        ) {
            $this->body = $matches[3] ?? null;
        }
        return $count;
    }
    /**
     * Funcion auxiliar reemplazar por expresion regular
     */
    public function preg(string $regex, string $value): self
    {
        $this->element = preg_replace($regex, $value, $this->element);
        return $this;
    }
    /**
     *   Devuelve todos las etiquetas que contiene
     *  @param tag de la clase de etiqueta
     *  @return array de tags encontrados
     */
    public function tags(string $tag): array
    {
        /**
         * 0 -> Todo
         * 1 -> tag
         * 2 -> argimentos
         * 3 -> contenido
         */
        if (
            $len = preg_match_all("/\<($tag) ([^>]*?)>(.*?)<\/\\1>/sim", $this->body(), $matches)
        ) {
            for ($i = 0; $i < $len; $i++) {
                $a[$i] = new Tag($matches[0][$i]);
            }
        }
        return $a ?? [];
    }
    /**
     * Getters y setters
     */
    public function id(string $id = null): string
    {
        if (!is_null($id)) {
            $this->replace($this->id, $id);
            $this->replace('--id', $id);
            $this->id = $id;
        }
        return $this->id;
    }
    public function type(string $type = null): string
    {
        if (!is_null($type)) {
            $this->replace($this->type, $type);
            $this->type = $type;
        }
        return $this->type;
    }
    public function body(string $body = null): ?string
    {
        if (!is_null($body)) {
            $this->replace($this->body, $body);
        }
        return $this->body ?? null;
    }
    public function element(string $arg = null): string
    {
        if (!is_null($arg)) {
            $this->element = $arg;
        }
        return $this->element;
    }
    public function prefix(string $arg = null): string
    {
        if (!is_null($arg)) $this->prefix = $arg;
        return $this->prefix;
    }
    public function code()
    {
        return $this->code;
    }
    /**
     * Getter setter de los atributos del tag html 
     * @param array setter [llave => valor] 
     * @param string getter con valor a devolver 
     * @param null getter sin argumento devuelve todos 
     */
    public function attrs($arg = null, $val = null)
    {
        if (!is_null($arg)) {
            if (!is_null($val)) {
                // Si es un string comprobamos si es un array o objeto json
                if (is_string($val)) {
                    $json_val = json_decode($val);
                    if (!is_null($json_val)) $val = $json_val;
                }
                $this->attrs = array_merge($this->attrs, [$arg => ($val)]);
            }
            return $this->attrs[$arg] ?? null;
        }
        return $this->attrs ?? null;
    }
    /**
     * Comprime y formatea el codigo
     */
    public static function compress_code(string $code = null): string
    {
        $search = array(
            '/\>[^\S ]+/s',  // remove whitespaces after tags
            '/[^\S ]+\</s',  // remove whitespaces before tags
            '/(\s)+/s'       // remove multiple whitespace sequences
        );

        $replace = array('>', '<', '\\1');
        return preg_replace($search, $replace, $code);
    }
    /**
     * Limpia el contenido de comentarios
     */
    public function clear(): self
    {
        $this->preg('/((<!--(.|\s)*?-->)|(?<!:)\/\/.*?\n|\/\*\*(.*?)\*\/)/s', '');
        //$this->preg("/[\r\n|\n|\r|\s]+/", " ");
        return $this;
    }
}
