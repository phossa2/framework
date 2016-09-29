# phossa2/framework

**phossa2/framework** is a modern PHP framework with middleware, dependency
injection.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with [PSR-1][PSR-1],
[PSR-2][PSR-2], [PSR-3][PSR-3], [PSR-4][PSR-4], and the proposed [PSR-5][PSR-5].

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-3]: http://www.php-fig.org/psr/psr-3/ "PSR-3: Logger Interface"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"
[PSR-5]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md "PSR-5: PHPDoc"

Installation
---
Install via the `composer` utility.

```bash
composer require "phossa2/framework"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa2/framework": "*"
    }
}
```

Directory structure
---

- server based installation

  ```
  |--- .env
  |
  +--- project/
        |
        |--- app/
        |
        |--- plugin/
        |
        |--- public/
        |      |--- asset/
        |      |--- index.php
        |
        |--- runtime/
        |      |--- local/
        |      |      |--- cache
        |      |      +--- session
        |      |
        |      |--- log/
        |      +--- upload/
        |
        |--- system/
        |      |--- console/
        |      |--- bootstrap.php
        |
        |--- vendor/
               |--- bower/
               +--- npm/
  ```

- NFS based setup

  ```php
  |--- .env
  |
  |--- local
  |      |--- cache/
  |      |--- session/
  |
  |--- project/  NFS mounted shared code/asset
  |      |--- app/
  |      |--- plugin/
  |      |--- public/
  |      |--- system/
  |      |--- vendor/
  |
  +--- runtime/ NFS mounted shared runtime
         |--- log/host1
         |--- upload/
         +--- static/  generated static html files
  ```

- Solution

  - centralized logs, log dir `runtime/shared/log/host1`
  - code shared ?
  - different tempaltes ? skin

License
---

[MIT License](http://mit-license.org/)
