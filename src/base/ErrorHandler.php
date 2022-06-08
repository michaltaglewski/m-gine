<?php

namespace mgine\base;

/**
 * ErrorHandler @TODO
 */
class ErrorHandler extends Component {

    public function init()
    {
        if(!DEBUG_MODE){
            ini_set('display_errors', 0);
            ini_set('display_startup_errors', 1);
            error_reporting(0);
        }
    }
}