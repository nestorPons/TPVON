<?php namespace app\views\components\controllers;
/**
 * Clase de madre de los componentes html
 */
class Component{
    protected $type, $id, $name, $label, $class, $required, $pattern, $tittle, $minlength, $maxlength, $prefix;
        
    function __construct(Array $data = []){
        $this->id = $this->randomid();
        $this->name = $data['name']??'';
        $this->label = ucfirst($data['label']??$this->name);
        $this->class = $data['class']??'';
        $this->required = isset($data['required']);
        $this->pattern = $data['pattern']??null; 
        $this->title = $data['title']??null; 
        $this->minlength = $data['minlength']??null; 
        $this->maxlength = $data['maxlength']??null;
        $this->for = $data['for']??null;
    }
    protected function printInput(){
        $TYPE = $this->TYPE;
        $id = $this->id; 
        $idSel = 'input_' .$this->id; 
        $name = $this->printName(); 
        $title = $this->printTitle();
        $required = $this->printRequired();
        $label = $this->printLabel();
        $class = $this->class($this->COLLAPSE);
        $minlength = $this->printMinlength($this->MINLENGTH);
        $maxlength = $this->printMaxlength($this->MAXLENGTH);
        $pattern = $this->printPattern();
        $for = $this->printFor();
        include \FOLDERS\COMPONENTS . 'view/input.phtml';
    }
    protected function getnameclass(){
        $arr_controller= explode('\\',get_class($this));
        $controller = end($arr_controller);
        return strtolower($controller);
    }
    protected function randomid(){
        return uniqid($this->prefix());
    }
    protected function prefix(string $arg = null){
        if(!empty($arg)) $this->prefix = $arg; 
        return $this->prefix; 
    }
    protected function class(bool $collapse = false, string $args = null){
        if ($args) $this->class .= ' ' . $args;
        $this->class .= $collapse ? ' collapse' : ''; 
        return $this->class;
    }
    protected function printRequired(){
        return  ($this->required)? 'required' : '';
    }
    protected function printClass(){
        return  (empty($this->class))? '' : "class='{$this->class}'";
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
    protected function printMinlength(string $min = null){
        if($min) $this->minlength = $min; 
        return  (empty($this->minlength))? '' : "minlength='{$this->minlength}'";
    }
    protected function printMaxlength(string $max = null){
        if($max) $this->maxlength = $max; 
        return  (empty($this->maxlength))? '' : "maxlength='{$this->maxlength}'";
    }
    // getters y setters
    function id(int $arg = null){
        if($arg) $this->{__FUNCTION__} = $arg; 
        return $this->{__FUNCTION__}; 
    }
}