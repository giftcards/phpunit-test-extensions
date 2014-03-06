<?php

namespace GiftCards\Extension;

use GiftCards\Extension\Tests\Fixtures\MockEntity;

class EntityExtensionTest extends \PHPUnit_Framework_TestCase
{

    protected $extension;

    public function setUp()
    {
        $this->extension = new EntityExtension();
    }

    public function testSetDefaultScalar()
    {
        $entity = new MockEntity();

        $this->extension->assertEntityGetterSetter($entity, 'getMyScalar', 'setMyScalar', "Scalar");
    }

    public function testSetDefaultObject()
    {
        $defaultDateTime = new \DateTime();
        $entity = new MockEntity($defaultDateTime);

        $testForDefault = function ($test, $entity, $getter) use ($defaultDateTime) {
            $test->assertSame($defaultDateTime, $entity->$getter());
        };
        $this->extension->assertEntityGetterSetter(
            $entity,
            'getMyDateTime',
            'setMyDateTime',
            $defaultDateTime,
            $testForDefault
        );
    }

    public function testAssertEntityGetterSetterArrayPass()
    {
        $defaultDateTime = new \DateTime();
        $entity = new MockEntity($defaultDateTime);

        $testForDefault = function ($test, $entity, $getter) use ($defaultDateTime) {
            $test->assertSame($defaultDateTime, $entity->$getter());
        };

        $this->extension->assertEntityGetterSetterArray(
            $entity,
            array(
                array('getMyScalar', 'setMyScalar', 'Scalar'),
                array('getMyDateTime', 'setMyDateTime', $defaultDateTime, $testForDefault),
            )
        );
    }
}