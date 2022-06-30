<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\controllers
 * @category   CategoryName
 */

namespace open20\amos\documenti\controllers;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\core\forms\editors\m2mWidget\controllers\M2MWidgetControllerTrait;
use open20\amos\core\record\Record;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiAclGroups;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

/**
 * Class DocumentiAclGroupsController
 * This is the class for controller "DocumentiAclGroupsController".
 * @package open20\amos\documenti\controllers
 */
class DocumentiAclGroupsController extends \open20\amos\documenti\controllers\base\DocumentiAclGroupsController
{
    /**
     * M2MWidgetControllerTrait
     */
    use M2MWidgetControllerTrait;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        
        $this->setMmTableName($this->documentsModule->model('DocumentiAclGroupsUserMm'));
        $this->setStartObjClassName($this->documentsModule->model('DocumentiAclGroups'));
        $this->setMmStartKey('group_id');
        $this->setTargetObjClassName(AmosAdmin::instance()->model('User'));
        $this->setMmTargetKey('user_id');
        $this->setTargetUrl('associa-m2m');
        $this->setRedirectAction('update');
        $this->setModuleClassName(AmosDocumenti::className());
        $this->setCustomQuery(true);
        
        $this->setUpLayout('main');
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'associa-m2m',
                            'elimina-m2m',
                            'annulla-m2m',
                            'group-users',
                        ],
                        'roles' => ['DOCUMENTIACLGROUPS_UPDATE']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post', 'get']
                ]
            ]
        ]);
    }
    
    /**
     * @param DocumentiAclGroups $model
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public function getAssociaM2mQuery($model)
    {
        /** @var ActiveQuery $query */
        $query = $model->getAssociationTargetQuery($model->id);
        $post = \Yii::$app->request->post();
        if (isset($post['genericSearch']) && (strlen($post['genericSearch']) > 0)) {
            $searchName = $post['genericSearch'];
            /** @var AmosAdmin $amosAdmin */
            $amosAdmin = AmosAdmin::instance();
            /** @var UserProfile $userProfileModel */
            $userProfileModel = $amosAdmin->createModel('UserProfile');
            $userProfileTable = $userProfileModel::tableName();
            $query->andWhere([
                'or',
                ['like', $userProfileTable . '.nome', $searchName],
                ['like', $userProfileTable . '.cognome', $searchName],
                ['like', "CONCAT( " . $userProfileTable . ".nome , ' ', " . $userProfileTable . ".cognome )", $searchName],
                ['like', "CONCAT( " . $userProfileTable . ".cognome , ' ', " . $userProfileTable . ".nome )", $searchName]
            ]);
        }
        return $query;
    }
    
    /**
     * Users of a group m2m widget - Ajax call to redraw the widget
     *
     * @param int $id
     * @param string $classname
     * @param array $params
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionGroupUsers($id, $classname, array $params)
    {
        if (\Yii::$app->request->isAjax) {
            $this->setUpLayout(false);
            
            /** @var Record $object */
            $object = \Yii::createObject($classname);
            $model = $object->findOne($id);
            $isUpdate = $params['isUpdate'];
            
            return $this->render('group-users', [
                'model' => $model,
                'isUpdate' => $isUpdate,
            ]);
        }
        return null;
    }
}
