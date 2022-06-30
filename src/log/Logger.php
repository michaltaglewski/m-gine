<?php

declare(strict_types=1);

namespace mgine\log;

/**
 * Logger
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 *
 * @property Stream $stream
 *
 * @method void alert(string $message)
 * @method void critical(string $message)
 * @method void debug(string $message)
 * @method void error(string $message)
 * @method void emergency(string $message)
 * @method void info(string $message)
 * @method void notice(string $message)
 * @method void warning(string $message)
 *
 */
class Logger extends \mgine\base\Logger
{
    const ALERT = 'ALERT';

    const CRITICAL = 'CRITICAL';

    const DEBUG = 'DEBUG';

    const ERROR = 'ERROR';

    const EMERGENCY = 'EMERGENCY';

    const INFO = 'INFO';

    const NOTICE = 'NOTICE';

    const WARNING = 'WARNING';

    /**
     * @param Stream $stream
     * @throws \mgine\base\InvalidConfigException
     */
    public function __construct(protected Stream $stream)
    {
        parent::__construct();
    }

    /**
     * @param string $type
     * @param string $message
     * @return void
     */
    public function log(string $type, string $message): void
    {
        $this->stream->save($type, $message);
    }
}