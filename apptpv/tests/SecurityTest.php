<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once('apptpv/core/Security.php');

final class SecurityTest extends TestCase
{
    function test_getZonesTest()
    {
        $this->assertIsArray(app\core\Security::getZones(), 'No es sun array!!');
    }

    function test_getJWT()
    {
        $this->assertIsString(app\core\Security::getJWT(), 'No es us String!!');
    }
    function test_isRestrict(){
        $this->assertTrue(app\core\Security::isRestrict('unaZona'));
        $this->assertFalse(app\core\Security::isRestrict('Company'));
    }
}
