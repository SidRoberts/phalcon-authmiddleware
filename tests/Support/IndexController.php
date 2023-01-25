<?php

namespace Tests\Support;

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    /**
     * @AuthMiddleware("Tests\Support\Middleware1")
     */
    public function indexAction()
    {
        return "Hello world";
    }

    /**
     * @AuthMiddleware("Tests\Support\Middleware2")
     */
    public function index2Action()
    {
        return "Hello world";
    }
    
    /**
     * @AuthMiddleware("Tests\Support\Middleware1")
     * @AuthMiddleware("Tests\Support\Middleware2")
     */
    public function index3Action()
    {
        return "Accepted all";
    }

    /**
     * @AuthMiddleware("Tests\Support\Middleware2")
     * @AuthMiddleware("Tests\Support\Middleware3")
     */
    public function index4Action()
    {
        return "Accepted all";
    }

    public function noMiddlewareAction()
    {
        return "Hello world";
    }

    /**
     * @AuthMiddleware("Tests\Support\IndexController")
     */
    public function notProperMiddlewareAction()
    {
        return "This won't work as an Exception should get thrown";
    }
}
