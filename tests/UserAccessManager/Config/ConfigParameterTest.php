<?php
/**
 * ConfigParameterFactoryTest.php
 *
 * The ConfigParameterFactoryTest unit test class file.
 *
 * PHP versions 5
 *
 * @author    Alexander Schneider <alexanderschneider85@gmail.com>
 * @copyright 2008-2017 Alexander Schneider
 * @license   http://www.gnu.org/licenses/gpl-2.0.html  GNU General Public License, version 2
 * @version   SVN: $id$
 * @link      http://wordpress.org/extend/plugins/user-access-manager/
 */
namespace UserAccessManager\Config;

use UserAccessManager\UserAccessManagerTestCase;

/**
 * Class ConfigParameterTest
 *
 * @package UserAccessManager\Config
 */
class ConfigParameterTest extends UserAccessManagerTestCase
{
    /**
     * @return \PHPUnit_Framework_MockObject_MockObject|ConfigParameter
     */
    private function getStub()
    {
        return $this->getMockForAbstractClass(
            '\UserAccessManager\Config\ConfigParameter',
            [],
            '',
            false,
            true,
            true
        );
    }

    /**
     * @group   unit
     * @covers  \UserAccessManager\Config\ConfigParameter::__construct()
     */
    public function testCanCreateInstance()
    {
        $stub = $this->getStub();
        $stub->expects($this->exactly(2))->method('isValidValue')->will($this->returnValue(true));
        $stub->__construct('testId');

        self::assertAttributeEquals('testId', 'id', $stub);
        self::assertAttributeEquals(null, 'defaultValue', $stub);

        $stub->__construct('otherId', 'defaultValue');

        self::assertAttributeEquals('otherId', 'id', $stub);
        self::assertAttributeEquals('defaultValue', 'defaultValue', $stub);

        $stub = $this->getStub();
        $stub->expects($this->once())->method('isValidValue')->will($this->returnValue(false));

        self::expectException('\Exception');
        $stub->__construct('otherId', 'defaultValue');
    }

    /**
     * @group   unit
     * @covers  \UserAccessManager\Config\ConfigParameter::getId()
     */
    public function testGetId()
    {
        $stub = $this->getStub();
        $stub->expects($this->once())->method('isValidValue')->will($this->returnValue(true));
        $stub->__construct('testId');

        self::assertEquals('testId', $stub->getId());
    }

    /**
     * @group   unit
     * @covers  \UserAccessManager\Config\ConfigParameter::validateValue()
     */
    public function testValidateValue()
    {
        $stub = $this->getStub();
        $stub->expects($this->exactly(2))
            ->method('isValidValue')
            ->will($this->onConsecutiveCalls(true, false));

        self::assertNull(self::callMethod($stub, 'validateValue', ['value']));

        self::expectException('\Exception');
        self::callMethod($stub, 'validateValue', ['value']);
    }

    /**
     * @group   unit
     * @covers  \UserAccessManager\Config\ConfigParameter::setValue()
     *
     * @return ConfigParameter
     */
    public function testSetValue()
    {
        $stub = $this->getStub();
        $stub->expects($this->once())
            ->method('isValidValue')
            ->will($this->returnValue(true));

        $stub->setValue('testValue');

        self::assertAttributeEquals('testValue', 'value', $stub);

        return $stub;
    }

    /**
     * @group   unit
     * @depends testSetValue
     * @covers  \UserAccessManager\Config\ConfigParameter::getValue()
     *
     * @param ConfigParameter $stub
     */
    public function testGetValue($stub)
    {
        self::assertEquals('testValue', $stub->getValue());
    }
}
