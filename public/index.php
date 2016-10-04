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
use Zend\Diactoros\Response;
use Zend\Diactoros\ServerRequestFactory;

/**
 *
 * Default public entry
 *
 * - load bootstrap file
 * - execute web related middleware queue
 *
 * @package Phossa2\Framework
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.1.0
 * @added   2.1.0 added
 */

// Load bootstrap file
require dirname(__DIR__) . '/system/bootstrap.php';

// execute the main middleware queue
$response = Service::middleware()->process(
    ServerRequestFactory::fromGlobals(),
    new Response()
);
