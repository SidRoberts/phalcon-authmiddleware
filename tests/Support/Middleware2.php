<?php

namespace Tests\Support;

use Phalcon\Di\Injectable;
use Sid\Phalcon\AuthMiddleware\MiddlewareInterface;

class Middleware2 extends Injectable implements MiddlewareInterface
{
    public function authenticate() : bool
    {
        $this->dispatcher->setReturnedValue("Goodbye cruel world");
        
        return true;
    }
}
