<?php
/**
 * This is the template for generating a view file.
 */

/* @var $this \mgine\builder\generator\ViewGenerator */

echo "<?php\n";
?>

/**
* @var mgine\web\View $this
* @var array $installedPackages
*/

$this->title = 'Welcome to the M-Gine framework!';

$installedPackages = [
    'firebase/php-jwt' => [
        'src' => 'https://github.com/firebase/php-jwt',
        'v' => '^6.1'
    ],
    'psr/container' => [
        'src' => 'https://packagist.org/packages/psr/container',
        'v' => '^2.0^2.0'
    ],
    'twbs/bootstrap' => [
        'src' => 'https://github.com/twbs/bootstrap',
        'v' => '5.0.2'
    ],
    'twbs/bootstrap-icons' => [
        'src' => 'https://github.com/twbs/icons',
        'v' => '1.8.3'
    ],
];

?>
<h1><?= "<?= \$this->title ?>" ?></h1>
<p class="fs-5 col-md-8">Quickly and easily get started with Bootstrap's compiled, production-ready files with this barebones example featuring some basic HTML and helpful links. Download all our examples to get started.</p>

<div class="mb-5">
    <a href="https://github.com/michaltaglewski/m-gine" class="btn btn-success btn-lg px-4" target="_blank">
        <i class="bi bi-github"></i> Get started with M-Gine
    </a>
</div>

<hr class="col-3 col-md-2 mb-5">

<div class="row g-5">
    <div class="col-md-6">
        <h2>Installed packages</h2>
        <p>Ready to beyond the starter template? Check out these open source projects that you can quickly duplicate to a new GitHub repository.</p>
        <ul class="icon-list ps-0">
            <?= "<?php foreach (\$installedPackages as \$name => \$item): ?>\n" ?>
                <li class="d-flex align-items-start mb-1">
                    <a href="<?= "<?= \$item['src'] ?>" ?>" rel="noopener" target="_blank"><?= "<?= \$name ?>" ?></a>
                </li>
            <?= "<?php endforeach ?>\n" ?>
        </ul>
    </div>
</div>