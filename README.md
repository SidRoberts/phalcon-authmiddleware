Sid\Phalcon\AuthMiddleware
==========================

Auth Middleware component for Phalcon.



[![Build Status](https://travis-ci.org/SidRoberts/phalcon-authmiddleware.svg?branch=master)](https://travis-ci.org/SidRoberts/phalcon-authmiddleware)
[![GitHub tag](https://img.shields.io/github/tag/sidroberts/phalcon-authmiddleware.svg?maxAge=2592000)]()



## Installing ##

Install using Composer:

```bash
composer require sidroberts/phalcon-authmiddleware
```

You'll need to add the event to the `dispatcher` DI service:

```php
use Phalcon\Mvc\Dispatcher;

$di->set(
    "dispatcher",
    function () use ($di) {
        $dispatcher = new Dispatcher();

        // ...

        $eventsManager = $di->getShared("eventsManager");

        $eventsManager->attach(
            "dispatch:beforeExecuteRoute",
            new \Sid\Phalcon\AuthMiddleware\Event()
        );

        $dispatcher->setEventsManager($eventsManager);

        // ...

        return $dispatcher;
    },
    true
);
```

Now, you can create middleware classes:

```php
namespace Example\AuthMiddleware;

use Phalcon\Mvc\User\Plugin;
use Sid\Phalcon\AuthMiddleware\MiddlewareInterface;

class MustBeLoggedIn extends Plugin implements MiddlewareInterface
{
    public function authenticate() : bool
    {
        $loggedIn = $this->auth->isLoggedIn();

        if (!$loggedIn) {
            $this->flash->error(
                "You must be logged in."
            );

            $this->response->redirect(
                "login"
            );

            return false;
        }

        return true;
    }
}
```



## Example ##

### Controller ###

```php
use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    /**
     * @AuthMiddleware("Example\AuthMiddleware\MustBeLoggedIn")
     * @AuthMiddleware("Example\AuthMiddleware\MustBeAdmin")
     */
    public function indexAction()
    {
        // ...
    }
}
```
