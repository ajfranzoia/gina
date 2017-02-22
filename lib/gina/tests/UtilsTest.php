<?php

use PHPUnit\Framework\TestCase;
use Gina\Utils;

/**
 * @covers Utils
 */
final class UtilsTest extends TestCase
{

    public function testToCamelCase() {
        $result = Utils::toCamelCase('converted-string');
        $this->assertEquals('ConvertedString', $result);

        $result = Utils::toCamelCase('converted_string');
        $this->assertEquals('ConvertedString', $result);
    }

    public function testNow() {
        $result = Utils::now();
        $this->assertRegExp('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}$/', $result);
    }

}
