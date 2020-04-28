<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Clase contenedora de elementos a testar
 */
require_once('apptpv/core/Data.php');

class DataTest extends TestCase
{
    protected function setUp() : void
    {
        /**
         * Objetos a cargar dentro de la clase contenedora
         */
        $object = new stdClass();
        $object->property = 'one property';
        // Carga por arrays 
        $this->dataArray = new app\core\Data(['uno' => 1, 'dos' => 'dos', 3 => 'tres']);
        // Carga un objeto
        $this->dataObject = new app\core\Data($object);
    }
    
    function testget()
    {
        $this->assertIsArray($this->dataArray->getAll());
        $this->assertIsArray($this->dataObject->getAll());
    }
    function testaddItem()
    {
        // Añadido de elementos al contenedor
        $this->assertEquals(1, $this->dataArray->addItems(['keycuatro' => 'valuecuatro']));
        // Comprobación que hay los elementos que se esperan
        $this->assertCount(4, $this->dataArray->getAll());
    }
    function testDelete(){   
        $this->assertEquals(1, $this->dataArray->delete([3]));
    }
    function testisEmail(){
        $this->dataArray->addItems(['email' => 'email@email.es']);
        $this->assertTrue($this->dataArray->isEmail('email'));
        $this->assertFalse($this->dataArray->isEmail('uno'));
    }
    function testIsString(){
        $this->assertTrue($this->dataArray->isSmaller('dos', 4));
        $this->assertFalse($this->dataArray->isSmaller('uno', 1));
    }
    function testToArray(){
        $this->assertIsArray($this->dataArray->toArray());
    }
    function testFilter(){
        $this->assertEquals(2, $this->dataArray->filter(new  app\core\Data(['uno' => 'es uno']))); 
    }
    function testIsEmpty(){
        $this->assertFalse($this->dataArray->isEmpty()); 
    }
}
