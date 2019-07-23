<?php namespace app\views\components\controllers;
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
    const PREFIX_OBJECT = 'ob';
        
    function __construct(Array $data = []){

        foreach($data as $key => $val){
            $this->{$key} = $val??null;
        }
        $this->id = ($this->id)??$this->randomid();
        $this->idObj = self::PREFIX_OBJECT . $this->id;
    }
    /** Imprime ĺa vista 
    * $file => Nombre de la vista a imprimir 
    * $Data => Objeto Data para pasar datos a la vista [opcional]
    */
    function print(string $file, Object $Data = null){

        // Variables no obligatorias (Elemtos especificos) 
        foreach($this as $key => $value){
            ${$key} = $value;
            @${'attr_'.$key} = "$key = '$value'";
        }

        // Variables obligatorias para usos generales
        $prefix_element = self::PREFIX_ELEMENT; 
        $type= $this->type??null;
        $id = $this->id;
        $idContainer = self::PREFIX_CONTAINER . $id;
        $idElement = $this->idEl(); 
        $idObj = $this->idObj;

        $hidden = ($type == 'hidden')?'hidden':null;
        $disabled = (isset($this->disabled))?'disabled':null;
        $readonly = (isset($this->readonly))?'readonly':null;
        $checked = (isset($this->checked))?'checked':null;
        $placeholder = (isset($this->placeholder))?"placeholder = '{$this->placeholder}'":null;
        $body = $this->body??null;
        $name = $this->printName(); 
        $title = $this->printTitle();
        $required = $this->printRequired();
        $label = $this->printLabel();
        $class = $this->class($this->COLLAPSE);
        $minlength = $this->printMinlength($this->MINLENGTH);
        $maxlength = $this->printMaxlength($this->MAXLENGTH);
        $pattern = $this->printPattern();
        $for = $this->printFor();
        $value = $this->printValue();
        $list = $this->printList();
        $spinner = $this->spinner??null;
        $caption = $this->caption??null;
        $columns = $this->columns??null;
        $iconlast = isset($this->iconlast)?'iconlast='.$this->iconlast:null; 
        $tabindex = isset($this->tabindex)?'tabindex='.$this->tabindex:null; 
        $onclick = isset($this->onclick)?'onclick='.$this->onclick:null;
        $onblur = isset($this->onblur)?'onblur='.$this->onblur:null;
        $onfocus = isset($this->onfocus)?'onfocus='.$this->onfocus:null;
        $onload = isset($this->onload)?'onload='.$this->onload:null;
        $onkeypress = isset($this->onkeypress)?'onkeypress='.$this->onkeypress:null;
        $onkeydown = isset($this->onkeydown)?'onkeydown='.$this->onkeydown:null;
        $onkeyup = isset($this->onkeyup)?'onkeyup='.$this->onkeyup:null;
        $icon = isset($this->icon)?$this->icon:false;
        $printName = $this->printName();
        $options = $this->options??null;
        $printTitle = $this->printTitle();
        $selected = $this->selected??null; 
        
        include \VIEWS\COMPONENTS . "view/$file.phtml";
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
    protected function printRequired(){
        return  ($this->required)? 'required' : null;
    }
    protected function printClass(){
        return  (empty($this->class))? null : "class='{$this->class}'";
    }
    protected function printValue(){
        return  (!isset($this->value) || is_null($this->value))? null : "value='{$this->value}'";
    }
    protected function printName(){
        return  (empty($this->name))? null : "name='{$this->name}'";
    }
    protected function printLabel(){
        return  (empty($this->label))? null : $this->label;
    }
    protected function printPattern(){
        return  (empty($this->pattern))? null : "pattern='{$this->pattern}'";
    }
    protected function printTitle(){
        return  (empty($this->title))? null : "title='{$this->title}'";
    }
    protected function printFor(){
        return  (empty($this->for))? null : "for='{$this->for}'";
    }
    protected function printList(){
        return  (empty($this->list))? null : "list='{$this->list}'";
    }
    protected function printMinlength($min = null){
        if($min) $this->minlength = $min; 
        return  (empty($this->minlength))? null : "minlength='{$this->minlength}'";
    }
    protected function printMaxlength($max = null){
        if($max) $this->maxlength = $max; 
        return  (empty($this->maxlength))? null : "maxlength='{$this->maxlength}'";
    }
    protected function printView($file){
        foreach((array)$this as $key => $val){
            if(strpos($key, '*')){
                $key = substr($key, 3);
            }
            ${$key} = $val;
        }

        include \VIEWS\COMPONENTS . "view/$file.phtml";
    }
    // getters y setters
    function id(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
    function idEl(){ 
        return  SELF::PREFIX_ELEMENT . $this->id; 
    }
    function idCon(){ 
        return  SELF::PREFIX_CONTAINER . $this->id; 
    }
    function idObj(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
}