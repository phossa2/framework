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

use Phossa2\Route\Dispatcher;
use Phossa2\Route\Collector\Collector;
use Phossa2\Route\Resolver\ResolverSimple;

/**
 * this config file reads all the routes from route/ directory
 */
return [

    /***********************************************************
     *
     * common dispatcher configurations
     *
     ***********************************************************/

    // dispatcher classname
    'class' => Dispatcher::getClassName(),

    // resolver classname
    'resolver.class' => ResolverSimple::getClassName(),

    // collectors, read all configs from 'route/' dir
    'collectors' => function() {
        $result = [];
        foreach (glob(__DIR__ . '/route/*.php') as $file) {
            $rte  = include_once $file;
            $coll = new Collector();
            $coll->loadRoutes($rte['routes']);
            if (isset($rte['prefix'])) {
                $coll->setPathPrefix($rte['prefix']);
            }
            $result[] = $coll;
        }
        return $result;
    },

    /***********************************************************
     *
     * $dispatcher = (new Dispatcher(null, new ResolverSimple()))
     *     ->addCollectors(...);
     *
     ***********************************************************/

    'di' => [
        // ${#dispatcher}
        'dispatcher' => [
            'class' => '${route.class}',
            'args' => [null, '${#route_resolver}'],
            'methods' => [
                ['addCollectors', ['${route.collectors}']],
            ]
        ],

        // ${#route_resolver}
        'route_resolver' => '${route.resolver.class}',
    ],
];
