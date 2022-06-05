<?php
/**
 * This is the template for generating a main index file
 */

echo "<?php\n";
?>

defined('DEBUG_MODE') or define('DEBUG_MODE', true);

require '../vendor/autoload.php';

$config = require '../config/web.php';

(new mgine\web\Application($config))->run();