<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\documenti
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\documenti\AmosDocumenti;
use open20\onlyoffice\widgets\EditorWidget;
use open20\amos\admin\models\UserProfile;
use yii\helpers\Url;
/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\models\Documenti $model
 */
/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$onlyofficeModule = \Yii::$app->getModule('onlyoffice');


$this->title = \yii\helpers\Html::decode($model->titolo);
$this->params['breadcrumbs'][] = ['label' => Yii::$app->session->get('previousTitle'), 'url' => Yii::$app->session->get('previousUrl')];
$this->params['breadcrumbs'][] = AmosDocumenti::t('amosdocumenti', 'Aggiorna');

$this->params['forceBreadcrumbs'][] = [
    'label' => AmosDocumenti::t('amosdocumenti', 'Documenti'),
    'url' => Yii::$app->session->get('previousUrl'),
];

$this->params['forceBreadcrumbs'][] = [
    'label' => AmosDocumenti::t('amosdocumenti', 'Aggiorna'),
    'url' => ['/documenti/documenti/update','id'=>$model->id],
];

$this->params['forceBreadcrumbs'][] = [
    'label' => $this->title,	
];

$modelUserProfile = UserProfile::find()
->andWhere(['user_id' => Yii::$app->user->id])
->one();
?>

<div class="open-office m-t-20">
    <?= EditorWidget::widget([
        'urlFile' => $model->documentMainFile->getWebUrl('original', true, false),
        'keyFile' => $model->documentMainFile->hash,
        'nameFile' => $model->documentMainFile->name . '.' . strtolower($model->documentMainFile->type),
        'options' => [
            'iframeHeight' => '700px',
            'fileTypeAuto' => strtolower($model->documentMainFile->type),
            'documentTypeAuto' => true,
        ],
        'configForJs' => [
            'documentType' => $onlyofficeModule->getDocumentTypeByExtension($model->documentMainFile->type,true),
            'editorConfig' => [
                'mode' => 'edit',
                'callbackUrl' => Url::to(['/documenti/onlyoffice/callback-api'], true),
                'customization' => [
                    'forcesave' => true, 
                ],
                'coEditing'=> [
                    'mode' => 'fast',
                    'change' => true,
                ],
                'user' => [
                    'id' => Yii::$app->user->id,
                    'name' => ((!empty($modelUserProfile)) ? $modelUserProfile->nomeCognome : ''),
                ],
            ],
        ]
    ]) ?>
</div>
