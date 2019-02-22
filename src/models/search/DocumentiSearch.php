<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\models\search
 * @category   CategoryName
 */

namespace lispa\amos\documenti\models\search;

use lispa\amos\core\interfaces\ContentModelSearchInterface;
use lispa\amos\core\interfaces\SearchModelInterface;
use lispa\amos\core\module\AmosModule;
use lispa\amos\core\record\SearchResult;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\notificationmanager\base\NotifyWidget;
use lispa\amos\notificationmanager\models\NotificationChannels;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;
use yii\di\Container;
use yii\helpers\ArrayHelper;
use lispa\amos\core\interfaces\CmsModelInterface;
use lispa\amos\core\record\CmsField;

/**
 * Class DocumentiSearch
 * DocumentiSearch represents the model behind the search form about `lispa\amos\documenti\models\Documenti`.
 * @package lispa\amos\documenti\models\search
 */
class DocumentiSearch extends Documenti implements SearchModelInterface, ContentModelSearchInterface, CmsModelInterface
{
    private $container;
    public $parentId;

    public function __construct(array $config = [])
    {
        $this->isSearch = true;
        parent::__construct($config);
        $this->modelClassName = Documenti::className();
    }

    public function init()
    {
        parent::init();

        $this->data_pubblicazione = null;
        $this->data_rimozione = null;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'primo_piano', 'hits', 'abilita_pubblicazione', 'created_by', 'updated_by', 'deleted_by', 'parent_id'], 'integer'],
            [['parentId', 'titolo', 'sottotitolo', 'descrizione_breve', 'descrizione', 'metakey', 'metadesc', 'data_pubblicazione', 'data_rimozione', 'documenti_categorie_id', 'created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }


    public function searchFieldsMatch(){
        return [
            'id',
            'primo_piano',
            'hits',
            'abilita_pubblicazione',
            'created_at',
            'updated_at',
            'deleted_at',
            'created_by',
            'updated_by',
            'deleted_by',
            'documenti_categorie_id',
        ];
    }

//    /**
//     * Array of fields to search with >= condition in search method
//     *
//     * @return array
//     */
//    public function searchFieldsGreaterEqual()
//    {
//        return [
//            'data_pubblicazione'
//        ];
//    }


    public function searchFieldsLike()
    {
        return [
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione',
            'metakey',
            'metadesc',
        ];
    }

    public function searchFieldsGlobalSearch(){
        return [
            'titolo',
            'sottotitolo',
            'descrizione_breve',
            'descrizione',
            'metakey',
            'metadesc',
        ];
    }

    /**
     * Use to add Join condition/add other filtering condition
     *
     * @param ActiveQuery $query
     */
    public function getSearchQuery($query)
    {

        /** @var AmosDocumenti $documentModule */
        $documentModule = \Yii::$app->getModule(AmosDocumenti::getModuleName());
        if (isset($documentModule)) {
            if ($documentModule->hidePubblicationDate == false) {
                $query->andFilterWhere([
                    'data_pubblicazione' => $this->data_pubblicazione,
                    'data_rimozione' => $this->data_rimozione,
                ]);
            }
        }
    }

    /**
     * Documents search method
     *
     * @param array $params
     * @param string $queryType
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function search($params, $queryType = null, $limit = null, $onlyDratfs = false)
    {
        $query = $this->buildQuery($params, $queryType);
        $query->limit($limit);

        /** Switch off notifications - method of NotifyRecord */
        $this->switchOffNotifications($query);

        $dp_params = ['query' => $query,];
        if ($limit) {
            $dp_params ['pagination'] = false;
        }
        //set the data provider
        $dataProvider = new ActiveDataProvider($dp_params);

        $dataProvider = $this->searchDefaultOrder($dataProvider);

        //overwrite default order in case  foldering is enabled
        if (AmosDocumenti::instance()->enableFolders) {
            $dataProvider->setSort([
                'defaultOrder' => [
                    'is_folder' => SORT_DESC,
                ]
            ]);
        }

        //if you don't use the seach form, the recursive search is not active
        if (!($this->load($params) && $this->validate())) {
            $query->andWhere(['parent_id' => $this->parentId]);
            return $dataProvider;
        }

        // recursive search
        if (!empty($this->parentId)) {
            $currentFolder = Documenti::findOne($this->parentId);
            $listChildrenId = $currentFolder->getAllChildrens();
            $query->andWhere(['parent_id' => $listChildrenId]);
        }
        //if parentid empty the search is without (parent_id IS NULL)

        if (isset($params[$this->formName()]['tagValues'])) {

            $tagValues = $params[$this->formName()]['tagValues'];
            $this->setTagValues($tagValues);
            if (is_array($tagValues) && !empty($tagValues)) {
                $orQueries = null;
                $i = 0;
                foreach ($tagValues as $rootId => $tagId) {
                    if (!empty($tagId)) {
                        if ($i == 0) {
                            $query->innerJoin('entitys_tags_mm entities_tag',
                                "entities_tag.classname = '" . addslashes($this->modelClassName) . "' AND entities_tag.record_id=" .static::tableName().".id");
                            $orQueries[] = 'or';
                        }
                        $tags = explode(',', $tagId);
                        $tags = array_unique($tags);
                        $orQueries[] = ['and',["entities_tag.tag_id" => $tags],['entities_tag.root_id' =>  $rootId ], ['entities_tag.deleted_at' => null]];
                        $i++;
                    }
                }
                if(!empty($orQueries)) {
                    $query->andWhere($orQueries);
                }
            }
        }

        $this->applySearchFilters($query);

        $this->getSearchQuery($query);

        return $dataProvider;
    }

    /**
     * Documents base search: all documents matching search parameters and not deleted.
     *
     * @param   array $params Search parameters
     * @return \yii\db\ActiveQuery
     */
    public function baseSearch($params)
    {
        //init the default search values
        $this->initOrderVars();

        //check params to get orders value
        $this->setOrderVars($params);

        /** @var \yii\db\ActiveQuery $baseQuery */
        $documentModel = $this->documentsModule->model('Documenti');
        $baseQuery =  $documentModel::find()->distinct();
        
        if ($this->documentsModule->enableDocumentVersioning) {
            $baseQuery->andWhere(['version_parent_id' => null]);
        }

        return $baseQuery;
    }

    /**
     * Search the Documents created by the logged user
     *
     * @param array $params Array di parametri per la ricerca
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchOwnDocuments($params, $limit = null)
    {
        return $this->search($params, 'created-by', $limit);
    }

    /**
     * Search documents to validate based on cwh rules if cwh is active, all documents in 'to validate status' otherwise
     *
     * @param array $params Array di parametri per la ricerca
     * @param int $limit
     * @return ActiveDataProvider
     */
    public function searchToValidateDocuments($params, $limit = null)
    {
        return $this->search($params, 'to-validate', $limit);
    }

    /**
     * Search last documents in validated status, generally the limit is set to 3 (by last documents graphic widget)
     *
     * @param array $params Array of search parameters
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function lastDocuments($params, $limit = null)
    {
        $dataProvider = $this->searchAll(Yii::$app->request->getQueryParams(), $limit);
        return $dataProvider;
    }

    /**
     * @param $params
     * @param null $limit
     * @return ActiveDataProvider
     */
    public function searchAdminAll($params, $limit = null)
    {
        return $this->search($params, 'admin-all', $limit);
    }

    /**
     * Search method useful to retrieve all validated documenti (based on publication rule and visibility).
     *
     * @param array $params Array of get parameters for search
     * @param int|null $limit
     * @return ActiveDataProvider
     */
    public function searchOwnInterest($params, $limit = null)
    {
        return $this->search($params, 'own-interest', $limit);
    }

    /**
     * Search method useful to retrieve documents in validated status with both flags primo_piano and in_evidenza true
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHighlightedAndHomepageDocumenti($params)
    {
        $query = $this->highlightedAndHomepageDocumentiQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * Search method useful to retrieve documents in validated status with flag primo_piano = true
     *
     * @param array $params Array di parametri
     * @return ActiveDataProvider
     */
    public function searchHomepageDocuments($params)
    {
        $query = $this->homepageDocumentsQuery($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                ],
            ],
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        return $dataProvider;
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function highlightedAndHomepageDocumentiQuery($params)
    {
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->where([])
            ->andWhere([$tableName . '.status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
            ->andWhere($tableName . '.deleted_at IS NULL')
            ->andWhere($tableName . '.in_evidenza = 1')
            ->andWhere($tableName . '.primo_piano = 1');
        return $query;
    }

    /**
     * @param array $params
     * @return \yii\db\ActiveQuery
     */
    public function homepageDocumentsQuery($params)
    {
        $tableName = $this->tableName();
        $query = $this->baseSearch($params)
            ->where([])
            ->andWhere([$tableName . '.status' => Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO])
            ->andWhere($tableName . '.deleted_at IS NULL')
            ->andWhere($tableName . '.primo_piano = 1');
        return $query;
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function searchVersions($params)
    {
        $currentDoc = Documenti::find()->select('max(version), id')->andWhere(['OR',
            ['version_parent_id' => $params['parent_id']],
            ['id' => $params['parent_id']],
        ])->one();

        $query = Documenti::find()
            ->andFilterWhere(['OR',
                ['version_parent_id' => $params['parent_id']],
                ['id' => $params['parent_id']],
            ])
            ->andFilterWhere(['!=', 'id', $currentDoc->id])
            ->orderBy('version DESC');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

    /**
     * @param object $model The model to convert into SearchResult
     * @return SearchResult
     */
    public function convertToSearchResult($model)
    {
        $searchResult = new SearchResult();
        $searchResult->url = $model->getFullViewUrl();
        $searchResult->box_type = "file";
        $searchResult->id = $model->id;
        $searchResult->titolo = $model->titolo;
        $searchResult->data_pubblicazione = $model->data_pubblicazione;
        $searchResult->documento = $model->getDocumentMainFile();
        $searchResult->abstract = $model->descrizione_breve;
        return $searchResult;
    }

    /**
     * @inheritdoc
     */
    public function searchDefaultOrder($dataProvider)
    {
        // Check if can use the custom module order
        if ($this->canUseModuleOrder()) {
            $dataProvider->setSort($this->createOrderClause());
        } else { // For widget graphic last news, order is incorrect without this else
            $dataProvider->setSort([
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC
                ]
            ]);
        }
        return $dataProvider;
    }

    public function cmsIsVisible($id)
    {
        $retValue = true;
        return $retValue;
    }

    public function cmsSearch($params, $limit)
    {
        $params = array_merge($params, Yii::$app->request->get());
        $this->load($params);
        $query = $this->homepageDocumentsQuery($params);
        $this->applySearchFilters($query);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'data_pubblicazione' => SORT_DESC,
                ],
            ],
        ]);
        if($params["withPagination"]){
           $dataProvider->setPagination(['pageSize' => $limit]);
           $query->limit(null);
        }else{
            $query->limit($limit);
        }
        if(!empty($params["conditionSearch"]))
        {
             $commands = explode (";", $params["conditionSearch"]);
             foreach ($commands as $command)
             {
                $query->andWhere(eval("return " . $command . ";"));
             }
        }
        return $dataProvider;
    }

    public function cmsSearchFields()
    {
        $searchFields = [];

        array_push($searchFields, new CmsField("titolo", "TEXT"));
        array_push($searchFields, new CmsField("descrizione", "TEXT"));
        array_push($searchFields, new CmsField("descrizione_breve", "TEXT"));

        return $searchFields;
    }

    public function cmsViewFields()
    {
        $viewFields = [];

        array_push($viewFields, new CmsField("titolo", "TEXT", 'amosdocumenti', $this->attributeLabels()['titolo']));
        array_push($viewFields, new CmsField("descrizione", "TEXT", 'amosdocumenti', $this->attributeLabels()['descrizione']));
        array_push($viewFields, new CmsField("descrizione_breve", "TEXT", 'amosdocumenti', $this->attributeLabels()['descrizione_breve']));
        array_push($viewFields, new CmsField("documentMainFile", "IMAGE", 'amosdocumenti', $this->attributeLabels()['documentMainFile']));
        return $viewFields;
    }
}
