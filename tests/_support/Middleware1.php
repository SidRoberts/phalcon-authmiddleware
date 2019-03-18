<?php

class Middleware1 extends \Phalcon\Mvc\User\Plugin implements \Sid\Phalcon\AuthMiddleware\MiddlewareInterface
{
    public function authenticate()
    {
        $this->dispatcher->setReturnedValue("Goodbye cruel world");
        
        return false;
    }
}
