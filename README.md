# Gina

Lightweight MVC framework. Built on top of known and robust libraries.

## Installation and setup

* Copy ```lib/gina/app-skeleton``` folder for a basic app structure into a new project.
* If using a MySql database backend, configure connection parameters under ```config/parameters.ini```.

## Application structure

* Application files must be located in the root ```src``` folder. Gina follows the classic MVC structure divided into controllers, models and views. PSR-4 namespace specification is used for autoloading.
* Configuration is done in the ```config``` folder. ```bootstrap.php``` is utilized for app initialization and ```parameters.ini``` contains global configs.
* Public webroot is located in the ```public``` folder, where the front controller ```index.php``` resides. CSS, scripts, and other static assets can be put in this directory.

### Controllers
Gina will search for controllers in the ```src/Controller``` folder. A controller's filename must end with ```Controller``` for it to be properly located by the autoloader.
Controllers must extend the base framework controller class ```Gina\Controller``` and be under the ```App\Controller``` namespace.
Request can be handled by creating actions as instance methods, which will be called by the dispatcher when a request is received.
If actions return an scalar value (e.g. a number or rendered HTML), it will be used as the response body. If a ```Gina\Response``` object is returned, that will be send as response.

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
Gina will search for views under the ```src/Views``` folder, which must have a ```.twig``` extension. Views have the full Twig functionality, which means they can use the complete Twig syntax, extend other views, include snippets, etc.

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

To render a view and return its result from a controller action, the controller auxiliary method ```render()``` is provided. It accepts the view name and the required context variables for its rendering. The view will be searched using the controller name as a subfolder. If the view name is omitted, the action name will me used as the filename instead.

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

Gina includes a demo application that features a list of teams for the configured European football league and their basic data. It also allows selecting favorites for quick access.

App configuration must be done in config/parameters.ini, using the footbalData INI section. Required configuration parameters are:

| Parameter  | Type | Description |
| ------------- | ------------- | ------------- |
| authToken  | string  | API token provided by FootballData api  |
| leagueId  | number  | Target league ID  |

### Available endpoints

`GET /teams`: returns a full HTML view for the found league teams ordered by favorites and name
`POST /teams/add_favorite/{id}`: sets a team as favorite given its id
`POST /teams/remove_favorite/{id}`: unsets a team as favorite given its id


### Tests

Framework tests can be run using phpunit by executing `composer run gina-tests`. Composer development packages are required tests to work properly.

## TODOs

* Provide Gina as a composer package
* Add more tests and improve coverage
* Error handling
