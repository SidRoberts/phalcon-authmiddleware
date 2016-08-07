<?php
// Here you can initialize variables that will be available to your tests


class IndexController extends \Phalcon\Mvc\Controller
{
    /**
     * @AuthMiddleware("Middleware1")
     */
    public function indexAction()
    {
        return "Hello world";
    }

    /**
     * @AuthMiddleware("Middleware2")
     */
    public function index2Action()
    {
        return "Hello world";
    }
    
    /**
     * @AuthMiddleware("Middleware1")
     * @AuthMiddleware("Middleware2")
     */
    public function index3Action()
    {
        return "Hello world";
    }

    public function noMiddlewareAction()
    {
        return "Hello world";
    }

    /**
     * @AuthMiddleware("IndexController")
     */
    public function notProperMiddlewareAction()
    {
        return "This won't work as an Exception should get thrown";
    }
}


class Middleware1 extends \Phalcon\Mvc\User\Plugin implements \Sid\Phalcon\AuthMiddleware\MiddlewareInterface
{
    public function authenticate()
    {
        $this->dispatcher->setReturnedValue("Goodbye cruel world");
        
        return false;
    }
}

class Middleware2 extends \Phalcon\Mvc\User\Plugin implements \Sid\Phalcon\AuthMiddleware\MiddlewareInterface
{
    public function authenticate()
    {
        $this->dispatcher->setReturnedValue("Goodbye cruel world");
        
        return true;
    }
}
