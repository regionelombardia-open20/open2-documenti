<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\views\documenti
 * @category   CategoryName
 */

use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiAclGroupsUserMm;
use yii\web\JsExpression;

/**
 * @var yii\web\View $this
 * @var open20\amos\documenti\AmosDocumenti $documentsModule
 * @var yii\widgets\ActiveForm $form
 * @var open20\amos\documenti\models\DocumentiAcl $model
 * @var open20\amos\documenti\models\DocumentiAclGroupsUserMm $emptyGroupMmModel
 * @var bool $isUpdate
 */

$usersGridId = 'documenti-acl-users-permissions-grid';

$queryUsers = $model->getDocumentiAclGroupsMms()->andWhere(['group_id' => null]);

$modelDataArrUsers = $model->getDocumentiAclGroupsMms()
    ->andWhere(['group_id' => null])
    ->indexBy('user_id')
    ->all();

$itemsMittenteUsers = [
    'userProfile.surnameName',
    [
        'class' => 'open20\amos\core\views\grid\CheckboxColumn',
        'label' => $emptyGroupMmModel->getAttributeLabel('update_folder_content'),
        'checkboxOptions' => function ($model, $key, $index, $column) use ($modelDataArrUsers) {
            /** @var DocumentiAclGroupsUserMm $model */
            $checkboxOptions = [
                'name' => 'DocumentiAclGroupsUserMm[users][' . $model->user_id . '][update_folder_content]',
                'value' => $model->update_folder_content,
                'onchange' => new JsExpression('$(this).val($(this).is(":checked") ? 1 : 0);'),
                'class' => 'm2m-target-checkbox'
            ];
            if (isset($modelDataArrUsers[$model->user_id]) && ($modelDataArrUsers[$model->user_id]->update_folder_content == 1)) {
                $checkboxOptions['checked'] = 'checked';
            }
            return $checkboxOptions;
        },
        'multiple' => false
    ],
    [
        'class' => 'open20\amos\core\views\grid\CheckboxColumn',
        'label' => $emptyGroupMmModel->getAttributeLabel('upload_folder_files'),
        'checkboxOptions' => function ($model, $key, $index, $column) use ($modelDataArrUsers) {
            /** @var DocumentiAclGroupsUserMm $model */
            $checkboxOptions = [
                'name' => 'DocumentiAclGroupsUserMm[users][' . $model->user_id . '][upload_folder_files]',
                'value' => $model->upload_folder_files,
                'onchange' => new JsExpression('$(this).val($(this).is(":checked") ? 1 : 0);'),
                'class' => 'm2m-target-checkbox'
            ];
            if (isset($modelDataArrUsers[$model->user_id]) && ($modelDataArrUsers[$model->user_id]->upload_folder_files == 1)) {
                $checkboxOptions['checked'] = 'checked';
            }
            return $checkboxOptions;
        },
        'multiple' => false
    ],
    [
        'class' => 'open20\amos\core\views\grid\CheckboxColumn',
        'label' => $emptyGroupMmModel->getAttributeLabel('read_folder_files'),
        'checkboxOptions' => function ($model, $key, $index, $column) use ($modelDataArrUsers) {
            /** @var DocumentiAclGroupsUserMm $model */
            $checkboxOptions = [
                'name' => 'DocumentiAclGroupsUserMm[users][' . $model->user_id . '][read_folder_files]',
                'value' => $model->read_folder_files,
                'onchange' => new JsExpression('$(this).val($(this).is(":checked") ? 1 : 0);'),
                'class' => 'm2m-target-checkbox'
            ];
            if (isset($modelDataArrUsers[$model->user_id]) && ($modelDataArrUsers[$model->user_id]->read_folder_files == 1)) {
                $checkboxOptions['checked'] = 'checked';
            }
            return $checkboxOptions;
        },
        'multiple' => false
    ]
];

?>

<?= Html::tag('h3', AmosDocumenti::t('amosdocumenti', 'Utenti'), ['class' => 'subtitle-form']) ?>
<?= M2MWidget::widget([
    'model' => $model,
    'modelId' => $model->id,
    'modelData' => $queryUsers,
    'overrideModelDataArr' => true,
    'showPageSummary' => false,
    'showPager' => false,
    'itemsMittentePagination' => false,
    'gridId' => $usersGridId,
    'createAssociaButtonsEnabled' => $isUpdate,
    'disableCreateButton' => true,
    'btnAssociaLabel' => AmosDocumenti::t('amosdocumenti', '#documenti_acl_grids_add_user'),
    'btnAssociaClass' => 'btn btn-primary',
    'deleteRelationTargetIdField' => 'user_id',
    'targetUrl' => '/documenti/documenti/associa-m2m-users',
    'targetUrlController' => 'documenti',
    'moduleClassName' => AmosDocumenti::className(),
    'postName' => 'DocumentiAcl',
    'postKey' => 'documentiacl',
    'permissions' => [
        'add' => 'DOCUMENTIACL_UPDATE',
        'manageAttributes' => 'DOCUMENTIACL_UPDATE',
    ],
    'actionColumnsButtons' => [
        'deleteRelation' => function ($url, $functionModel) use ($model) {
            /** @var \open20\amos\documenti\models\DocumentiAclGroupsUserMm $functionModel */
            /** @var \open20\amos\documenti\models\DocumentiAcl $model */
            $btn = '';
            if (Yii::$app->user->can('DOCUMENTIACL_UPDATE', ['model' => $model])) {
                $btn = Html::a(
                    AmosIcons::show('delete'),
                    [
                        '/documenti/documenti/elimina-m2m-users',
                        'documentId' => $functionModel->document_id,
                        'userId' => $functionModel->user_id
                    ],
                    [
                        'title' => Yii::t('amoscore', 'Elimina associazione'),
                        'data-confirm' => Yii::t('amoscore', 'Sei sicuro di voler cancellare questo elemento?'),
                        'class' => 'btn btn-danger-inverse'
                    ]
                );
            }
            return $btn;
        }
    ],
    'itemsMittente' => $itemsMittenteUsers,
]); ?>
