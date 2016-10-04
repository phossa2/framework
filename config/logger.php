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

use Phossa2\Logger\Logger;
use Phossa2\Logger\Processor\MemoryProcessor;
use Phossa2\Logger\Processor\InterpolateProcessor;
use Phossa2\Logger\Handler\SyslogHandler;

return [
    /***********************************************************
     *
     * common logger configurations
     *
     ***********************************************************/

    // logger classname
    'class' => Logger::getClassName(),

    // default channel name
    'channel.name' => 'app',

    /***********************************************************
     *
     * for DI container
     *
     * $logger = (new Logger('app'))
     *     ->addProcessor(new MemoryProcessor())
     *     ->addProcessor(new InterpolateProcessor(), '*', -100)
     *     ->addHandler('notice', new SyslogHandler(), '*')
     *
     ***********************************************************/

    'di' => [
        // ${#logger}
        'logger' => [
            'class' => '${logger.class}',
            'args' => ['${logger.channel.name}'],
            'methods' => [
                ['addProcessor', ['${#logger_processor_memory}']],
                ['addProcessor', ['${#logger_processor_inter}', '*', -100]],
                ['addHandler', ['notice', '${#logger_handler_syslog}', '*']]
            ]
        ],

        // ${#logger_processor_memory}
        'logger_processor_memory' => MemoryProcessor::getClassName(),

        // ${#logger_processor_inter}
        'logger_processor_inter' => InterpolateProcessor::getClassName(),

        // ${#logger_handler_syslog}
        'logger_handler_syslog' => SyslogHandler::getClassName(),
    ],
];
