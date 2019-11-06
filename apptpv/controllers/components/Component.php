<?php namespace app\controllers\components;
use app\core\Data;
/**
 * Clase de madre de los componentes html
 */
class Component{
    protected $type, $id, $idObj, $name, $label, $class, $required, $pattern, $tittle, $minlength, $maxlength, $prefix,
        $COLLAPSE = false,
        $MINLENGTH = 1, 
        $MAXLENGTH = 255;

    const PREFIX_CONTAINER = 'container_'; 
    const PREFIX_ELEMENT = 'el_';
    const PREFIX_OBJECT = 'ob_';
        
    function __construct(Array $data = []){

        foreach($data as $key => $val){
            if($val){
                if($key == 'required') $val = 'required';
                if($key == 'disabled') $val = 'disabled';
                if($key == 'readonly') $val = 'readonly';
                if($key == 'checked')  $val = 'checked';

                $this->{$key} = $val??null;                
            }
        }
        $this->id = ($this->id)??$this->randomid();
        $this->idCon = self::PREFIX_CONTAINER . $this->id; 
        $this->idObj = self::PREFIX_OBJECT . $this->id;
    }
    /** Imprime ĺa vista 
    * $file => Nombre de la vista a imprimir 
    * $Data => Objeto Data para pasar datos a la vista [opcional]
    */
    function print(string $file, Data $Data = null){
        // Variables no obligatorias (Elemtos especificos) 
        foreach($this as $key => $value){
            ${$key} = $value;
            @${'attr_'.$key} = "$key = '$value'";     
        }        

        // Variables obligatorias para usos generales
        $class = $this->class($this->COLLAPSE);
        $prefix_element = self::PREFIX_ELEMENT; 
        $type= $this->type??null;
        $id = $this->id;
        $idContainer = self::PREFIX_CONTAINER . $id;
        $idElement = $this->idEl(); 
        $idObj = $this->idObj;
        
        ob_start();
        include \VIEWS\COMPONENTS . "$file.phtml";
        $this->file = ob_get_contents();
        $this->sintax(); 
        ob_end_clean();
        echo $this->file;
    }
    // Procesa la sintaxis de la plantillas
    private function sintax() : void{

        // Imprimiendo las variables de la clase a plantilla 
        foreach($this as $key => $value){
            $this->file = str_ireplace("--$key", $value, $this->file);    
        }
        // Eliminamos las variables vacias
        $this->file = preg_replace('/(\s)*--[A-Za-z]+(\s)*/i','', $this->file);
        // Procesando condicional if
        $this->file = $this->sintax_if($this->file);
    }
    private function sintax_if(string $text) : string {
        $regex_conditional = '/@if(\s)*?\((.)*?\)(.)*?@endif/sim'; 
        $start_condition = '/@if(\s)*?\((.)*?\)/sim';
        $end_condition = '/@endif/i'; 
        $has = preg_match_all($regex_conditional, $text, $matches);
        if($has){
            foreach ($matches[0] as $key => $value) {
                // Se obtiene la condición
                if(preg_match($start_condition, $value, $matches)){
                    $condition = preg_replace('/@if(\s)*?\(/sim','',$matches[0]);
                    $condition = preg_replace('/\)$/','',$condition);
                    if(empty($condition)) $condition = null;
                    $valcon = false; 
                    eval('if ($condition) { $valcon = true; }');

                   if($valcon){
                       // Imprimimos el contenido dentro del condicional
                        $replace = preg_replace($start_condition,'',$value); 
                        $replace = preg_replace($end_condition,'',$replace);
                        $text = str_replace($value, $replace, $text);
                    } else {
                        // Eliminamos todo el condicional 
                        $text = str_replace($value, '', $text);
                    }
                }
              
            } 
        }
        return $text;
    }
    protected function getnameclass(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
    protected function randomid(){
        return uniqid($this->type);
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
    function id(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function idEl(){ 
        return  SELF::PREFIX_ELEMENT . $this->id; 
    }
    function idCon(){ 
        return $this->idCon; 
    }
    function idObj(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
}