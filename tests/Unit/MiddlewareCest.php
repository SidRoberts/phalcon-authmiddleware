<?php

namespace Tests\Unit;

use Phalcon\Di\Di;
use Phalcon\Di\FactoryDefault as FactoryDefaultDi;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\Mvc\Dispatcher;
use Sid\Phalcon\AuthMiddleware\Event as AuthMiddlewareEvent;
use Sid\Phalcon\AuthMiddleware\Exception as AuthMiddlewareException;
use Tests\Support\UnitTester;

class MiddlewareCest
{
    public function _before()
    {
        Di::reset();

        $di = new FactoryDefaultDi();

        $di->set(
            "dispatcher",
            function () {
                $dispatcher = new Dispatcher();

                $eventsManager = new EventsManager();

                $eventsManager->attach(
                    "dispatch",
                    new AuthMiddlewareEvent()
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
            AuthMiddlewareException::class,
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
