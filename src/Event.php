<?php

namespace Sid\Phalcon\AuthMiddleware;

class Event extends \Phalcon\Mvc\User\Plugin
{
    /**
     * @param \Phalcon\Events\Event            $event
     * @param \Phalcon\Mvc\DispatcherInterface $dispatcher
     *
     * @return boolean
     *
     * @throws \Sid\Phalcon\AuthMiddleware\Exception
     */
    public function beforeExecuteRoute(\Phalcon\Events\Event $event, \Phalcon\Mvc\DispatcherInterface $dispatcher, $data)
    {
        $methodAnnotations = $this->annotations->getMethod(
            $dispatcher->getHandlerClass(),
            $dispatcher->getActiveMethod()
        );

        if (!$methodAnnotations->has("AuthMiddleware")) {
            return true;
        }
        foreach ($methodAnnotations->getAll("AuthMiddleware") as $annotation) {
            $class = $annotation->getArgument(0);
            $extraParams = array_slice($annotation->getArguments(), 1);
            $authMiddleware = new $class();
            if (!($authMiddleware instanceof \Sid\Phalcon\AuthMiddleware\MiddlewareInterface)) {
                throw new \Sid\Phalcon\AuthMiddleware\Exception("Not an auth middleware.");
            }

            if ($authMiddleware instanceof \Sid\Phalcon\AuthMiddleware\MiddlewareParamInterface) {
                $authMiddleware->setExtraParam($extraParams);
            }
            
            $result = $authMiddleware->authenticate();
            if ($result === false) {
                throw new \Sid\Phalcon\AuthMiddleware\Exception("AuthMiddleware invalid permission");
            }
        }
        
        return true;
    }
}
