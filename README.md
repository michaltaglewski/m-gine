
<p align="center">
  <img src="src/builder/assets/img/logo-120.png">
</p>

# M-Gine Framework

## Requirements

* PHP >= 8.0

## Installing via Composer

Create the composer.json file as follows:

```json
{
    "require-dev": {
        "michaltaglewski/m-gine": "dev-main"
    }
}
```

Run the composer installer:

```bash
php composer.phar install
```

Then initiate your first project. See the [M-gine commands](#m-gine-commands) section below.

## M-gine commands

Right after the composer installation is completed, you are able to use **M-Gine** commands tool.

Execute binary file located in the vendor directory, like following:

```bash
./vendor/bin/mgine help
```

This command should display something similar to:

```bash
$ ./vendor/bin/mgine help

M-gine commands (0.0.1)

Available commands:
  create-project       Creates a new project. Usage: project-create [name]
  init-project         Initializes a new project in current directory. Usage: init-project
  create-controller    Creates a Controller. Usage: create-controller [name] [namespace]
```

Initiate a project:

```bash
$ ./vendor/bin/mgine init-project
```

## Project Directory Structure

```
config/             framework configuration
controllers/        MVC controllers directory
public/             web public folder (includes index.php)
models/             MVC models directory
tests/              tests of the core framework code
views/              MVC views directory
```

## Configuration

Configure your main web configuration file **config/web.php**:
```php
<?php

return [
    'basePath' => dirname(__DIR__),
    'language' => 'en',
    'charset' => 'utf-8',
    'components' => [
        'urlManager' => require 'urlManager.php',
        'db' => require 'db.php'
    ]
];
```

### Url Manager component 

**config/urlManager.php**:
```php
<?php

return [
    'class' => 'mgine\web\UrlManager',
    'defaultRoute' => 'home/index',
    'rules' => [
        'rules' => [
            /**
             * Add your URL rules here
             * '/' => 'home/index',
             * '/about' => 'home/about',
             * '/contact' => 'home/contact',
             */
        ]
    ]
];
```

### Database connection

**config/db.php**:
```php
<?php

return [
    'class' => 'mgine\db\MysqlConnection',
    'dsn' => 'mysql:host=localhost;dbname=my_db_name',
    'username' => 'my_db_user',
    'password' => 'my_db_user_password',
    'charset' => 'utf8',
];
```