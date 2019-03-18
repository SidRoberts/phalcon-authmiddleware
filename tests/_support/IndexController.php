<?php

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
