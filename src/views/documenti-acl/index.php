<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti-acl
 * @category   CategoryName
 */

use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\utilities\ModalUtility;
use open20\amos\core\views\DataProviderView;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiAcl;
use open20\amos\documenti\models\search\DocumentiSearch;
use open20\amos\documenti\utility\DocumentsUtility;
use open20\amos\documenti\widgets\DocumentsOwlCarouselWidget;

if ($currentView['name'] == 'expl') {
    echo $this->render('_explorer', []);
    return null;
}

/**
 * @var yii\web\View $this
 * @var yii\data\ActiveDataProvider $dataProvider
 * @var open20\amos\documenti\models\search\DocumentiSearch $model
 * @var \open20\amos\dashboard\models\AmosUserDashboards $currentDashboard
 */

/** @var \open20\amos\documenti\controllers\DocumentiController $controller */
$controller = Yii::$app->controller;
$actionId = $controller->action->id;

$enableCategories = $controller->documentsModule->enableCategories;

$columns = [];

$columns['type'] = [
    'label' => AmosDocumenti::t('amosdocumenti', '#type'),
    'format' => 'html',
    'value' => function ($model) {
        /** @var Documenti $model */
        $title = AmosDocumenti::t('amosdocumenti', 'Documenti');
        if ($model->isFolder()) {
            $title = ucfirst(AmosDocumenti::t('amosdocumenti', '#folder'));
        } else {
            $documentFile = $model->getDocumentMainFile();
            if ($documentFile) {
                $title = $documentFile->type;
            }
        }
        
        $icon = DocumentsUtility::getDocumentIcon($model, true);
        if ($model->drive_file_id) {
            return AmosIcons::show($icon, ['title' => $title], 'dash') . AmosIcons::show('google-drive', ['class' => 'google-sync'], 'am');
        } else {
            return AmosIcons::show($icon, ['title' => $title], 'dash');
        }
    }
];
$columns['titolo'] = [
    'attribute' => 'titolo',
    'headerOptions' => [
        'id' => $model->getAttributeLabel('titolo')
    ],
    'contentOptions' => [
        'headers' => $model->getAttributeLabel('titolo')
    ],
    'format' => 'html',
    'value' => function ($model) use ($actionId) {
        /** @var Documenti $model */
        $title = $model->titolo;
        if ($model->isFolder()) {
            $url = [$actionId, 'parentId' => $model->id];
            $urlTitle = 'Vai alla cartella';
        } else {
            $document = $model->getDocumentMainFile();
            if ($document) {
                $url = $document->getUrl();
                $urlTitle = '#download_document_for_view';
            } else {
                $url = $model->getFullViewUrl();
                $urlTitle = 'Vai al documento';
            }
        }
        return Html::a(
            $title,
            $url,
            [
                'title' => AmosDocumenti::t(
                        'amosdocumenti',
                        $urlTitle
                    ) . ' "' . $model->titolo . '"'
            ]
        );
    }
];

$columns['created_by'] = [
    'attribute' => 'createdUserProfile',
    'label' => AmosDocumenti::t('amosdocumenti', 'Creato da'),
    'value' => function ($model) {
        /** @var Documenti $model */
        $profile = $model->createdUserProfile;
        if (empty($profile)) {
            return '';
        }
        
        if (isset(\Yii::$app->params['disableLinkContentCreator']) && (\Yii::$app->params['disableLinkContentCreator'] === true)) {
            return $profile->nomeCognome;
        }
        
        return Html::a(
            $profile->nomeCognome,
            $profile->getFullViewUrl(),
            [
                'title' => AmosDocumenti::t('amosdocumenti', 'Apri il profilo di {nome_profilo}', ['nome_profilo' => $profile->nomeCognome])
            ]
        );
    },
    'format' => 'html'
];

// TODO riabilitare se vogliono il conteggio dei file per ciascuna cartella. C'è da fare il conteggio diversificato fra utente con permesso di leggere tutto e quello che può solo caricare i file.
// $columns[] = [
//     'label' => AmosDocumenti::t('amosdocumenti', 'Documents'),
//     'value' => function ($model) {
//         /** @var DocumentiAcl $model */
//         if ($model->isFolder()) {
//             return count($model->documentChildrens);
//         } else {
//             return '';
//         }
//     },
// ];

if ($enableCategories && !is_null(Yii::$app->request->get('parentId'))) {
    $columns['documenti_categorie_id'] = [
        'attribute' => 'documentiCategorie.titolo',
        'label' => AmosDocumenti::t('amosdocumenti', 'Categoria'),
    ];
}

if ($controller->documentsModule->enableDocumentVersioning) {
    $columns['version'] = [
        'attribute' => 'version',
        'value' => function ($model) {
            /** @var Documenti $model */
            return (!$model->isFolder() && $model->version ? $model->version : '');
        },
    ];
}

if ($controller->documentsModule->enableDocumentVersioning && !$model->isFolder()) {
    $columns['expandAllTitle'] = [
        'class' => 'kartik\grid\ExpandRowColumn',
        'expandAllTitle' => AmosDocumenti::t('amosdocumenti', 'Version'),
        'allowBatchToggle' => false,
        'header' => AmosDocumenti::t('amosdocumenti', 'Expand / Collapse'),
        'headerOptions' => [
            'style' => 'white-space: nowrap;',
        ],
        'contentOptions' => [
            'class' => 'text-center',
        ],
        'value' => function ($model, $key, $index, $column) use ($controller) {
            /** @var Documenti $model */
            $queryParams = \Yii::$app->request->getQueryParams();
            $queryParams['parent_id'] = $model->id;
            
            /** @var DocumentiSearch $modelSearch */
            $modelSearch = $controller->documentsModule->createModel('DocumentiSearch');
            /** @var  $dataProvider \yii\data\ActiveDataProvider */
            $dataProvider = $modelSearch->searchVersions($queryParams);
            
            if (!$model->isFolder() && $dataProvider->count > 0) {
                return \kartik\grid\GridView::ROW_COLLAPSED;
            } else
                return '';
        },
        'expandIcon' => AmosIcons::show(
            'caret-down',
            ['title' => AmosDocumenti::t('amosdocumenti', '#expand_title')]
        ),
        'collapseIcon' => AmosIcons::show(
            'caret-up',
            ['title' => AmosDocumenti::t('amosdocumenti', '#collapse_title')]
        ),
        'expandTitle' => AmosDocumenti::t('amosdocumenti', ''),
        'collapseTitle' => AmosDocumenti::t('amosdocumenti', ''),
        'detailUrl' => \yii\helpers\Url::to(['/documenti/documenti/list-only'])
    ];
}

$deleteOptions = DocumentsUtility::getGridActionColumnsButtonsOptions('delete');
$deleteOptions['data-confirm'] = function ($model) {
    /** @var DocumentiAcl $model */
    if ($model->isFolder()) {
        $trans = AmosDocumenti::t('amosdocumenti', '#confirm_delete_folder');
    } else {
        $trans = AmosDocumenti::t('amoscore', 'Sei sicuro di voler cancellare questo elemento?');
    }
    return $trans;
};
$updateOptions = DocumentsUtility::getGridActionColumnsButtonsOptions('update');
$updateOptions['url'] = ['/documenti/documenti/update'];
$updateOptions['defaultUrlIdParam'] = true;

$actionColumns = [
    'class' => 'open20\amos\core\views\grid\ActionColumn',
    'updateOptions' => $updateOptions,
    'deleteOptions' => $deleteOptions,
    'buttons' => [
        'view' => function ($url, $model) {
            /** @var DocumentiAcl $model */
            $btn = '';
            if (!$model->isFolder() && Yii::$app->user->can('DOCUMENTIACL_READ', ['model' => $model])) {
                $btn = Html::a(
                    AmosIcons::show('file'),
                    ['view', 'id' => $model->id],
                    [
                        'class' => 'btn btn-tools-secondary',
                        'title' => AmosDocumenti::t('amosdocumenti', 'Open the card')
                    ]
                );
            }
            return $btn;
        },
        'duplicateBtn' => function ($url, $model) use ($controller) {
            /** @var DocumentiAcl $model */
            return $this->render('_duplicate_btn', [
                'model' => $model,
                'isInIndex' => true
            ]);
        },
        'newDocVersion' => function ($url, $model) use ($controller) {
            /** @var DocumentiAcl $model */
            $btn = '';
            if ($model->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO && Yii::$app->getUser()->can(
                    'DOCUMENTIACL_UPDATE',
                    [
                        'model' => $model,
                        'newVersion' => $controller->documentsModule->enableDocumentVersioning
                    ]
                )) {
                if ($controller->documentsModule->enableDocumentVersioning && !$model->isFolder() && (is_null($model->version_parent_id))) {
                    $btn = ModalUtility::addConfirmRejectWithModal([
                        'modalId' => 'new-document-version-modal-id-' . $model->id,
                        'modalDescriptionText' => AmosDocumenti::t('amosdocumenti', '#NEW_DOCUMENT_VERSION_MODAL_TEXT'),
                        'btnText' => AmosIcons::show('plus', ['class' => '']),
                        'btnLink' => \yii\helpers\Url::to([
                            '/documenti/documenti-acl/new-document-version',
                            'id' => $model['id']
                        ]),
                        'btnOptions' => [
                            'title' => AmosDocumenti::t(
                                'amosdocumenti',
                                'New document version'
                            ), 'class' => 'btn btn-tools-secondary'
                        ]
                    ]);
                }
            }
            return $btn;
        }
    ]
];
$columns[] = $actionColumns;
?>
<div class="documents-index">
    <?php
    echo $this->render('_search', ['model' => $model, 'originAction' => Yii::$app->controller->action->id]);
    echo $this->render('_order', ['model' => $model]);
    
    echo DocumentsOwlCarouselWidget::widget([
        'owlCarouselId' => 'documentOwlCarousel',
        'owlCarouselClass' => 'document-owl-carousel',
        'singleItemView' => '@vendor/open20/amos-documenti/src/views/documenti/amos_owl_carousel_widget_item',
        'owlCarouselJSOptions' => "{
        margin: 10,
        nav: true,
        loop: false,
        autoplay: false,
        autoplayTimeout: 3000,
        responsive: {
            320: {
                items: 1,
                stagePadding: 30
            },
            420: {
                items: 1,
                stagePadding: 30
            },
            620: {
                items: 1,
                stagePadding: 30
            },
            768: {
                items: 2,
                stagePadding: 30
            },
            992: {
                items: 2,
                stagePadding: 30
            },
            1199: {
                items: 3,
                stagePadding: 30
            }
        }
    }"
    ]);
    
    echo DataProviderView::widget([
        'dataProvider' => $dataProvider,
        'currentView' => $currentView,
        'gridView' => [
            'rowOptions' => function ($model) {
                return ['class' => 'kv-disable-click'];
            },
            'columns' => $columns,
        ],
        'listView' => [
            'itemView' => '_item',
            'showItemToolbar' => false,
        ]
    ]);
    ?>
</div>
