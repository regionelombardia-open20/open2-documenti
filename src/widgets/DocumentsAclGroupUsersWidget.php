<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\widgets
 * @category   CategoryName
 */

namespace open20\amos\documenti\widgets;

use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\M2MWidget;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\JsUtility;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiAclGroups;
use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\web\View;
use yii\widgets\PjaxAsset;

/**
 * Class DocumentsAclGroupUsersWidget
 * @package open20\amos\documenti\widgets
 */
class DocumentsAclGroupUsersWidget extends Widget
{
    /**
     * @var DocumentiAclGroups $model
     */
    public $model = null;
    
    /**
     * @var AmosDocumenti $documentsModule
     */
    public $documentsModule = null;
    
    /**
     * @var string $addPermission
     */
    public $addPermission = 'DOCUMENTIACLGROUPS_UPDATE';
    
    /**
     * @var string $manageAttributesPermission
     */
    public $manageAttributesPermission = 'DOCUMENTIACLGROUPS_UPDATE';
    
    /**
     * @var string $gridId
     */
    public $gridId = 'documents-acl-group-users-grid';
    
    /**
     * @var bool|false true if we are in edit mode, false if in view mode or otherwise
     */
    public $isUpdate = false;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->documentsModule = AmosDocumenti::instance();
        if (!$this->model) {
            throw new InvalidConfigException($this->throwErrorMessage('model'));
        }
    }
    
    /**
     * @param string $field
     * @return string
     */
    protected function throwErrorMessage($field)
    {
        return AmosDocumenti::t(
            'amosdocumenti',
            'Wrong widget configuration: missing field {field}',
            ['field' => $field]
        );
    }
    
    /**
     * @inheritdoc
     */
    public function run()
    {
        $gridId = $this->gridId;
        $model = $this->model;
        $groupId = $model->id;
        $params = [];
        $params['isUpdate'] = $this->isUpdate;
        
        $url = \Yii::$app->urlManager->createUrl(
            [
                '/documenti/documenti-acl-groups/group-users',
                'id' => $model->id,
                'classname' => $model->className(),
                'params' => $params
            ]
        );
        $searchPostName = 'searchDocumentsAclGroupUsersName';
        
        $js = JsUtility::getSearchM2mFirstGridJs($gridId, $url, $searchPostName);
        PjaxAsset::register($this->getView());
        $this->getView()->registerJs($js, View::POS_LOAD);
        
        $itemsMittente = [
            'userProfile.cognome',
            'userProfile.nome',
            'email'
        ];
        
        if ($this->isUpdate) {
            $actionColumnsTemplate = '{deleteRelation}';
            $actionColumnButtons = [
                'deleteRelation' => function ($url, $model) use ($groupId) {
                    /** @var User $model */
                    $url = '/documenti/documenti-acl-groups/elimina-m2m';
                    $urlDelete = Yii::$app->urlManager->createUrl([
                        $url,
                        'id' => $groupId,
                        'targetId' => $model->id
                    ]);
                    $loggedUser = Yii::$app->getUser();
                    $btnDelete = '';
                    if ($loggedUser->can($this->addPermission, ['model' => $this->model])) {
                        $btnDelete = Html::a(
                            AmosIcons::show('close', ['class' => '']),
                            $urlDelete,
                            [
                                'title' => AmosDocumenti::t('amosdocumenti', 'Delete'),
                                'data-confirm' => AmosDocumenti::t('amosdocumenti', '#group_users_widget_ask_remove_user_from_group'),
                                'class' => 'btn btn-danger-inverse'
                            ]
                        );
                    }
                    return $btnDelete;
                },
            ];
        } else {
            $actionColumnsTemplate = '';
            $actionColumnButtons = [];;
        }
        
        $query = $this->getM2mWidgetQuery($model, $searchPostName);
        
        $widget = M2MWidget::widget([
            'model' => $model,
            'modelId' => $model->id,
            'modelData' => $query,
            'overrideModelDataArr' => true,
            'gridId' => $gridId,
            'firstGridSearch' => true,
            'createAssociaButtonsEnabled' => $this->isUpdate,
            'disableCreateButton' => true,
            'btnAssociaLabel' => AmosDocumenti::t('amosdocumenti', '#group_users_widget_add_users'),
            'btnAssociaClass' => 'btn btn-primary',
            'deleteRelationTargetIdField' => 'user_id',
            'targetUrl' => '/documenti/documenti-acl-groups/associa-m2m',
            'targetUrlController' => 'documenti-acl-groups',
            'moduleClassName' => AmosDocumenti::className(),
            'postName' => 'DocumentiAclGroups',
            'postKey' => 'documentiaclgroups',
            'permissions' => [
                'add' => $this->addPermission,
                'manageAttributes' => $this->manageAttributesPermission
            ],
            'actionColumnsButtons' => $actionColumnButtons,
            'actionColumnsTemplate' => $actionColumnsTemplate,
            'itemsMittente' => $itemsMittente,
        ]);
        
        $html = Html::tag('div',
            Html::tag('h2', AmosDocumenti::t('amosdocumenti', '#group_users'), ['class' => 'subtitle-form m-b-15 group-widget-title']) .
            $widget,
            [
                'id' => $gridId,
                'data-pjax-container' => $gridId . '-pjax',
                'data-pjax-timeout' => 10000,
                'class' => 'col-xs-12 table-responsive group-widget-container',
            ]
        );
        
        return $html;
    }
    
    /**
     * @param DocumentiAclGroups $model
     * @param string $searchPostName
     * @return \yii\db\ActiveQuery
     */
    protected function getM2mWidgetQuery($model, $searchPostName)
    {
        $query = $model->getGroupUsers()->innerJoinWith('userProfile');
        $query->orderBy([
            UserProfile::tableName() . '.cognome' => SORT_ASC,
            UserProfile::tableName() . '.nome' => SORT_ASC,
        ]);
        if (isset($_POST[$searchPostName])) {
            $searchName = $_POST[$searchPostName];
            if (!empty($searchName)) {
                $query->andWhere([
                    'or',
                    ['like', UserProfile::tableName() . '.nome', $searchName],
                    ['like', UserProfile::tableName() . '.cognome', $searchName],
                ]);
            }
        }
        return $query;
    }
}
