<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\views\documenti-categorie
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

use open20\amos\core\helpers\Html;
use open20\amos\core\views\AmosGridView;
use open20\amos\documenti\AmosDocumenti;

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\documenti\models\search\DocumentiCategorieSearch $searchModel
 */

//$this->title = AmosDocumenti::t('amosdocumenti', '#page_title_documents_categories');
//$this->params['breadcrumbs'][] = $this->title;
$module = AmosDocumenti::instance();
?>
<div class="documenti-categorie-index">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo AmosGridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $model,
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
        'columns' => [
            'filemanager_mediafile_id' => [
                'label' => AmosDocumenti::t('amosdocumenti', 'Icona'),
                'format' => 'html',
                'value' => function ($model) {
                    $url = '/img/img_default.jpg';
                    if (!is_null($model->documentCategoryImage)) {
                        $url = $model->documentCategoryImage->getUrl('square_small', false, true);
                    }
                    return Html::img($url, ['class' => 'gridview-image', 'alt' => AmosDocumenti::t('amosdocumenti', 'Immagine della categoria')]);
                }
            ],
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione:ntext',
            'enable_annual_archiving' => [
                'attribute'=>'enable_annual_archiving',
                'format' => 'raw',
                'contentOptions'=>['class'=>'text-center'],
                'value' => function ($model) {                                      
                    return $model->enable_annual_archiving ? AmosDocumenti::t('amosdocumenti', 'SI') : AmosDocumenti::t('amosdocumenti', 'NO') ;
                },
                'visible'=>$module->enableAnnualArchiving,
            ],
            'enable_countdown_unpublishing' => [
                'attribute'=>'enable_countdown_unpublishing',
                'format' => 'raw',
                'contentOptions'=>['class'=>'text-center'],
                'value' => function ($model) {                                      
                    return $model->enable_countdown_unpublishing ? AmosDocumenti::t('amosdocumenti', 'SI') : AmosDocumenti::t('amosdocumenti', 'NO') ;
                },
                'visible'=>$module->enableCountdownUnpublishing,
            ],
            [
                'class' => 'open20\amos\core\views\grid\ActionColumn',
            ],
        ],
    ]); ?>
</div>
