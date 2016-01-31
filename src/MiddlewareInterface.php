<?php

namespace Sid\Phalcon\AuthMiddleware;

interface MiddlewareInterface
{
    /**
     * @return boolean
     */
    public function authenticate();
}
