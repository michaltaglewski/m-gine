<?php
/**
 * This is the template for generating DB connection config file.
 */

echo "<?php\n";
?>

return [
    'class' => 'mgine\db\MysqlConnection',
    'dsn' => 'mysql:host=localhost;dbname=my_db_name',
    'username' => 'my_db_user',
    'password' => 'my_db_user_password',
    'charset' => 'utf8',
];