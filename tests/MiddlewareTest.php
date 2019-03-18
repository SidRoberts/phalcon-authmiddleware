<?php

namespace Sid\Phalcon\AuthMiddleware\Tests\Unit;

use Codeception\TestCase\Test;
use Phalcon\Di;
use Phalcon\Mvc\Dispatcher;

class MiddlewareTest extends Test
{
   /**
    * @var \UnitTester
    */
    protected $tester;

    protected function _before()
    {
        Di::reset();

        $di = new \Phalcon\Di\FactoryDefault();

        $di->set(
            "dispatcher",
            function () {
                $dispatcher = new Dispatcher();

                $eventsManager = new \Phalcon\Events\Manager();

                $eventsManager->attach(
                    "dispatch",
                    new \Sid\Phalcon\AuthMiddleware\Event()
                );

                $dispatcher->setEventsManager($eventsManager);

                return $dispatcher;
            },
            true
        );

        $this->dispatcher = $di->get("dispatcher");
    }

    protected function _after()
    {
    }

    // tests
    public function testMiddlewareIsAbleToInterfereWhenReturningTrue()
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("index");

        $dispatcher->dispatch();

        $this->assertEquals(
            "Goodbye cruel world",
            $dispatcher->getReturnedValue()
        );
    }


    public function testMiddlewareDoesNotInterfereWhenReturningFalse()
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("index2");

        $dispatcher->dispatch();

        $this->assertEquals(
            "Hello world",
            $dispatcher->getReturnedValue()
        );
    }

    public function testDispatcherWorksAsNormalWithoutAnyMiddleware()
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("noMiddleware");

        $dispatcher->dispatch();

        $this->assertEquals(
            "Hello world",
            $dispatcher->getReturnedValue()
        );
    }

    public function testAnExceptionIsThrownIfWePassSomethingThatIsntProperMiddleware()
    {
        $this->expectException(
            \Sid\Phalcon\AuthMiddleware\Exception::class
        );



        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("notProperMiddleware");

        $dispatcher->dispatch();
    }
}
