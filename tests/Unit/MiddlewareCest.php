<?php

namespace Tests\Unit;

use Phalcon\Di\Di;
use Phalcon\Mvc\Dispatcher;
use Tests\Support\UnitTester;

class MiddlewareCest
{
    public function _before()
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

                $dispatcher->setDefaultNamespace("Tests\\Support\\");

                return $dispatcher;
            },
            true
        );

        $this->dispatcher = $di->get("dispatcher");
    }



    public function middlewareIsAbleToInterfereWhenReturningTrue(UnitTester $I)
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("index");

        $dispatcher->dispatch();

        $I->assertEquals(
            "Goodbye cruel world",
            $dispatcher->getReturnedValue()
        );
    }


    public function middlewareDoesNotInterfereWhenReturningFalse(UnitTester $I)
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("index2");

        $dispatcher->dispatch();

        $I->assertEquals(
            "Hello world",
            $dispatcher->getReturnedValue()
        );
    }

    public function dispatcherWorksAsNormalWithoutAnyMiddleware(UnitTester $I)
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("noMiddleware");

        $dispatcher->dispatch();

        $I->assertEquals(
            "Hello world",
            $dispatcher->getReturnedValue()
        );
    }

    public function anExceptionIsThrownIfWePassSomethingThatIsntProperMiddleware(UnitTester $I)
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("notProperMiddleware");



        $I->expectThrowable(
            \Sid\Phalcon\AuthMiddleware\Exception::class,
            function () use ($dispatcher) {
                $dispatcher->dispatch();
            }
        );
    }

    public function multiMiddlewareModeFirstCase(UnitTester $I)
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("index3");

        $dispatcher->dispatch();

        $I->assertNotEquals(
            "Accepted all",
            $dispatcher->getReturnedValue()
        );
    }

    public function multiMiddlewareModeSecondCase(UnitTester $I)
    {
        $dispatcher = $this->dispatcher;

        $dispatcher->setControllerName("index");
        $dispatcher->setActionName("index4");

        $dispatcher->dispatch();

        $I->assertEquals(
            "Accepted all",
            $dispatcher->getReturnedValue()
        );
    }
}
