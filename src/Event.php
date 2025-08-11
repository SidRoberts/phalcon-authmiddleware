<?php

namespace Sid\Phalcon\AuthMiddleware;

use Phalcon\Events\Event as PhalconEvent;
use Phalcon\Mvc\DispatcherInterface;
use Phalcon\Di\Injectable;

final class Event extends Injectable
{
    /**
     * @throws Exception
     */
    public function beforeExecuteRoute(PhalconEvent $event, DispatcherInterface $dispatcher, $data) : bool
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

            $authMiddleware = new $class();

            if (!($authMiddleware instanceof MiddlewareInterface)) {
                throw new Exception(
                    "Not an auth middleware."
                );
            }



            $result = $authMiddleware->authenticate();

            /*
             * Multi-middleware mode
             */
            if ($result === false) {
                return $result;
            }
        }

        return true;
    }
}
