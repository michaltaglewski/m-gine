<?php
/**
 * This is the template for generating framework 'Welcome' home controller class file.
 */

use mgine\helpers\InflectorHelper;

/* @var $this \mgine\builder\generator\ControllerGenerator */

echo "<?php\n";
?>

namespace <?= $this->getClassNamespace() ?>;

class <?= $this->className ?> <?= $this->getClassExtends() . "\n" ?>
{
<?php foreach ($this->getActionIDs() as $id): ?>
    public function <?= InflectorHelper::idToCamelCase("action-$id") ?>()
    {
    <?php if($id === 'index'): ?>

        $rootPackage = \mgine\base\Composer::getRootPackage();
        $installedPackages = \mgine\base\Composer::getInstalledPackages();

        return $this->render('<?= $id ?>', [
            'rootPackage' => $rootPackage,
            'installedPackages' => $installedPackages
        ]);
    <?php else: ?>
        return $this->render('<?= $id ?>');
    <?php endif; ?>
}

<?php endforeach; ?>
}
