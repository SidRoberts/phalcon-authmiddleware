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
     * @throws \Exception
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

        $class = $methodAnnotations->get("AuthMiddleware")->getArgument(0);

        $authMiddleware = new $class();

        if (!($authMiddleware instanceof \Sid\Phalcon\AuthMiddleware\MiddlewareInterface)) {
            throw new \Sid\Phalcon\AuthMiddleware\Exception("Not an auth middleware.");
        }

        return $authMiddleware->authenticate();
    }
}
