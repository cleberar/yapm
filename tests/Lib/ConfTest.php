<?php

namespace Test\Lib;

use Yapm\Lib\Conf as Conf;

class ConfTest extends \PHPUnit_Framework_TestCase
{
    private static function instance()
    {
        return new Conf(__DIR__ . "/../fixtures/yapm-devel.ini");
    }

    public function testNotConfiguration()
    {
        $this->setExpectedException('Exception');
        new Conf();
    }

    public function testErrorConfConfiguration()
    {
        $this->setExpectedException('UnexpectedValueException');
        new Conf(__DIR__ . "/../fixtures/yapm-devel-invalid.ini");
    }

    public function testInvalidData()
    {
        $this->setExpectedException('UnexpectedValueException');
        $Conf = self::instance();
        $Conf::repo("a");
    }

    public function testInvalidParameter()
    {
        $this->setExpectedException('UnexpectedValueException');
        $Conf = self::instance();
        $Conf::invalid();
    }

    public function testParameter()
    {
        $Conf = self::instance();
        $data = $Conf::repo();
        $this->assertEquals(
            array("domain" => "http://yapm-mosh.rhcloud.com/"),
            $data
        );

        $this->assertEquals(
            "http://yapm-mosh.rhcloud.com/",
            $Conf::repo("domain")
        );
    }
}
