<?php
/**
 * This is the template for generating framework 'Welcome' home\index view file.
 */

/* @var $this \mgine\builder\generator\ViewGenerator */

echo "<?php\n";
?>

/**
* @var array $rootPackage
* @var array $installedPackages
* @var mgine\web\View $this
*/

$rootPackageURL = $rootPackage['source']['url'] ?? 'https://github.com/michaltaglewski/m-gine';
$version = ($rootPackage['version'] ? "Version: {$rootPackage['version']}": null);

$this->title = 'Welcome to the M-Gine framework!';
?>
<h1><?= "<?= \$this->title ?>" ?></h1>
<p class="fs-5 col-md-8"><?= "<?= \$version ?>" ?></p>

<div class="mb-5">
    <a href="<?= "<?= \$rootPackageURL ?>" ?>" class="btn btn-success btn-lg px-4" target="_blank">
        <i class="bi bi-github"></i> Get started
    </a>
</div>

<hr class="col-3 col-md-2 mb-5">

<div class="row g-5">
    <div class="col-md-6">
        <h2>Installed packages</h2>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, delectus deleniti doloribus eligendi
            hic laudantium neque nesciunt odit! Eius enim libero nobis omnis provident quod tenetur. Asperiores dolorem
            earum neque!</p>

        <ul class="icon-list ps-0">
            <?= "<?php foreach (\$installedPackages as \$name => \$item): ?>\n" ?>
                <li class="d-flex align-items-start mb-1">
                    <?= "<?php \$v = (isset(\$item['version']) ? \" <strong>{\$item['version']}</strong>\": null); ?>\n" ?>
                    <a href="<?= "<?= \$item['source']['url'] ?? '#' ?>" ?>" rel="noopener" target="_blank"><?= "<?= \$name . \$v ?>" ?></a>
                </li>
            <?= "<?php endforeach ?>\n" ?>
        </ul>
    </div>

    <div class="col-md-6">
        <h2>Heading</h2>

        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, delectus deleniti doloribus eligendi
            hic laudantium neque nesciunt odit! Eius enim libero nobis omnis provident quod tenetur. Asperiores dolorem
            earum neque!</p>
    </div>
</div>