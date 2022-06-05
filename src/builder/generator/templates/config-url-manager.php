<?php
/**
 * This is the template for generating Url Manager config file.
 */

echo "<?php\n";
?>

return [
    'class' => 'mgine\web\UrlManager',
    'defaultRoute' => 'home/index',
    'rules' => [
        /**
         * Add your URL rules here
         * '/' => 'home/index',
         * '/about' => 'home/about',
         * '/contact' => 'home/contact',
         */
    ]
];