<?php

namespace Tests;

use Phalcon\Di\Injectable;
use Sid\Phalcon\AuthMiddleware\MiddlewareInterface;

class Middleware3 extends Injectable implements MiddlewareInterface
{
    public function authenticate() : bool
    {
        $this->dispatcher->setReturnedValue("Goodbye cruel world");
        
        return true;
    }
}
