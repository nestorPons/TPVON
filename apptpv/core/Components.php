<?php namespace app\core;
use app\core\Data;
/**
 * Clase de madre de los componentes html
 */
class Components{
    const PREFIX_CONTAINER = 'container_'; 
    const PREFIX_COMPONENT = 'component_'; 

    function __construct($type, Array $data = null){
        foreach($data as $key => $val){
            // Atributos booleanos
            if($key == 'required') $val = 'required';
            if($key == 'disabled') $val = 'disabled';
            if($key == 'readonly') $val = 'readonly';
            if($key == 'checked')  $val = 'checked';
            // Resto atributos
            $this->{$key} = $val??null;     
        }
        $this->print($type);
    }
    /** Imprime ĺa vista 
    * $file => Nombre de la vista a imprimir 
    * $Data => Objeto Data para pasar datos a la vista [opcional]
    */
    function print(string $file, $Data = null){
        // Variables no obligatorias (Elemtos especificos) 
        foreach($this as $key => $value){
            ${$key} = $value;
        }        
        $this->file = file_get_contents(\VIEWS\MYCOMPONENTS . "$file.phtml");
        $this->autoId($file);
        // Buscamos el id del elemento contenedor
        // Si no tiene id se crea uno 
        $this->style_scoped();
        $this->script_scoped();
        $this->sintax(); 
        $this->clear();
        ob_start();
            echo ($this->file);
        ob_end_flush();
    }
    private function autoId($type){
        $this->id = ($this->id)??uniqid($type);
        $this->id_container = self::PREFIX_CONTAINER . $this->id;
        $this->id_component = self::PREFIX_COMPONENT . $this->id;
        $this->file = str_ireplace('--id', $this->id, $this->file);
    }
    private function clear(){
        $this->file = \preg_replace("/[\r\n|\n|\r|\s]+/", " ", $this->file);
    }
    // Procesa la sintaxis de la plantillas 
    private function sintax() : void{
        // Procesando condicional if
        $this->sintax_if();
        // Imprimiendo las variables de la clase a plantilla 
        $has = preg_match_all('#\$\$(\w+)#is', $this->file, $matches);
        // Modificando las propiedades o tags de los elementos html
        if($has){
            for($i = 0; $i < count($matches[0]); $i++) {
                $prop = $matches[1][$i]; 
                if(\property_exists($this, $prop)){
                    $value = $this->{$matches[1][$i]} ?? '';
                    $this->file = str_replace($matches[0][$i], $value, $this->file);
                } else {
                    // En caso que no exista la propiedad la eliminamos 
                    $regex = "#\w+?\s*=\s*[\"']\s*\\$\\$$prop\b\"#";
                    $this->file = preg_replace($regex, '', $this->file);
                    $this->file = str_replace("\$\$$prop", '', $this->file);
                }
            }
        }
    }
    private function sintax_if() {
        $regex_conditional = '/@if\s*?\((.*?)\)(.*?)@endif/sim'; 
        $has = preg_match_all($regex_conditional, $this->file, $matches);
        if($has){
            for($i = 0; $i < count($matches[0]) ; $i++){
                $prop = trim($matches[1][$i], '$$'); 
                if(\property_exists($this, $prop)){
                    $condition = $this->{$porp};
                    $valcon = false;
                    eval('if ($condition) { $valcon = true; }');
                    $this->file = ($valcon) 
                        ? str_replace($matches[0][$i], $matches[2][$i], $this->file)
                        : str_replace($matches[0][$i], '' , $this->file);

                    // Quitamos los espacios en blanco
                    $this->file = preg_replace("[\n|\r|\n\r]", "", $this->file);
                } else {
                    // Si no existe la propiedad quitamos el elemento
                    $this->file = str_replace($matches[0][$i], '' , $this->file);
                }
            }
        }
    }
    protected function getnameclass(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
    protected function class(bool $collapse = false, string $args = null){
        $class = $this->mainclass ?? null;
        $class .= ' ' . $this->class ?? null; 
        if ($args) $class .= ' ' . $args;
        $class .= $collapse ? ' collapse' : null; 
        return $class;
    }
    protected function printView($file){
        foreach((array)$this as $key => $val){
            if(strpos($key, '*')){
                $key = substr($key, 3);
            }
            ${$key} = $val;
        }

        include \VIEWS\COMPONENTS . "$file.phtml";
    }
    // getters y setters genéricos
    function id(string $arg = null) : string{
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function id_container(string $arg = null) : string{
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    // Añade atributos a la primera etiqueta del componente
    private function addParent($tag, $attr, $value, string $content) : string{
        $regex = "/<\s*{$tag}.*?>/";
        if(preg_match($regex, $content, $matches)){
            $search = substr($matches[0], 0 ,-1); 
            $replace = "{$search} {$attr}='{$value}'";
            $content = str_replace($search, $replace, $content);
        };  
        return $content;
    }
    private function style_scoped() : void{

        $has_scoped = preg_match('/<style.*?scoped[^<]*?>(.*?)<\/style>/mis', $this->file, $matches);
        if($has_scoped) {
            // Quitamos el comando scope
            $tagstyle = str_replace('scoped','',$matches[0]);
            // Quitamos las reglas principales
            $tagstyle = preg_replace('/@import.*?;/', '', $tagstyle);  
            $tagstyle = preg_replace('/@charser.*?;/', '', $tagstyle);  
            
            // Se coloca el id a los estilos 
            $less = new \lessc;
            $content_less = $less->compile('#'.$this->id.'{'.$matches[1].'}'); 
            
            $tagstyle = str_replace($matches[1], $content_less, $tagstyle);
            $this->file = str_replace($matches[0], $tagstyle, $this->file);
        };
    }
    // Comportamiento scoped para script-> individualiza el style en el objeto contenedor
    private function script_scoped() : void{
        $has_scoped = preg_match_all('/<script[^>]*scoped>(.*?)<\/script>/si', $this->file, $matches);
        if($has_scoped){
            // Quitar los scopes 
            foreach ($matches[0] as $key => $value) {
                // Quitamos el comando scope
                $noscope = str_replace(' scoped', '', $value); 
                $this->file = str_replace($value, $noscope, $this->file);
            }
            foreach ($matches[1] as $key => $value) {
                // encapsular en contenido en una funcion autoejecutable js
                $env =  '(function(){'. $value .'})();'; 
                $this->file = str_replace($value, $env, $this->file);
            }
        }
    }
}