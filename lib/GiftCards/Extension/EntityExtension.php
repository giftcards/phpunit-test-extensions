<?php

namespace GiftCards\Extension;

use GiftCards\TestExtension\TestCase\Extension\AbstractExtension;

class EntityExtension extends AbstractExtension
{

    /**
     * Asserts an entity's default getter value, setter value return and getter value after setting. If the default value is neither null
     * nor a scalar, you may need a custom default tester. In that case you can pass in an anonymous function that takes three arguments.
     * The first arg to your anonymous function will be $this (the test instance), the second arg will be the entity whose getter/setter
     * is being tested, and the third arg will be the name of the getter method. The default function is responsible for calling
     * the appropriate assert___() method.
     * @param object $entity The entity to test the setter and getter for
     * @param string $getter The getter function to call
     * @param string $setter The setter function to call
     * @param mixed $value value to send to the setter function
     * @param string $default The expected default value of the getter after initialization, or an anonymous function that will do test for default values that are not null or scalar
     * @param string $return The expected result of the getter after setting
     */
    public function assertEntityGetterSetter(
        $entity,
        $getter,
        $setter = null,
        $value = null,
        $default = null,
        $return = null
    ) {
        $args = func_get_args();
        // anonymous functions are instances of class Closure
        if ($default instanceof \Closure) {
            $default($this, $entity, $getter); // should call $this->assert(...) at some point
        } else {
            $this->assertSame($default, $entity->$getter());
        }

        if (isset($args[2])) {
            $this->assertSame($entity, $entity->$setter($value));
            $this->assertSame(6 == count($args) ? $return : $value, $entity->$getter());
        }
    }

    /**
     * Convenience method that accepts an entity and an array of parameters to test.
     * Array parameters match the method arguments of assertEntityGetterSetter method.
     *
     * @param $entity
     * @param array $calls
     */
    public function assertEntityGetterSetterArray($entity, array $calls = array())
    {
        foreach ($calls as $call) {
            array_unshift($call, $entity);
            call_user_func_array(array($this, 'assertEntityGetterSetter'), $call);
        }
    }

    public function assertEntityAddRemoveCollection(
        $entity,
        $getter,
        $addMethod = null,
        $removeMethod = null,
        $objects = array(),
        $initialCount = 0
    ) {

        $this->assertCount($initialCount, $entity->$getter());

        $expectedCount = $initialCount;

        $args = func_get_args();

        if (isset($args[2])) {

            foreach ($objects as $object) {

                $this->assertSame($entity, $entity->$addMethod($object));
                $expectedCount++;
                $this->assertCount($expectedCount, $entity->$getter());
                $this->assertContains($object, $entity->$getter());
            }
        }

        if (isset($args[3])) {

            foreach ($objects as $object) {

                $this->assertSame($entity, $entity->$removeMethod($object));
                $expectedCount--;
                $this->assertCount($expectedCount, $entity->$getter());
                $this->assertNotContains($object, $entity->$getter());
            }
        }
    }

}
