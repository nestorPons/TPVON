<?php namespace app\controllers\components;
/**
 * Clase de menus tipo cuadrados de tipo numero
 */
class MenuSquare extends Component{
    function __construct(Array $data = []){
        parent::__construct($data);
        $this->print('menuSquare');
    }
}
