<?php

namespace mgine\console;

use mgine\base\InvalidActionException;
use mgine\base\InvalidRouteException;
use mgine\base\MissingArgumentException;

/**
 * Response
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Response extends \mgine\base\Response
{
    /**
     * @return void
     */
    public function send(): void
    {
        $this->statusCode = 1;

        parent::send();
    }

    /**
     * @param \Throwable $ex
     * @return void
     * @throws \Throwable
     */
    public function sendError(\Throwable $ex)
    {
        parent::sendError($ex);

        try {
            throw $ex;
        } catch (InvalidActionException | InvalidRouteException | UnknownCommandException| MissingArgumentException $ex) {
            echo $ex->getMessage();die;
        }
    }
}