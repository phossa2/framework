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

use Phossa2\Middleware\Queue;
use Phossa2\Middleware\Middleware\Phossa2RouteMiddleware;
use Phossa2\Middleware\Middleware\Phossa2SessionMiddleware;

/**
 * middleware configs
 */
return [

    /***********************************************************
     *
     * common middleware configurations
     *
     ***********************************************************/

    // middleware queue classname
    'class' => Queue::getClassName(),

    // main middleware queue
    'queue.main' => [
        '${#middleware_session}',
        '${#middleware_dispatcher}',
    ],

    // session middleware class
    'session.class' => Phossa2SessionMiddleware::getClassName(),

    // router class
    'router.class' => Phossa2RouteMiddleware::getClassName(),

    /***********************************************************
     *
     * $mws = new Queue([
     *     new MiddlewareSession(),
     *     new MiddlewareDispatcher($dispatcher),
     * ]);
     *
     ***********************************************************/

    'di' => [
        // ${#middleware} main middleware queue
        'middleware' => [
            'class' => '${middleware.class}',
            'args'  => ['${middleware.queue.main}'],
        ],

        // ${#middleware_session}
        'middleware_session' => [
            'class' => '${middleware.session.class}',
            'args' => ['${#session}'], // from session.php
        ],

        // ${#middleware_dispatcher}
        'middleware_dispatcher' => [
            'class' => '${middleware.router.class}',
            'args' => ['${#dispatcher}'], // from route.php
        ],
    ],
];
