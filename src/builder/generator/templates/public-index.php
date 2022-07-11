<?php
/**
 * This is the template for generating a main index file
 */

echo "<?php\n";
?>

defined('DEBUG_MODE') or define('DEBUG_MODE', true);

require '../bootstrap.php';
require '../vendor/autoload.php';

App::$name = 'app';
App::$baseURL = 'https://' . $_SERVER['SERVER_NAME'];
App::autoload();

$config = require '../config/web.php';

(new mgine\web\Application($config))->run();