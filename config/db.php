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

use Phossa2\Di\Container;
use Phossa2\Db\Driver\Pdo\Driver as Pdo_Driver;

/**
 * database configurations.
 */
return [

    /***********************************************************
     *
     * common db configurations
     *
     ***********************************************************/

    // PDO driver classname
    'driver.pdo.class' => Pdo_Driver::getClassName(),

    // connect confs
    'driver.pdo.conf' => [
        'dsn' => 'mysql:dbname=test;host=127.0.0.1;charset=utf8',
    ],

    /***********************************************************
     *
     * DI container
     *
     * $db = new Db\Pdo\Driver($conf);
     *
     ***********************************************************/

    'di' => [
        // ${#db}
        'db' => [
            'class' => '${db.driver.pdo.class}',
            'args' => ['${db.driver.pdo.conf}'],
        ],
    ],
];
