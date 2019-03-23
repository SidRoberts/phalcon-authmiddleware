<?php

namespace Sid\Phalcon\AuthMiddleware;

interface MiddlewareInterface
{
    public function authenticate() : bool;
}
