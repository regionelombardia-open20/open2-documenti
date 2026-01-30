<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\editors\Select;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\controllers\DocumentiController;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\tag\AmosTag;
use kartik\datecontrol\DateControl;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\search\DocumentiSearch $model
 * @var yii\widgets\ActiveForm $form
 * @var array $queryParams
 * @var $isTagContentsSearch bool
 */


/** @var AmosDocumenti $documentsModule */
$documentsModule = AmosDocumenti::instance();


$enableAutoOpenSearchPanel = !isset(\Yii::$app->params['enableAutoOpenSearchPanel']) || \Yii::$app->params['enableAutoOpenSearchPanel'] === true;
?>

<div class="documenti-search element-to-toggle" data-toggle-element="form-search">
    <div class="col-xs-12"><p class="h3"><?= AmosDocumenti::tHtml('amosdocumenti', 'Cerca per') ?>:</p></div>

    <?php $form = ActiveForm::begin([
        'action' => (isset($originAction) ? [$originAction,
            'currentView' => $queryParams['currentView'],
            'parentId' => $queryParams['parentId'],
        ] : ['history-log']),
        'method' => 'get',
    ]);

    echo Html::hiddenInput("enableSearch", $enableAutoOpenSearchPanel);
    echo Html::hiddenInput("currentView", $queryParams['currentView']);

    ?>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'activityType')->widget(Select2::className(), [
            'data' => \open20\amos\documenti\models\Documenti::getLogTypes(),
            'options' => ['placeholder' => 'Seleziona...']
        ])->label(AmosDocumenti::t('amosdocumenti', "Operazione")) ?>
    </div>

    <div class="col-sm-6 col-lg-8">
        <?= $form->field($model, 'documentTitle')->label(AmosDocumenti::t('amosdocumenti', "Titolo documento")) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'dateFrom')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ])->label(AmosDocumenti::t('amosdocumenti', "Dal")) ?>
    </div>

    <div class="col-sm-6 col-lg-4">
        <?= $form->field($model, 'dateTo')->widget(DateControl::className(), [
            'type' => DateControl::FORMAT_DATE
        ])->label(AmosDocumenti::t('amosdocumenti', "Al")) ?>
    </div>
    <div style="display: none">
        <?= $form->field($model, 'id')->hiddenInput() ?>
    </div>
    <div class="col-xs-12">
        <div class="pull-right">
            <?= Html::a(AmosDocumenti::t('amosdocumenti', 'Annulla'),
                [isset($originAction) ? $originAction : Yii::$app->controller->action->id, 'currentView' => $queryParams['currentView'], 'parentId' => $queryParams['parentId']],
                [
                    'class' => 'btn btn-outline-primary'
                ]
            ) ?>

            <?= Html::submitButton(AmosDocumenti::tHtml('amosdocumenti', 'Cerca'), [
                'class' => 'btn btn-primary',
            ]); ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <?php ActiveForm::end(); ?>

</div>
