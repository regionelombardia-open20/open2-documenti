<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-documenti
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

/**
 * @var $model \open20\amos\documenti\models\Documenti
 */

use \open20\amos\documenti\models\Documenti;
use \open20\amos\core\views\AmosGridView;
use open20\amos\documenti\AmosDocumenti;
?>

<?php
if($model->isNewRecord){
    $query = Documenti::find()->andWhere(0);
}else {
    if (\Yii::$app->user->can(Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO, ['model' => $model])) {

        $query = Documenti::find()
            ->andWhere(['parent_id' => $model->id])
            ->andWhere(['OR',
                ['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA, 'created_by' => \Yii::$app->user->id],
                ['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE],
            ])
            ->andWhere(['is_folder' => false]);
    } else {
        $query = Documenti::find()
            ->andWhere(['parent_id' => $model->id])
            ->andWhere(['status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA])
            ->andWhere(['created_by' => \Yii::$app->user->id])
            ->andWhere(['is_folder' => false]);
    }
}
$dataProvider = new \yii\data\ActiveDataProvider([
    'query' => $query,
]);
if($page){
    $dataProvider->pagination->page = $page;
}
?>
<?php if ($dataProvider->totalCount > 0) { ?>
    <h5>
        <?= AmosDocumenti::t('amosdocumenti', "Seleziona qui sotto i documenti che vuoi pubblicare assieme alla cartella") ?>
        <br> <?= AmosDocumenti::t('amosdocumenti', "Hai selezionato") . ' ' ?><strong
            id="count-selected-elem">0</strong><?= ' ' . AmosDocumenti::t('amosdocumenti', "documenti") ?>
    </h5>


    <?= AmosGridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => \kartik\grid\CheckboxColumn::className()
            ],
            [
                'label' => AmosDocumenti::t('amosdocumenti', '#type'),
                'format' => 'html',
                'value' => function ($model) {
                    /** @var Documenti $model */
                    $title = AmosDocumenti::t('amosdocumenti', 'Documenti');
                    if ($model->is_folder) {
                        $title = AmosDocumenti::t('amosdocumenti', '#folder');
                    } else {
                        $documentFile = $model->getDocumentMainFile();
                        if ($documentFile) {
                            $title = $documentFile->type;
                        }
                    }

                    if(\Yii::$app->params['befe']){
                        return \open20\amos\documenti\utility\DocumentsUtility::getDocumentIcon($model);
                    }else {
                        $icon = \open20\amos\documenti\utility\DocumentsUtility::getDocumentIcon($model, true);
                        if ($model->drive_file_id) {
                            return \open20\amos\core\icons\AmosIcons::show($icon, ['title' => $title], 'dash') . \open20\amos\core\icons\AmosIcons::show('google-drive', ['class' => 'google-sync'], 'am');
                        } else {
                            return \open20\amos\core\icons\AmosIcons::show($icon, ['title' => $title], 'dash');
                        }
                    }
                }
            ],
            'titolo' => [
                'attribute' => 'titolo',
                'headerOptions' => [
                    'id' => $model->getAttributeLabel('titolo')
                ],
                'contentOptions' => [
                    'headers' => $model->getAttributeLabel('titolo')
                ],
                'format' => 'raw',
                'value' => function ($model) use ($actionId) {
                    $stringa = \open20\amos\documenti\models\DocumentiCartellePath::getPath($model);

                    $options = [
                        'title' => \open20\amos\documenti\AmosDocumenti::t('amosdocumenti', 'Scarica il documento ')
                            . '"'
                            . $stringa
                            . $model->titolo
                            . '"'
                    ];

                    /** @var Documenti $model */
                    $title = $model->titolo;
                    $append = '';
                    $url = '';
                    $document = $model->getDocumentMainFile();
                    if ($document) {
                        $append = ' (' . $document->formattedSize . ')';
                        $url = $document->getUrl();
                    } else {
                        $url = $model->link_document;
                        $options['target'] = '_blank';
                    }
                    return \yii\helpers\Html::a(
                        $title,
                        $url,
                        $options
                    );
                }
            ],
            [
                'attribute' => 'workflowStatus.label',
                'label' => AmosDocumenti::t('amosdocumenti', 'Status')
            ]
        ]
    ]) ?>

<?php } else { ?>
    <p><?= AmosDocumenti::t('amosdocumenti', "Sei sicuro di voler cambiare stato?") ?></p>
<?php } ?>
