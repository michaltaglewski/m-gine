<?php
/**
 * This is the template for generating a main web config file.
 */

echo "<?php\n";
?>

return [
    'language' => 'en',
    'charset' => 'utf-8',
    'components' => [
        'urlManager' => require 'url-manager.php',
        'db' => require 'db.php'
    ]
];