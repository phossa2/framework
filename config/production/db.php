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
use Phossa2\Db\Manager as Db_Manager;
use Phossa2\Db\Driver\Pdo\Driver as Pdo_Driver;

/**
 * database configuration.
 *
 * WILL REPLACE configs in `config/db.php`
 */
return [

    /***********************************************************
     *
     * common db configurations
     *
     ***********************************************************/

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

    /***********************************************************
     *
     * $db1 = (new Pdo_Driver($conf ))->addTag('RW');
     * $db2 = (new Pdo_Driver($conf2))->addTag('RO');
     * $db3 = (new Pdo_Driver($conf3))->addTag('RO');
     *
     * $dbm = (new Db\Manager\Manager())
     *     ->addDriver($db1, 1)    // readwrite, factor 1
     *     ->addDriver($db2, 5)    // read_only, factor 5
     *     ->addDriver($db3, 5)    // read_only, factor 5
     *
     * // get a readonly driver
     * $dbro = $dbm->getDriver('RO');
     *
     * // get a readwrite driver
     * $dbrw = $dbm->getDriver('RW');
     *
     * // get a driver (whatever)
     * $db = $dbm->getDriver('');
     *
     ***********************************************************/

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
            'args' => ['${db.driver.pdo.conf1}'],
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

        // ${#db} whatever driver
        'db' => [
            'class' => '${db.callable.getdriver}',
            'args' => ['${#dbm}', ''],
            'scope' => Container::SCOPE_SINGLE,
        ],
    ],
];
