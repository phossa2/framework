<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa2\Framework
 * @copyright Copyright (c) 2016 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

use Phossa2\Di\Service;
use Phossa2\Di\Container;
use Phossa2\Config\Config;
use Phossa2\Env\Environment;
use Phossa2\Shared\Message\Message;
use Phossa2\Config\Loader\ConfigFileLoader;
use Phossa2\Shared\Message\Loader\LanguageLoader;

/**
 *
 * System wide bootstrap file
 *
 * **DO NOT CHANGE THIS FILE**
 *
 * - set main project directory
 * - set vendor directory
 * - start autoloading
 * - load other environments from host-specific '.env' file
 * - start configure & DI container
 *
 * @package Phossa2\Framework
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.0
 * @added   2.1.0 added
 */

/********************************************************************
 *
 * Directory settings
 *
 ********************************************************************/

// system dir
putenv(sprintf('PHOSSA2_SYSTEM_DIR="%s"', __DIR__));

// project main dir
putenv(sprintf('PHOSSA2_PROJECT_DIR="%s"', dirname(__DIR__)));

// vendor dir
putenv(sprintf('PHOSSA2_VENDOR_DIR="%s"', getenv('PHOSSA2_PROJECT_DIR') . '/vendor'));

/********************************************************************
 *
 * Autoloading & other environments
 *
 ********************************************************************/

// load autoloader from vendor directory
require getenv('PHOSSA2_VENDOR_DIR') . '/autoload.php';

// load other environment values from '.env' file
(new Environment())->load(dirname(getenv('PHOSSA2_PROJECT_DIR')) . '/.env');

// set timezone. TZ was set in '.env' file
date_default_timezone_set(getenv('TZ'));

// set message language. LANG was set in '.env' file
if ('en_' !== substr(getenv('LANG'), 0, 3)) {
    Message::setLoader(new LanguageLoader(getenv('LANG')));
}

/********************************************************************
 *
 * Start configure & DI container
 *
 * - container is available as Service::container()
 * - config is available as Service::config()
 *
 ********************************************************************/

Service::setContainer(new Container(new Config(new ConfigFileLoader(
    getenv('PHOSSA2_CONFIG_DIR'),   // was set in '.env'
    getenv('PHOSSA2_ENV')           // was set in '.env'
))));
