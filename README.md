Sid\Phalcon\AuthMiddleware
==========================

Auth Middleware component for Phalcon.



[![Build Status](https://travis-ci.org/SidRoberts/phalcon-authmiddleware.svg?branch=master)](https://travis-ci.org/SidRoberts/phalcon-authmiddleware)



## Installing ##

Install using Composer:

```json
{
	"require": {
		"sidroberts/phalcon-authmiddleware": "dev-master"
	}
}
```

You'll need to add the event to the `dispatcher` DI service:

```php
$di->set(
	"dispatcher",
	function () use ($di) {
        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        // ...

        $eventsManager = $di->getShared("eventsManager");

        $eventsManager->attach("dispatch:beforeExecuteRoute", new \Sid\Phalcon\AuthMiddleware\Event());

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

class MustBeLoggedIn extends \Phalcon\Mvc\User\Plugin implements \Sid\Phalcon\AuthMiddleware\MiddlewareInterface
{
    /**
     * @return boolean
     */
    public function authenticate()
    {
        $loggedIn = $this->auth->isLoggedIn();

        if (!$loggedIn) {
            $this->flash->error("You must be logged in.");

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
class IndexController extends \Phalcon\Mvc\Controller
{
    /**
     * @AuthMiddleware("Example\AuthMiddleware\MustBeLoggedIn")
     */
    public function indexAction()
    {
    	// ...
    }
}
```
