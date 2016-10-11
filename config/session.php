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

use Phossa2\Session\Session;
use Phossa2\Session\Driver\CookieDriver;
use Phossa2\Session\Handler\StorageHandler;
use Phossa2\Session\Generator\UuidGenerator;

/**
 * session settings
 */
return [
    /***********************************************************
     *
     * common session configurations
     *
     ***********************************************************/

    // session classname
    'class' => Session::getClassName(),

    // session name (cookie name)
    'name' => 'phossaSession',

    // ssession storage path
    'savepath' => '${storage.virtual.tmpdir}/session',

    // cookie settings
    'cookie.settings' => [
        'domain' => null,
        'path'   => '/',
        'ttl'    => 0,
        'secure' => false,
        'httponly' => true
    ],

    // storage handler class
    'handler.storage.class' => StorageHandler::getClassName(),

    // driver class
    'driver.cookie.class' => CookieDriver::getClassName(),

    // generator class
    'generator.uuid.class' => UuidGenerator::getClassName(),

    /***********************************************************
     *
     * $session = new Session(
     *     'phossaSession',
     *     new StorageHandler($storage, '/tmp/session'),
     *     new CookieDriver($settings),
     *     new UuidGenerator()
     * );
     *
     ***********************************************************/

    'di' => [
        // ${#session}
        'session' => [
            'class' => '${session.class}',
            'args'  => [
                '${session.name}',
                '${#session_storage_handler}',
                '${#session_cookie_driver}',
                '${#session_uuid_generator}'
            ],
        ],

        // ${#session_storage_driver}
        'session_storage_handler' => [
            'class' => '${session.handler.storage.class}',
            'args' => ['${#storage}', '${session.savepath}']
        ],

        // ${#session_cookie_driver}
        'session_cookie_driver' => [
            'class' => '${session.driver.cookie.class}',
            'args'  => ['${session.cookie.settings}'],
        ],

        // ${#session_uuid_generator}
        'session_uuid_generator' => [
            'class' => '${session.generator.uuid.class}',
        ]
    ],
];
