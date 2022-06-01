<?php
/**
 * This is the template for generating a controller class file.
 */

use mgine\helpers\InflectorHelper;

/* @var $this \mgine\builder\generator\ControllerGenerator */

echo "<?php\n";
?>

namespace <?= $this->getClassNamespace() ?>;

class <?= $this->className ?> <?= $this->getClassExtends() . "\n" ?>
{
<?php foreach ($this->getActionIDs() as $id): ?>
    public function <?= InflectorHelper::camelCase("action-$id") ?>()
    {
        return $this->render('<?= $id ?>');
    }

<?php endforeach; ?>
}
