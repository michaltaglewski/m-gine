<?php
/**
 * This is the template for generating a view file.
 */

/* @var $this \mgine\builder\generator\ViewGenerator */

echo "<?php\n";
?>

/**
* @var mgine\web\View $this
*/

<?php foreach ($this->getViewProperties() as $name => $value): ?>
$this-><?= $name ?> = <?= (is_string($value) ? "'$value'" : $value) ?>;
<?php endforeach; ?>

?>
<h1><?= "<?= \$this->title ?>" ?></h1>