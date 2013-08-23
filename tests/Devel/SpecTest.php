<?php

namespace Test\Devel;

use Yapm\Devel\Spec as Spec;
use Yapm\Lib\Conf as Conf;

class SpecTest extends \PHPUnit_Framework_TestCase
{
    public function testNotNameSpec()
    {
        $this->setExpectedException('UnexpectedValueException');
        new Spec();
    }

    public function testInvalidNameSpec()
    {
        $this->setExpectedException('UnexpectedValueException');
        new Spec('aaa');
    }

    public function testParseFile()
    {
        $data = array(__DIR__ . "/../fixtures/test.spec");
        $method = new \ReflectionMethod('Yapm\Devel\Spec', 'parse');
        $method->setAccessible(true);

        $spec = new Spec($data);
        $this->assertTrue($method->invoke($spec));
    }

    public function testUpdateCommand()
    {
        new Conf(__DIR__ . "/../fixtures/yapm-devel.ini");
        $data = array(__DIR__ . "/../fixtures/test.spec");
        $spec = new Spec($data);
        $spec->updateCommand();
    }

    public function testGetAllCommits()
    {
        $data = array(__DIR__ . "/../fixtures/test.spec");
        $method = new \ReflectionMethod('Yapm\Devel\Spec', 'getAllCommits');
        $method->setAccessible(true);
        $this->assertEquals(array('90'), $method->invoke(new Spec($data), 'r90'));
        $this->assertEquals(
            array('90', '23df63q'),
            $method->invoke(
                new Spec($data),
                array('test in commit r90', 'test commit git r23df63q')
            )
        );
    }
}
