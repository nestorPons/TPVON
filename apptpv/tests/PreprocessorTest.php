<?php 
use PHPUnit\Framework\TestCase;
require_once('apptpv/core/Prepocessor.php');

/**
 * PreprocessorTest
 * @group group
 */
class PreprocessorTest extends TestCase
{
    /** @test */
    public function test_construct()
    {
        define('\FOLDERS\HTDOCS','');
        
        $class = new \app\core\Prepocessor();
    }

}
