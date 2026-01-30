<?php

use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\admin\AmosAdmin;

$js = <<<JS
$('.open-detail-history').click(function(e){
    e.preventDefault();
    $.ajax({
        method: 'get',
        url: "/documenti/documenti/detail-log",
        data: {
           id: $(this).attr('data-key') 
        },
        success: function (response) {
            $('#modal-history-detail .modal-body').html(response);
            $('#modal-history-detail').modal('show');
        }
    })
})
JS;
$this->registerJs($js);

$titleDoc = '';
if (!empty($modelSearch->id)) {
    $doc = Documenti::findOne($modelSearch->id);
    if ($doc) {
        $titleDoc = " - " . $doc->titolo;
    }
}
$this->title = AmosDocumenti::t('amosdocumenti', 'Storico log') . $titleDoc;
$this->params['titleSection'] = $this->title;
$this->params['breadcrumbs'][] = $this->title;

echo $this->render('_search_history', ['model' => $modelSearch]);
?>
<div class="col-md-12 m-b-20">
    <div class="row">
        <?= \open20\amos\core\views\AmosGridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                'name',
                [
                    'label' => 'Versione',
                    'value' => function ($model) {
                        if (!empty($model->description)) {
                            return 'Ver.' . $model->description;
                        }
                        return '';
                    }
                ],
                [
                    'attribute' => 'record_id',
                    'value' => function ($model) {
                        // messo where in modo da recuperare anche i documenti cancellati
                        $documento = Documenti::find()->where(['or',
                                ['version_parent_id' => $model->record_id, 'version' => $model->description],
                                ['id'=>$model->record_id, 'version_parent_id' => null, 'version' => $model->description],
                            ]
                        )->one();
                        if ($documento) {
                            return $documento->getFileTypeIcon() . \yii\helpers\Html::a($documento->titolo, ['/documenti/documenti/view', 'id' => $documento->id], [
                                    'title' => AmosDocumenti::t('amosdocumenti', 'Vai al documento'),
                                ]);
                        }

                        return '-';
                    },
                    'label' => 'Documento',
                    'format' => 'raw',
                ],

                [
                    'attribute' => 'user_id',
                    'value' => function ($model) {
                        $profile = \open20\amos\admin\models\UserProfile::find()
                            ->andWhere(['user_id' => $model->user_id])->one();
                        if ($profile) {
                            return \yii\helpers\Html::a($profile->nomeCognome, ['/' . AmosAdmin::getModuleName() . '/user-profile/view', 'id' => $profile->id], [
                                'title' => AmosDocumenti::t('amosdocumenti', 'Vai al profilo'),
                            ]);
                        }
                        return '-';
                    },
                    'format' => 'raw',

                ],
                [
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                        if ($model->created_at) {
                            $dateTime = new \DateTime($model->created_at);
                            return $dateTime->format('d/m/Y H:i');
                        }
                        return '-';
                    },
                    'format' => 'raw',

                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{showDetail}',
                    'buttons' => [
                        'showDetail' => function ($url, $model) {
                            if ($model->getChangesLog()->count() > 0) {
                                return \yii\helpers\Html::a(\open20\amos\core\icons\AmosIcons::show('file'), [], [
                                    'class' => 'btn btn-tools-secondary open-detail-history',
                                    'data-key' => $model->id,
                                    'title' => AmosDocumenti::t('amosdocumenti', "Dettaglio log")
                                ]);
                            }
                        }
                    ]
                ]
            ]
        ]) ?>
    </div>
</div>

<div id="modal-history-detail" class="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= AmosDocumenti::t('amosdocumenti', "Dettaglio log") ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Chiudi</button>


            </div>
        </div>
    </div>
</div>
