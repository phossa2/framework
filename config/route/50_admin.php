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

/**
 * routes for /admin/
 */
$ns = "App\\Controller\\"; // controller namespace

return [
    'prefix' => '/admin/',
    'routes' => [
        // resolve to ['App\Controller\AdminController', 'defaultAction']
        '/admin/{action:xd}/{id:d}' => [
            'GET,POST',                     // http methods,
            [$ns . 'Admin', 'default'],     // handler,
            ['id' => 1]                     // default values
        ],
    ]
];
