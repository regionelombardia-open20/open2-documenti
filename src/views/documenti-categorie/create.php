<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\views\documenti-categorie
 * @category   CategoryName
 */

use lispa\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\DocumentiCategorie $model
 */

$this->title = AmosDocumenti::t('amosdocumenti', 'Crea categoria');
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Documenti'), 'url' => '/documenti'];
$this->params['breadcrumbs'][] = ['label' => AmosDocumenti::t('amosdocumenti', 'Categorie documenti'), 'url' => ['index']];
?>

<div class="documenti-categorie-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
