<?php

namespace GiftCards\Extension;

use Mockery;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use GiftCards\TestExtension\TestCase\Extension\AbstractExtendableTestCase;

class UtilityExtensionTest extends AbstractExtendableTestCase
{
    protected $extension;

    public function setUp()
    {
    	$this->extension = new UtilityExtension();
    }

    public function testSetProperty()
    {
    	$object = new SetPropertyTestClass();
    	$this->assertNull($object->getProperty());
    	$this->assertSame($object, $this->extension->setProperty($object, 'property', 'blabla'));
    	$this->assertEquals('blabla', $object->getProperty());
    }

    public function testGetRandomIdWithoutFakerGettable()
    {
    	$this->assertInternalType('int', $this->extension->getRandomId());
    }

    public function testGetRandomIdWithFaker()
    {
    	$number = rand(1, 1e9);

        $testCase = Mockery::mock('GiftCards\TestExtension\TestCase\Extension\AbstractExtendableTestCase');
    	$testCase
    	   ->shouldReceive('getFaker->randomNumber')
    	   ->once()
    	   ->with(1, 1e9)
    	   ->andReturn($number)
    	;

    	$this->extension->setTestCase($testCase);

    	$this->assertEquals($number, $this->extension->getRandomId());
    }
    
    public function testAssertContainsListenerWithSameAndObjectSame()
    {
    	$dispatcher = new EventDispatcher();
    	$setPropertyTestClass = new SetPropertyTestClass();
    	$dispatcher->addListener('event1', array($setPropertyTestClass, 'getProperty'));
    	$this->extension->assertContainsListener($dispatcher, 'event1', array($setPropertyTestClass, 'getProperty'));
    	$callback = function(){};
    	$dispatcher->addListener('event1', $callback);
    	$this->extension->assertContainsListener($dispatcher, 'event1', $callback);
    }
    
    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that the dispatcher contains the exact listener GiftCards\Extension\CallableClass Object ()
     */
    public function testAssertContainsListenerWithSameAndObjectNotSame()
    {
    	$dispatcher = new EventDispatcher();
    	$callback = new CallableClass();
    	$dispatcher->addListener('event1', $callback);
    	$callback = new CallableClass();
    	$this->extension->assertContainsListener($dispatcher, 'event1', $callback);
    }
    
    public function testAssertContainsListenerWithNotSameAndObjectNotSame()
    {
    	$dispatcher = new EventDispatcher();
    	$callback = new CallableClass();
    	$dispatcher->addListener('event1', $callback);
    	$callback = new CallableClass();
    	$this->extension->assertContainsListener($dispatcher, 'event1', $callback, false);
    }
    
    public function testContainsSubscriberWithSameAndObjectsSame()
    {
    	$subscriber = new TestSubscriber();
    	$dispatcher = new EventDispatcher();
    	$dispatcher->addSubscriber($subscriber);
    	$this->extension->assertContainsSubscriber($dispatcher, $subscriber);
    }
    
    /**
     * @expectedException \PHPUnit_Framework_ExpectationFailedException
     * @expectedExceptionMessage Failed asserting that the dispatcher contains the subscriber GiftCards\Extension\TestSubscriber Object ()
the listener exception was: Failed asserting that the dispatcher contains the exact listener Array (
    0 => GiftCards\Extension\TestSubscriber Object ()
    1 => 'method1'
)
     */
    public function testContainsSubscriberWithSameAndObjectsNotSame()
    {
    	$subscriber = new TestSubscriber();
    	$dispatcher = new EventDispatcher();
    	$dispatcher->addSubscriber($subscriber);
    	$subscriber = new TestSubscriber();
    	$this->extension->assertContainsSubscriber($dispatcher, $subscriber);
    }
    
    public function testContainsSubscriberWithNotSameAndObjectsNotSame()
    {
    	$subscriber = new TestSubscriber();
    	$dispatcher = new EventDispatcher();
    	$dispatcher->addSubscriber($subscriber);
    	$subscriber = new TestSubscriber();
    	$this->extension->assertContainsSubscriber($dispatcher, $subscriber, false);
    }
    
    public function testContainsSubscriberWithStringPassedAsSubscriber()
    {
    	$subscriber = new TestSubscriber();
    	$dispatcher = new EventDispatcher();
    	$dispatcher->addSubscriber($subscriber);
    	$this->extension->assertContainsSubscriber($dispatcher, get_class($subscriber), false);
    }
}

class SetPropertyTestClass
{
    protected $property;

    public function getProperty()
    {
    	return $this->property;
    }
}

class TestSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
    	return array(
    		'event1' => 'method1',
    	    'event2' => array('method2', 3),
    	    'event3' => array(
    		
    	        array('method3', 4),
    	        array('method4')
    	    ) 
    	);
    }
    
    public function methodd1(){}
    public function methodd2(){}
    public function methodd3(){}
    public function methodd4(){}
}

class CallableClass
{
    public function __invoke()
    {
    	;
    }
}
