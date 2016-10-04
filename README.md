# phossa2/framework

**phossa2/framework** is a modern PHP framework built-upon configuration,
dependency injection and middlewares.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with
[PSR-1][PSR-1], [PSR-2][PSR-2], [PSR-3][PSR-3], [PSR-4][PSR-4], and other
proposed PSR standards.

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-3]: http://www.php-fig.org/psr/psr-3/ "PSR-3: Logger Interface"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"
[phossa2/config]: https://github.com/phossa2/config "phossa2/config"
[phossa2/di]: https://github.com/phossa2/di "phossa2/di"
[phossa2/env]: https://github.com/phossa2/di "phossa2/env"
[phossa2/middleware]: https://github.com/phossa2/middleware "phossa2/middleware"
[phossa2/route]: https://github.com/phossa2/route "phossa2/route"
[phossa2/app-skeleton]: https://github.com/phossa2/app-skeleton "phossa2/app-skeleton"

Create the project
---
Install via the `composer` utility.

```bash
# cd installation_dir/
# composer create-project phossa2/framework PROJECT
```

<a name="dir"></a>Directory structure
---

**phossa2/framework** is delivered with [the single server installation](#single)
directory structure. It also can be restructured to fit different requirements
by modifying directory settings in the [`.env`](#env) file.

- <a name="single"></a>Single server installation

  Default framework distribution is for a single server installation.

  ```
  |--- .env                             the environment file
  +--- PROJECT/                         the project directory
        |--- phossa2                    the utility script
        |--- app/                       app installation dir
        |--- config/                    where all the config files located
        |--- plugin/                    where all the plugins installed
        |--- public/                    where public stuff located
        |      |--- asset/
        |      +--- index.php           single public entry
        |--- runtime/                   runtime related stuff
        |      |--- local/              host-specific storage
        |      |      |--- cache
        |      |      +--- session
        |      +--- log/                log file directory
        |--- system/                    system files
        |      +--- bootstrap.php       bootstrap file
        +--- vendor/                    third-party libs
  ```

- Multiple servers installation

  The framework can also be installed across multiple servers to provide
  capabilities such as load balancing, central app managment (NFS mounted) etc.

  ```php
  |--- .env                             host-specific environments
  |--- local                            host-specific local storage
  |     |--- cache/
  |     +--- session/
  |
  |--- PROJECT/                         shared among servers (NFS mounted)
  |     |--- phossa2
  |     |--- app/
  |     |--- config/
  |     |--- plugin/
  |     |--- public/
  |     |--- system/
  |     +--- vendor/
  |
  +--- runtime/                         shared runtime stuff (NFS mounted)
        |--- log/host1                  host-specific log dir
        |--- upload/                    upload dir
        +--- static/                    generated static html files
  ```

<a name="execution"></a>Execution path
---

- Application execution path

  1. <a name="index"></a>`public/index.php`

    Single app entry point. Load [`system/bootstrap.php`](#bootstrap) file and
    then process the app middleware queue defined in `config/middleware.php`

    ```php
    <?php
    // public/index.php

    // Load bootstrap file
    require dirname(__DIR__) . '/system/bootstrap.php';

    // execute the main middleware queue
    $response = Service::middleware()->process(
        ServerRequestFactory::fromGlobals(),
        new Response()
    );
    ```

  2. <a name="bootstrap"></a>`system/bootstrap.php`

    Required by [`public/index.php`](#index) or [phossa2](#util) utility script.
    Bootstrap all required stuff, including

    - set basic environments.

    - start autloading.

    - load other environments from [`.env`](#env) file.

    - start [$config](#config) and [$container](#di) which read configs from
      the `config` directory.

  3. <a name="env"></a>`.env`

    Environment file installled at one level upper of `PROJECT` directory. This
    file is host specific and may differ on different servers.

    See [phossa2/env][phossa2/env] and [phossa2/config][phossa2/config] for
    detail.

    - To change app environment

      Change the value of `PHOSSA2_ENV` to implement different servers, such as
      production server, dev server or staging servers.

    - To restructure the framework

      By changing directory settings in this file, user may be able to
      restructure the framework.

  4. start `$config` and `$container`

    - <a name="config"></a>`configure`

      Configurations are grouped into files and located in the directory
      `config/`.

      See [phossa2/config][phossa2/config] for detail.

    - <a name="di"></a>`container`

      [phossa2/di][phossa2/di] provides a PSR-11 compliant container
      implementation built upon [phossa2/config][phossa2/config].

      Container objects are configured in `config/di.php` or scattered in the
      'di' section of different files such as `config/db.php`.

      A service locator `Phossa2\Di\Service` is also provided.

      The container object is available as `Service::container()`. The
      configuration object is available as `Service::config()`.

      ```php
      use Phossa2\Di\Service;

      $config = Service::config();
      $container = Service::container();

      // get the db configuration array
      $db_conf = $config->get('db');

      // get the db object
      $db = $container->get('db');

      // or get from locator
      $db = Service::db();
      ```

  5. Process the app middleware queue

    Middlewares are defined in `config/middleware.php`.

- Console script execution path

  1. <a name="util"></a>`phossa2` utility script

    Single utility entry point. Load [`system/bootstrap.php`](#bootstrap) file
    and then process console middleware queue.

  2. Console middleware queue

    Console middleware queue is configured in `config/middleware.php`. It will
    look for controller/action pairs in the 'system/Console/' and 'app/Console/'
    directories for specific actions.

<a name="driven"></a>Configuration driven framework
---

**phossa2/framework** is a configruation driven framework. Most of the objects,
utilities are defined in config files under the `config/` directory. Objects are
generated automatically by the DI container and avaiable via
`Service::objectId()`.

For example, the database connection is defined in `config/db.php` as follows,

```php
use Phossa2\Db\Driver\Pdo\Driver as Pdo_Driver;

// config/db.php
return [
    // PDO driver classname
    'driver.pdo.class' => Pdo_Driver::getClassName(),

    // connect conf
    'driver.pdo.conf' => [
        'dsn' => 'mysql:dbname=test;host=127.0.0.1;charset=utf8',
    ],

    // container section
    'di' => [
        // ${#db}
        'db' => [
            'class' => '${db.driver.pdo.class}',
            'args' => ['${db.driver.pdo.conf}'],
        ],
    ],
];
```

The last section `di` equals to defining a `$db` in the container

```php
$db = new Pdo_Driver(['dsn' => '...']);
$container->set('db', $db);
```

To utilize the database connection in your code, you may either inject it in
another container object configuration file.

```php
// config/article.php
return [
    'class' => MyArticle::getClassName();

    // ${#article} in container
    'di' => [
        'article' => [
            'class' => '${article.class}',
            'args' => ['${#db}'] // inject $db
        ]
    ]
];
```

Or use it explicitly with the service locator,

```php
use Phossa2\Di\Service;

// get db
$db = Service::db();

$article = new MyArticle($db);
```

Complicated db configurations can be found in `config/production/db.php` which
uses a db connection manager with a pool of a read-write connection and couple
of read-only connections.

```php
use Phossa2\Db\Manager as Db_Manager;
use Phossa2\Db\Driver\Pdo\Driver as Pdo_Driver;

// config/production/db.php
return [
    // driver manager
    'manager.class' => Db_Manager::getClassName(),

    // more connect confs
    'driver.pdo.conf2' => [
        'dsn' => 'mysql:dbname=test;host=127.0.0.2;charset=utf8',
    ],

    'driver.pdo.conf3' => [
        'dsn' => 'mysql:dbname=test;host=127.0.0.3;charset=utf8',
    ],

    // callback to get a db from db manager with tagname
    'callable.getdriver' => function($dbm, $tag) {
        return $dbm->getDriver($tag);
    },

    // container section
    'di' => [
        // ${#dbm}
        'dbm' => [
            'class' => '${db.manager.class}',
            'methods' => [
                ['addDriver', ['${#db1}', 1]],
                ['addDriver', ['${#db2}', 5]],
                ['addDriver', ['${#db3}', 5]],
            ],
        ],

        // ${#db1}
        'db1' => [
            'class' => '${db.driver.pdo.class}',
            'args' => ['${db.driver.pdo.conf}'],
            'methods' => [
                ['addTag', ['RW']]
            ]
        ],

        // ${#db2}
        'db2' => [
            'class' => '${db.driver.pdo.class}',
            'args' => ['${db.driver.pdo.conf2}'],
            'methods' => [
                ['addTag', ['RO']]
            ]
        ],

        // ${#db3}
        'db3' => [
            'class' => '${db.driver.pdo.class}',
            'args' => ['${db.driver.pdo.conf3}'],
            'methods' => [
                ['addTag', ['RO']]
            ]
        ],

        // ${#dbro} read only driver (round-robin)
        'dbro' => [
            'class' => '${db.callable.getdriver}',
            'args' => ['${#dbm}', 'RO'],
            'scope' => Container::SCOPE_SINGLE,
        ],

        // ${#dbrw} readwrite driver (round-robin if any)
        'dbrw' => [
            'class' => '${db.callable.getdriver}',
            'args' => ['${#dbm}', 'RW'],
            'scope' => Container::SCOPE_SINGLE,
        ],

        // ${#db} either RW or RO
        'db' => [
            'class' => '${db.callable.getdriver}',
            'args' => ['${#dbm}', ''],
            'scope' => Container::SCOPE_SINGLE,
        ],
    ],
];

```

The previous configruations equal to the following code,

```php
// different db connectors
$db1 = (new Pdo_Driver($conf ))->addTag('RW');
$db2 = (new Pdo_Driver($conf2))->addTag('RO');
$db3 = (new Pdo_Driver($conf3))->addTag('RO');

// db manager
$dbm = (new Db\Manager\Manager())
    ->addDriver($db1, 1)    // readwrite, factor 1
    ->addDriver($db2, 5)    // read_only, factor 5
    ->addDriver($db3, 5)    // read_only, factor 5

// get a readonly connection (round robin)
$dbro = $dbm->getDriver('RO');

// get a readwrite connection
$dbrw = $dbm->getDriver('RW');

// get a db connection (either RW or RO)
$db = $dbm->getDriver('');
```

<a name="mw"></a>Middleware driven framework
---

**phossa2/framework** is not a pure MVC structure but a middleware-centric
framework. For middleware runner implementation, please see
[phossa2/middleware][phossa2/middleware].

Different middleware queues are defined in `config/middleware.php`.

```php

```

<a name="route"></a>Routing
---

Routes are handled by `Phossa2\Middleware\Middleware\Phossa2RouteMiddleware`.
See [phossa2/middleware][phossa2/middleware], [phossa2/route][phossa2/route] for
detail.

Route dispatcher `$dispatcher` is defined in `config/route.php`. It will be
injected into the main middleware queue when processing reaches the
`Phossa2RouteMiddleware`.

Different routes should be defined in `config/route/*.php` files. For example,

```php
// route/50_admin.php
$ns = "App\\Controller\\"; // controller namespace

return [
    'prefix' => '/admin/',
    'routes' => [
        // resolve to ['App\Controller\AdminController', 'defaultAction']
        '/admin/{action:xd}/{id:d}' => [
            'GET,POST',                     // http methods,
            [$ns . 'Admin', 'default'],     // handler,
            ['id' => 1]                     // default values
        ],
    ]
];
```

**Note**: `50_` in the route filename is for sorting purpose.

<a name="app"></a>Application programming
---

- Do it a simple way

  You may just start programming in the `app/` directory where
  [phossa2/app-skeleton][phossa2/app-skeleton] is already installed during the
  project creation.

- Do it a nice way

  1. Git clone [app-skeleton](https://github.com/phossa2/app-skeleton) to your
    local directory.

  2. Add your own stuff to the cloned application skeleton.

  3. Remove the initially installed `app-skeleton` from the project

    ```bash
    # cd PROJECT/
    # composer remove phossa2/app-skeleton
    ```

  4. Install your app into the `PROJECT`

    - If your app is on the git

      Add the following lines to your `PROJECT/composer.json`

      ```
      "repositories": [
          {
              "type":"package",
              "package": {
                  "name": "my/app",
                  "version": "master",
                  "source": {
                      "url": "https://github.com/my/app.git",
                      "type": "git",
                      "reference":"master"
                  }
              }
          }
      ]
      ```

    - If your app is just a zip.

      Add  the following lines to your `PROJECT/composer.json`

      ```
      "repositories": [
          {
              "type": "package",
              "package": {
                  "name": "my/app",
                  "version": "master",
                  "dist": {
                      "type": "zip",
                      "url": "http://mydomain.com/downloads/app-1.4.zip",
                      "reference": "master"
                  }
              }
          }
      ]
      ```

    - then install the app via `composer require` or `composer update`

      ```bash
      # cd PROJECT/
      # composer require my/app
      ```

---

License
---

[MIT License](http://mit-license.org/)
