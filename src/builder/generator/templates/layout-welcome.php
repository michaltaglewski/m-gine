<?php
/**
 * This is the template for generating the main Home Welcome layout file.
 */

/* @var $this \mgine\builder\generator\ViewGenerator */

echo "<?php\n";
?>

use mgine\helpers\HtmlHelper;

/**
 * @var string $content
 * @var mgine\web\View $this
 */

?>
<!doctype html>
<html lang="<?= "<?= \$this->lang() ?>" ?>">
<head>
    <!-- Required meta tags -->
    <meta charset="<?= "<?= \$this->charset() ?>" ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="<?= "<?= \$this->baseUrl() ?>" ?>assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= "<?= \$this->baseUrl() ?>" ?>assets/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= "<?= \$this->baseUrl() ?>" ?>assets/css/starter-template.css">

    <?= "<?= HtmlHelper::csrfMetaTags() ?>" ?>

    <title><?= "<?= \$this->title ?>" ?></title>
    <link rel="icon" type="image/x-icon" href="<?= "<?= \$this->baseUrl() ?>" ?>favicon.ico">

</head>
<body>
<div class="col-lg-8 mx-auto p-3 py-md-5">
    <header class="d-flex align-items-center pb-3 mb-5 border-bottom">
        <a href="<?= "<?= \$this->baseUrl() ?>" ?>" class="d-flex align-items-center text-dark text-decoration-none">
            <img src="<?= "<?= \$this->baseUrl() ?>" ?>assets/img/logo.png" alt="logo.png" width="50px">
            <span class="fs-4">&nbsp;Gine</span>
        </a>
    </header>

    <main>
        <?= "<?= \$content ?>" ?>
    </main>
    <footer class="pt-5 my-5 text-muted border-top">
        Michal Taglewski <a href="mailto:mtaglewski.dev@gmail.com">mtaglewski.dev@gmail.com</a> · © 2022
    </footer>
</div>

<script src="<?= "<?= \$this->baseUrl() ?>" ?>assets/bootstrap/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<?= "<?php \$this->bodyEnd() ?>" ?>
</body>
</html>