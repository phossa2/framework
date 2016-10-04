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

use Phossa2\Cache\CachePool;
use Phossa2\Cache\Driver\StorageDriver;
use Phossa2\Cache\Extension\DistributedExpiration;

/**
 * cache settings
 */
return [
    /***********************************************************
     *
     * common cache configurations
     *
     ***********************************************************/

    // cache classname
    'class' => CachePool::getClassName(),

    // cache driver classname
    'driver.class' => StorageDriver::getClassName(),

    // extension: DistributedExpiration
    'ext.distributed' => DistributedExpiration::getClassName(),

    /***********************************************************
     *
     * $cache = new CachePool(new StorageDriver(
     *     $storage, '/tmp/cache'
     * ));
     *
     ***********************************************************/

    'di' => [
        // ${#cache}
        'cache' => [
            'class' => '${cache.class}',
            'args'  => ['${#cache_driver}'],
            'methods' => [
                ['addExtension', ['${#cache_ext_dist}']],
            ]
        ],

        // ${#cache_driver}
        'cache_driver' => [
            'class' => '${cache.driver.class}',
            'args' => ['${#storage}', '${storage.virtual.tmpdir}/cache']
        ],

        // ${#cache_ext_dist}
        'cache_ext_dist' => [
            'class' => '${cache.ext.distributed}'
        ],
    ],
];
