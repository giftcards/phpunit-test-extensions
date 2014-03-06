<?php
namespace GiftCards\Extension;

use GiftCards\TestExtension\TestCase\Extension\AbstractExtension;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UtilityExtension extends AbstractExtension
{
    public function setProperty($object, $name, $value)
    {
        $reflectionProperty = new \ReflectionProperty($object, $name);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);

        return $object;
    }

    public function getRandomId()
    {
        try {
            return $this->attemptTestCaseMethodCall('getFaker')->randomNumber(1, 1e9);
        } catch (\BadMethodCallException $e) {
            return rand(1, 1e9);
        }
    }

    public function assertContainsListener(EventDispatcherInterface $dispatcher, $eventName, $listener, $same = true)
    {
        foreach ($dispatcher->getListeners($eventName) as $testListener) {

            try {

                if ($same) {
                    if (is_array($testListener) && is_array($listener)) {

                        $this->assertSame($listener[0], $testListener[0]);
                        $this->assertSame($listener[1], $testListener[1]);
                        return;
                    }

                    $this->assertSame($listener, $testListener);
                    return;
                }

                $this->assertEquals($listener, $testListener);
                return;
            } catch (\PHPUnit_Framework_ExpectationFailedException $e) {
            }
        }

        throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                'Failed asserting that the dispatcher contains the%s listener %s',
                $same ? ' exact' : '',
                \PHPUnit_Util_Type::export($listener)
            )
        );
    }

    public function assertContainsSubscriber(EventDispatcherInterface $dispatcher, $subscriber, $same = true)
    {
        if (!$subscriber instanceof EventSubscriberInterface) {

            $subscriber = new $subscriber();
        }

        try {

            foreach ($this->normalizeListeners($subscriber) as $eventName => $listeners) {

                foreach ($listeners as $listener) {

                    $this->assertContainsListener($dispatcher, $eventName, $listener, $same);
                }
            }
        } catch (\PHPUnit_Framework_ExpectationFailedException $e) {

            throw new \PHPUnit_Framework_ExpectationFailedException(sprintf(
                "Failed asserting that the dispatcher contains the subscriber %s\nthe listener exception was: %s",
                \PHPUnit_Util_Type::export($subscriber),
                $e->getMessage()
            ));
        }
    }

    protected function normalizeListeners(EventSubscriberInterface $subscriber)
    {
        $listeners = array();

        foreach ($subscriber::getSubscribedEvents() as $eventName => $params) {

            $listeners[$eventName] = array();

            if (is_string($params)) {
                $listeners[$eventName][] = array($subscriber, $params);
            } elseif (is_string($params[0])) {
                $listeners[$eventName][] = array($subscriber, $params[0]);
            } else {
                foreach ($params as $listener) {
                    $listeners[$eventName][] = array($subscriber, $listener[0]);
                }
            }
        }

        return $listeners;
    }
}
