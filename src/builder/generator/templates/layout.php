<?php
/**
 * This is the template for generating the main layout file.
 */

/* @var $this \mgine\builder\generator\ViewGenerator */

echo "<?php\n";
?>

/**
* @var string $content
* @var mgine\web\View $this
*/
?>
<!doctype html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= "<?= \$this->title ?>" ?></title>
</head>
<body>
<?= "<?= \$content ?>" ?>
</body>
</html>