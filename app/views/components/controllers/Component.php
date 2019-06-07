<?php namespace app\views\components\controllers;
/**
 * Clase de madre de los componentes html
 */
class Component{
    protected $type, $id, $name, $label, $class, $required, $pattern, $tittle, $minlength, $maxlength, $prefix,
    $COLLAPSE = false,
    $MINLENGTH = 1, 
    $MAXLENGTH = 255;

    const PREFIX_CONTAINER = 'container_'; 
    const PREFIX_ELEMENT = 'main_';
        
    function __construct(Array $data = []){

        foreach($data as $key => $val){
            $this->{$key} = $val??null;
        }
        $this->id = ($this->id)??$this->randomid();
    }
    function print(string $file = 'input'){
        $type= $this->type??null;
        $id = $this->id;
        $idContainer = self::PREFIX_CONTAINER . $id;
        $idElement = $this->idEl(); 
        $hidden = ($type == 'hidden')?'hidden':'';
        $disabled = (isset($this->disabled))?'disabled':'';
        $readonly = (isset($this->readonly))?'readonly':'';
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
        $tabindex = isset($this->tabindex)?'tabindex='.$this->tabindex:''; 
        $onclick = isset($this->onclick)?'onclick='.$this->onclick:'';
        $onblur = isset($this->onblur)?'onblur='.$this->onblur:'';
        $onfocus = isset($this->onfocus)?'onfocus='.$this->onfocus:'';
        $icon = isset($this->icon)?$this->icon:false;
        
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
        $class = $this->mainclass ?? '';
        $class .= ' ' . $this->class ?? ''; 
        if ($args) $class .= ' ' . $args;
        $class .= $collapse ? ' collapse' : ''; 
        return $class;
    }
    protected function printRequired(){
        return  ($this->required)? 'required' : '';
    }
    protected function printClass(){
        return  (empty($this->class))? '' : "class='{$this->class}'";
    }
    protected function printValue(){
        return  (!isset($this->value) || is_null($this->value))? '' : "value='{$this->value}'";
    }
    protected function printName(){
        return  (empty($this->name))? '' : "name='{$this->name}'";
    }
    protected function printLabel(){
        return  (empty($this->label))? '' : $this->label;
    }
    protected function printPattern(){
        return  (empty($this->pattern))? '' : "pattern='{$this->pattern}'";
    }
    protected function printTitle(){
        return  (empty($this->title))? '' : "title='{$this->title}'";
    }
    protected function printFor(){
        return  (empty($this->for))? '' : "for='{$this->for}'";
    }
    protected function printList(){
        return  (empty($this->list))? '' : "list='{$this->list}'";
    }
    protected function printMinlength($min = null){
        if($min) $this->minlength = $min; 
        return  (empty($this->minlength))? '' : "minlength='{$this->minlength}'";
    }
    protected function printMaxlength($max = null){
        if($max) $this->maxlength = $max; 
        return  (empty($this->maxlength))? '' : "maxlength='{$this->maxlength}'";
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
}