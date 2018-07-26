<?php

namespace Sid\Phalcon\AuthMiddleware;

interface MiddlewareParamInterface
{
    /**
     * @return boolean
     */
    public function setExtraParam($params);
}
