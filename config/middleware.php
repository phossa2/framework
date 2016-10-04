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

return [

    /***********************************************************
     *
     * common middleware configurations
     *
     ***********************************************************/

    // middleware queue classname
    'class' => Queue::getClassName(),

    // main middleware queue
    'queue' => [
        '${#middleware_session}',
        '${#middleware_dispatcher}',
    ],

    /***********************************************************
     *
     * $mws = new Queue([
     *     new MiddlewareSession(),
     *     new MiddlewareDispatcher($dispatcher),
     * ]);
     *
     ***********************************************************/

    'di' => [
        // ${#middleware}
        'middleware' => [
            'class' => '${middleware.class}',
            'args'  => ['${middleware.queue}'],
        ],

        // ${#middleware_session}
        'middleware_session' => '',

        // ${#middleware_dispatcher}
        'middleware_dispatcher' => [
            'class' => Phossa2RouteMiddleware::getClassName(),
            'args' => ['${#dispatcher}'], // from route.php
        ],
    ],
];
