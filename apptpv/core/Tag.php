<?php

namespace app\core;

/**
 * Clase de para el manuejo de tags html
 */
class Tag
{
    private $element, $content, $id, $attrs, $type;

    function __construct(string $element)
    {
        $this->element = $element;
        $this->load();
    }
    /**
     * Carga de los componentes de la clase
     */
    private function load()
    {
        // Primer tipo de tag <tag></tag>
        if (
            preg_match("/<([\w\-]+)\s*([^>]*?)>(.*?)<\/\\1>/si", $this->element, $matches)
        ) {
            $this->content = $matches[3]??null;
           
        } else if (
            // Segundo tipo de tag <tag/>
            preg_match("/<([\w\-]+)\s*([^>]*?)\/>/si", $this->element, $matches)
        ) {
            $this->content = null; 
        }
        $this->type = $matches[1];
        if (
            preg_match_all("/([^\s]*)(\s*=\"(.*)?\")?/i", $matches[2], $match)
        ) {
            foreach (array_filter($match[0]) as $value) {
                $ar = explode('=', trim($value, "'"));
                $this->attrs[$ar[0]] = isset($ar[1]) ? preg_replace('/[\'\"]/', '', $ar[1]) : true;
            }
        }
        $this->id = $this->attrs['id'] ?? uniqid('tag');
    }
    /**
     * Getters y setters
     */
    public function id(string $id = null): string
    {
        if (!is_null($id)) {
            $this->replace($this->id, $id);
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
    public function content(string $content = null): string
    {
        if (!is_null($content)) {
            $this->replace($this->content, $content);
            $this->content = $content;
        }
        return $this->content;
    }
    public function element(): string
    {
        return $this->element;
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
     * Funcion auxiliar para reemplazar partes del elemento
     */
    private function replace($arg, $val = null): self
    {
        $this->element = str_replace($arg, $val, $this->element);
        return $this;
    }
}
