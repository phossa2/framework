# phossa2/framework

**phossa2/framework** is a modern PHP framework built-upon middleware and
dependency injection.

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
[phossa2/app-skeleton]: https://github.com/phossa2/app-skeleton "phossa2/app-skeleton"

Installation
---
Install via the `composer` utility.

```bash
# cd installation_dir/
# composer create-project phossa2/framework PROJECT
```

<a name="dir"></a>Directory structures
---

**phossa2/framework** can be restructured to fit different requirements by
modifying directory settings in the [`.env`](#env) file.

- Single server installation

  the distributed version is for a single server installation.

  ```
  |--- .env                             the environment file
  +--- PROJECT/                         the project directory
        |--- phossa2                    the utility
        |--- app/                       app installation dir
        |--- config/                    where all the config files located
        |--- plugin/                    where all the plugins installed
        |--- public/                    where public stuff located
        |      |--- asset/
        |      |--- index.php           single public entry
        |--- runtime/                   runtime related stuff
        |      |--- local/              host-specific storage
        |      |      |--- cache
        |      |      +--- session
        |      +--- log/                log file directory
        |--- system/                    system files
        |      +--- bootstrap.php       bootstrap file
        +--- vendor/                    third-party libs located
  ```

- Multiple servers installation

  The framework can be installed across multiple servers to provide capabilities
  such as load balancing, central app managment (NFS mounted) etc.

  ```php
  |--- .env                             host-specific environments
  |--- local                            host-specific local storage
  |     |--- cache/
  |     +--- session/
  |
  |--- PROJECT/                         shared among servers (NFS mountable)
  |     |--- phossa2
  |     |--- app/
  |     |--- config/
  |     |--- plugin/
  |     |--- public/
  |     |--- system/
  |     +--- vendor/
  |
  +--- runtime/                         shared runtime stuff (NFS mountable)
        |--- log/host1                  host-specific log dir
        |--- upload/                    upload dir
        +--- static/                    generated static html files
  ```

<a name="execution"></a>Execution path
---

- Application execution path

  1. <a name="index"></a>`public/index.php`

    Single app entry point. Load [`system/bootstrap.php`](#bootstrap) file and
    then process the app middleware queue.

  2. <a name="bootstrap"></a>`system/bootstrap.php`

    Required by [`public/index.php`](#index) or [phossa2](#util) file.
    Bootstrap all required stuff, including

    - set basic environments.

    - start autloading.

    - load other environments from [`.env`](#env) file.

    - start [configure](#config) and [DI container](#di)

  3. <a name="env"></a>`.env`

    Environment file installled at one level upper of `PROJECT` directory. This
    file is host-specific and may differ on different servers.

    See [phossa2/env][phossa2/env] and [phossa2/config][phossa2/config] for
    detail.

    - Change app environment

      Change the value `PHOSSA2_ENV` to implement different servers, such as
      production server, dev server or staging servers.

    - Restructure the framework

      By changing directory settings in this file, user may be able to
      restructure the framework.

  4. start config and DI container

    - <a name="config"></a>`configure`

      Configurations are grouped into files and located in one directory of the
      framework `config/`.

      See [phossa2/config][phossa2/config] for detail.

    - <a name="di"></a>`container`

      [phossa2/di][phossa2/di] provides a PSR-11 compliant container
      implementation built upon [phossa2/config][phossa2/config].

      Container objects are configured in `config/di.php` or scattered in the
      'di' section of different files such as `config/cache.php`.

      For simplicity, a service locator `Phossa2\Di\Service` is provided.

      The container is available as `Service::container()`. The configuration
      is available as `Service::config()`.

      ```php
      // db is defined in config/db.php

      // get the db config
      $db_conf = Service::config()->get('db');

      // get the db object
      $db = Service::container()->get('db');

      // or
      $db = Service::db();
      ```

  5. Process the app middleware queue

    Middlewares are defined in `config/middleware`.

- Console script execution path

  1. <a name="util"></a>`phossa2`

    Single utility entry point. Load [`system/bootstrap.php`](#bootstrap) file
    and then process console middleware queue.

  2. Console middleware queue

    Console middleware queue is configured in `config/middleware.php`. It will
    look for controller/action pairs in the 'system/Console/' and 'app/Console/'
    for specific actions.

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

    - install the app via `composer require` or `composer update`

      ```bash
      # cd PROJECT/
      # composer require my/app
      ```

---

License
---

[MIT License](http://mit-license.org/)
