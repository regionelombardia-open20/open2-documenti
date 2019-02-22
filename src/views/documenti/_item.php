<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\views\documenti
 * @category   CategoryName
 */

/**
 * @var yii\web\View $this
 * @var lispa\amos\documenti\models\Documenti $model
 */

$renderParams = [
    'model' => $model,
    'statsToolbar' => (isset($statsToolbar) ? $statsToolbar : null)
];

$viewToRender = ($model->is_folder ? '_item_folder' : '_item_document');

?>

<?= $this->render($viewToRender, $renderParams); ?>
