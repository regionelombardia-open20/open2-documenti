<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models\search
 * @category   CategoryName
 */

namespace open20\amos\documenti\models\search;

use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiAcl;
use open20\amos\documenti\models\DocumentiAclGroups;
use open20\amos\documenti\models\DocumentiAclGroupsUserMm;
use open20\amos\documenti\utility\AclDocumentsUtility;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

/**
 * Class DocumentiAclSearch
 * @package open20\amos\documenti\models\search
 */
class DocumentiAclSearch extends DocumentiAcl
{
    /**
     * @var int $parentId
     */
    public $parentId;
    
    /**
     * @var AclDocumentsUtility $aclUtility
     */
    public $aclUtility;
    
    public function init()
    {
        parent::init();
        
        $this->aclUtility = new AclDocumentsUtility();
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['titolo'], 'safe']
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }
    
    /**
     * This is the base search.
     * @param array $params
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function baseSearch($params)
    {
        /** @var DocumentiAclGroups $model */
        $model = $this->documentsModule->createModel('DocumentiAcl');
        
        /** @var ActiveQuery $query */
        $query = $model::find();
        $query->andWhere([$model::tableName() . '.is_acl' => Documenti::IS_ACL]);
        
        $this->initOrderVars(); // Init the default search values
        $this->setOrderVars($params); // Check params to get orders value
        
        return $query;
    }
    
    /**
     * Search sort.
     * @param ActiveDataProvider $dataProvider
     */
    protected function setSearchSort($dataProvider)
    {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort([
                'attributes' => [
                    'titolo' => [
                        'asc' => [self::tableName() . '.titolo' => SORT_ASC],
                        'desc' => [self::tableName() . '.titolo' => SORT_DESC]
                    ],
                ]
            ]);
        }
    }
    
    /**
     * Base filter.
     * @param ActiveQuery $query
     * @return mixed
     */
    public function baseFilter($query)
    {
        $query->andFilterWhere(['like', self::tableName() . '.titolo', $this->titolo]);
        return $query;
    }
    
    /**
     * @param array $params
     * @return ActiveDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchSharedWithMe($params)
    {
        $loggedUserId = \Yii::$app->user->id;
        $parentId = \Yii::$app->request->get('parentId');
        
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->model('Documenti');
        
        /** @var DocumentiAclGroupsUserMm $documentiAclGroupsUserMmModel */
        $documentiAclGroupsUserMmModel = $this->documentsModule->model('DocumentiAclGroupsUserMm');
        
        /** @var ActiveQuery $query */
        $query = $this->baseSearch($params);
        $query->groupBy([$documentiModel::tableName() . '.id']);
        
        $query->andWhere([$documentiAclGroupsUserMmModel::tableName() . '.user_id' => $loggedUserId]);
        
        if (empty($parentId)) {
            // Se passo di qui sono nell'elenco principale della roba condivisa con me e per ora si condividono solo cartelle.
            // Quando chiederanno di condividere anche il singolo file dovrà essere rimossa la riga dell'is_folder.
            // Per mostrare una cartella mi basta che l'utente abbia almeno un permesso su una di esse.
            // Poi a cosa l'utente vede dentro la cartella ci pensa la query dell'else che verifica l'effettivo permesso.
            $query->innerJoinWith('documentiAclGroupsMms');
            $query->andWhere([$documentiModel::tableName() . '.is_folder' => Documenti::IS_FOLDER]);
            $query->andWhere(['or',
                [$documentiAclGroupsUserMmModel::tableName() . '.update_folder_content' => 1],
                [$documentiAclGroupsUserMmModel::tableName() . '.upload_folder_files' => 1],
                [$documentiAclGroupsUserMmModel::tableName() . '.read_folder_files' => 1],
            ]);
        } else {
            // Se passo di qui sono in una cartella, quindi per ora devo mostrare solamente i file contenuti in questa cartella.
            // Tutte le altre cartella non devono essere mostrate all'utente normale.
            // Quando chiederanno di condividere anche il singolo file dovrà essere rimossa la riga dell'is_folder.
            $query->innerJoinWith('parentDocumentiAclGroupsMms');
            $query->andWhere([$documentiModel::tableName() . '.is_folder' => Documenti::IS_DOCUMENT]);
            $query->andWhere([$documentiModel::tableName() . '.parent_id' => $parentId]);
            
            $userPermissionCode = $this->aclUtility->userPermissionOnFolder($loggedUserId, $parentId);
            
            if ($userPermissionCode == AclDocumentsUtility::MANAGE_OWN_CONTENT) {
                $query->andWhere([$documentiModel::tableName() . '.created_by' => $loggedUserId]);
            } elseif ($userPermissionCode == AclDocumentsUtility::NO_PERMISSION) {
                $query->where('0=1');
            }
        }
        
        $dataProvider = new ActiveDataProvider(['query' => $query]);
        $this->setSearchSort($dataProvider);
        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }
        $this->baseFilter($query);
        return $dataProvider;
    }
    
    /**
     * @param array $params
     * @param string $queryType
     * @param int|null $limit
     * @return \yii\data\BaseDataProvider
     * @throws \yii\base\InvalidConfigException
     */
    public function searchManage(array $params, string $queryType = 'acl-manage', int $limit = null)
    {
        $getParentId = (\Yii::$app instanceof \yii\web\Application) ? \Yii::$app->request->get('parentId') : null;
        if ($getParentId) {
            $params['DocumentiAclSearch']['parentId'] = $getParentId;
        }
        $query = $this
            ->buildQuery($params, $queryType)
            ->limit($limit);
        
        /** Switch off notifications - method of NotifyRecord */
        $this->switchOffNotifications($query);
        
        $dp_params = ['query' => $query];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }
        
        //set the data provider
        $dataProvider = new ActiveDataProvider($dp_params);
        $dataProvider = $this->searchDefaultOrder($dataProvider);
        
        //overwrite default order in case  foldering is enabled
        if (empty($params['DocumentiAclSearch']['orderAttribute'])) {
            if ($this->documentsModule->enableFolders) {
                $dataProvider->setSort([
                    'defaultOrder' => [
                        'is_folder' => SORT_DESC,
                        'data_pubblicazione' => SORT_DESC,
                    ]
                ]);
            } else {
                $dataProvider->setSort([
                    'defaultOrder' => [
                        'data_pubblicazione' => SORT_DESC,
                    ]
                ]);
            }
        }
        
        //if you don't use the seach form, the recursive search is not active
        if (!($this->load($params) && $this->validate())) {
            //if you come from widget grphic LastDocuments Show also documents that are inside the folders
            if (($queryType != 'to-validate') && !(!empty($params['fromWidgetGraphic']) && $params['fromWidgetGraphic'] == true)) {
                $query->andWhere([self::tableName() . '.parent_id' => $this->parentId]);
            }
            return $dataProvider;
        }
        
        if (!empty($this->parentId)) {
            $idsToSearch = $this->parentId;
            if (!$this->documentsModule->disableAdminListRecursion) {
                // recursive search
                /** @var Documenti $documentiModel */
                $documentiModel = $this->documentsModule->createModel('DocumentiAcl');
                $currentFolder = $documentiModel::findOne($this->parentId);
                $idsToSearch = $currentFolder->getAllChildrens();
            }
            $query->andWhere([self::tableName() . '.parent_id' => $idsToSearch]);
        }
        
        $this->applySearchFilters($query);
        $this->getSearchQuery($query);
        
        return $dataProvider;
    }
}
