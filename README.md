# Gina

Lightweight MVC framework. Built on top of known and robust libraries.

## Installation and setup

* Copy `lib/gina/app-skeleton` folder for a basic app structure into a new project.
* Run `composer install` on the root folder.
* If using a MySql database backend, configure connection parameters under `config/parameters.ini`.

## Application structure

* Application files must be located in the root `src` folder. Gina follows the classic MVC structure divided into controllers, models and views. PSR-4 namespace specification is used for autoloading.
* Configuration is done in the `config` folder. `bootstrap.php` is utilized for app initialization and `parameters.ini` contains global configs.
* Public webroot is located in the `public` folder, where the front controller `index.php` resides. CSS, scripts, and other static assets can be put in this directory.

### Controllers
Gina will search for controllers in the `src/Controller` folder. A controller's filename must end with `Controller` for it to be properly located by the autoloader.
Controllers must extend the base framework controller class `Gina\Controller` and be under the `App\Controller` namespace.
Request can be handled by creating actions as instance methods, which will be called by the dispatcher when a request is received.
If actions return an scalar value (e.g. a number or rendered HTML), it will be used as the response body. If a `Gina\Response` object is returned, that will be send as response.

Example controller:

```php
<?php

namespace App\Controller;

use Gina\Controller;

class MyController extends Controller {

    // Public action accessed via /my-controller/show/{id}
    public function show($id) {
        // ...
    }

}

```

#### Routing

Simpler controller/action routes are supported in the form of:
`http://host/controller/action/param1/param2`. First value between slashes will match to the controller, second one to the action, and the rest of the values found will be passed as named parameters to the action methods.

Controller names are converted to camelcase, so both `http://host/my-controller/view` and `http://host/my_controller/view` will match to `MyController::index()` action.

### Models

Gina uses the library PHP ActiveRecord to provide ORM functionality.
Gina will search for models under the `src/Model` folder.
Models must extend the base framework model class `Gina\Model` and be located in the `App\Model` namespace.
`Gina\Model` inherits from `ActiveRecord\Model`, therefore all methods and properties are available.

Example model:

```php
<?php

namespace App\Model;

use Gina\Model;

class MyModel extends Model {

    // PHPActiveRecord class method
    public static function findActive() {
        return MyModel::find('all', ['conditions' => ['status > ?', 5]]);
    }

}

```

### Views

Gina uses the library Twig in order to provide templating capabilities.
Gina will search for views under the `src/Views` folder, which must have a `.twig` extension. Views have the full Twig functionality, which means they can use the complete Twig syntax, extend other views, include snippets, etc.

Example view:

```twig
{% extends "layout.twig" %}

{% block content %}
    <h1>My Cars</h1>
    <div id="car">
        <h2>{{ car.name }}<h2>
        <p>{{ car.description }}<p>
    </div>
{% endblock %}
```

#### View rendering from a controller action

To render a view and return its result from a controller action, the controller auxiliary method `render()` is provided. It accepts the view name and the required context variables for its rendering. The view will be searched using the controller name as a subfolder. If the view name is omitted, the action name will me used as the filename instead.

Example:

```php
<?php

namespace App\Controller;

use Gina\Controller;

class CarsController extends Controller {

    public function index() {
        $cars = [
            ['name' => 'Macan S', 'brand' => 'Porsche'],
            ['name' => 'Hilux', 'brand' => 'Hilux'],
            // ...
        ];

        $availableCars = 43;

        // Will render the view located under src/View/Cars/index
        // using the given context variables
        return $this->render([
            'cars' => $cars,
            'available' => $availableCars
        ]);
    }

}

```

## Demo application

Gina includes a demo application that features a list of teams for the configured European football league and their basic data. It also allows selecting favorites for quick access. Data is fetched using the [FootballData public API](http://api.football-data.org). Guzzle is used as a client for HTTP requests.

App configuration must be done in config/parameters.ini, using the footbalData INI section. Required configuration parameters are:

| Parameter  | Type | Description |
| ------------- | ------------- | ------------- |
| authToken  | string  | API token provided by FootballData api  |
| leagueId  | number  | Target league ID  |

### Available endpoints

`GET /teams`: returns a full HTML view for the found league teams ordered by favorites and name
Status codes:
`POST /teams/add_favorite/{id}`: sets a team as favorite given its id
Status code:
`POST /teams/remove_favorite/{id}`: unsets a team as favorite given its id


## Framework architecture

### Third party libraries

Instead of reinventing the wheel, Gina is built using known and tested PHP libraries:
* [Symfony HTTP foundation component](https://github.com/symfony/http-foundation) provides an object-oriented layer on top of PHP global variables and functions for request and response handling. Used by several frameworks like Laravel, Symfony and Drupal.
* [Twig](https://github.com/twigphp/Twig): instead of working with PHP low level functions like `ob_start()`, `ob_get_clean()`, etc., Twig provides flexible and advanced template rendering capabilities. Twig is both developer and designer friendly, thanks to its clean syntax.
* [PHP ActiveRecord](https://github.com/jpfuentes2/php-activerecord) provides an easy to use ActiveRecord implementation.
* [Arrayzy](https://github.com/bocharsky-bw/Arrayzy) provides wrappers for PHP built-in array functions and object-oriented array manipulation library, allowing a more functional array programming.
* [Kint](https://github.com/raveren/kint) is used for debugging purposes, as a replacement for `var_dump()` and `debug_backtrace()` functions.

### Design principles

Gina follows the classic Model-View-Controller pattern, ensuring separation of presentation and logic, but other patterns and principles were also taken into account:
* Front controller pattern is used in order to catch all the requests and have a single point of entry to the application. The front controller instantiates a new application Dispatcher and handles it the current request for further processing.
* An application Dispatcher is used to process the current request and obtain a proper HTTP response. The Dispatcher will coordinate the request lifecycle: it will parse the current route, load the correct controller via a controller factory, initialize application models, and obtain the final response by executing the proper controller action. Finally, this generated response is sent to the client.
* Based on the Factory pattern, a `ControllerFactory` class is used to load the correct controller making use of the PHP Reflection API. The controller class and action method are guessed from the request url.
* Basic inheritance is used for most framework classes that extend other third party classes, which allows a more convenient customization and overriding.

### Request processing flow

A basic  application flow for a request processing is as follows:

![Request processing flow](https://raw.githubusercontent.com/ajfranzoia/gina/master/request-flow-v3.png)


## Tests

Framework tests can be run using phpunit by executing `composer run gina-tests`
. Composer development packages are required for tests to work properly.

## TODOs

* Provide Gina as a composer package
* Add more tests and improve coverage
* Add proper error handling
