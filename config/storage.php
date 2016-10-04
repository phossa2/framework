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

use Phossa2\Storage\Storage;
use Phossa2\Storage\Filesystem;

return [
    /***********************************************************
     *
     * common storage configurations
     *
     ***********************************************************/

    // storage classname
    'class' => Storage::getClassName(),

    // filesystem classname
    'filesystem.class' => Filesystem::getClassName(),

    // project's realpath runtime dir
    'dir.runtime' => getenv('PHOSSA2_RUNTIME_DIR'),

    // project's realpath tmpdir
    'dir.tmpdir' => '${system.tmpdir}',

    // virtual storage dir
    'virtual.tmpdir' => '/tmp',

    /***********************************************************
     *
     * for DI container
     *
     * // instantiation
     * $storage = (new Storage(
     *     '/', new Filesystem(getenv('PHOSSA2_RUNTIME_DIR'))
     * ))->mount(
     *     '/tmp', new Filesystem('${system.tmpdir}')
     * );
     *
     ***********************************************************/

    'di' => [
        // ${#storage}
        'storage' => [
            'class' => '${storage.class}',

            // virtual '/'
            'args' => ['/', '${#filesystem_runtime}'],

            // virtual '/tmp'
            'methods' => [
                ['mount', ['${storage.virtual.tmpdir}', '${#filesystem_tmpdir}']]
            ]
        ],

        // ${#filesystem_runtime} to be mounted under '/'
        'filesystem_runtime' => [
            'class' => '${storage.filesystem.class}',
            'args'  => ['${storage.dir.runtime}'],
        ],

        // #{#filesystem_tmpdir} to be mounted under '/tmp'
        'filesystem_tmpdir' => [
            'class' => '${storage.filesystem.class}',
            'args'  => ['${storage.dir.tmpdir}'],
        ],
    ]
];
