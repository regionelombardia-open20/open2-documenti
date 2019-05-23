<?php
/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\controllers
 * @category   CategoryName
 */

namespace lispa\amos\documenti\controllers;

use lispa\amos\admin\models\UserProfile;
use lispa\amos\community\AmosCommunity;
use lispa\amos\community\models\Community;
use lispa\amos\community\models\CommunityUserMm;
use lispa\amos\community\models\search\CommunitySearch;
use lispa\amos\community\rules\CommunityManagerRoleRule;
use lispa\amos\community\rules\CreateSubcommunitiesRule;
use lispa\amos\core\controllers\CrudController;
use lispa\amos\cwh\models\CwhRegolePubblicazione;
use lispa\amos\cwh\query\CwhActiveQuery;
use lispa\amos\cwh\utility\CwhUtil;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\search\DocumentiSearch;
use lispa\amos\documenti\utility\DocumentsUtility;
use yii\base\Controller;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;
use Yii;

class DocumentiAjaxController extends Controller
{

    private $parentId = null;

    CONST DOCUMENTI_URL = '/documenti/documenti/view';

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = ArrayHelper::merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [
                            'get-documents',
                            'get-folders',
                            'create-folder',
                            'get-translations-and-options',
                            'delete-model',
                            'delete-community',
                            'get-aree',
                            'get-subcommunities',
                        ],
                        'roles' => ['@']
                    ],
                    [
                        'allow' => true,
                        'actions' => [
                            'sessions',
                            'reset-sessions',
                        ],
                        'roles' => ['ADMIN']
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-subcommunities' => ['post', 'get']
                ]
            ]
        ]);
        return $behaviors;
    }

    public function actionGetTranslationsAndOptions($scope = null) {

        $array = [];

        $array['translations'] = [
            'LABEL--CREATE-NEW-FOLDER' => AmosDocumenti::t('amosdocumenti', 'Nuova Cartella'),
            'LABEL--UPLOAD-NEW-FILES' => AmosDocumenti::t('amosdocumenti', 'Caricamento File'),
            'LABEL--UPLOAD-MULTI-FILES' => AmosDocumenti::t('amosdocumenti', 'Caricamento Multiplo'),
            'LABEL--CONDIVISI-CON-ME' => AmosDocumenti::t('amosdocumenti', 'Condivisi con me'),
            'ERROR--NOME-CARTELLA-NON-VUOTO' => AmosDocumenti::t('amosdocumenti', 'Inserire un nome per la cartella'),
        ];

        $array['foldersOptions'] = [
            //'rename' => ['name' =>  AmosDocumenti::t('amosdocumenti','Rinomina')], //MEV
            //'sep1' => "---------", //MEV
            'open' => ['name' =>  AmosDocumenti::t('amosdocumenti','Visualizza informazioni')],
            'edit' => ['name' =>  AmosDocumenti::t('amosdocumenti','Modifica informazioni')],
            'sep2' => "---------",
            'delete' => ['name' =>  AmosDocumenti::t('amosdocumenti','Rimuovi')],
        ];

        $array['documentsOptions'] = [
            //'rename' => ['name' => AmosDocumenti::t('amosdocumenti','Rinomina')], //MEV
            //'sep1' => "---------", //MEV
            'open' => ['name' => AmosDocumenti::t('amosdocumenti','Visualizza informazioni')],
            'edit' => ['name' => AmosDocumenti::t('amosdocumenti','Modifica informazioni')],
            'sep2' => "---------",
            'upload' => ['name' => AmosDocumenti::t('amosdocumenti','Carica nuova versione')],
            'download' => ['name' => AmosDocumenti::t('amosdocumenti','Scarica')],
            'sep3' => "---------",
            'import' => ['name' =>  AmosDocumenti::t('amosdocumenti','Importa Documenti')],
            'sep3' => "---------",
            'delete' => ['name' => AmosDocumenti::t('amosdocumenti','Rimuovi')],
        ];

        return Json::encode($array);

    }

    public function actionDeleteModel() {
        $postParams = $this->isPostActions();

        $documenti = Documenti::findOne(['id' => $postParams['model-id']]);
        if($documenti) {
            if (\Yii::$app->user->can('DOCUMENTI_DELETE', ['model' => $documenti])) {
                //return Json::encode(Yii::$app->runAction('documenti/documenti/delete', ['id' => $postParams['model-id'], 'isAjaxRequest' => true]));
                return Json::encode($this->deleteFileOrFolder($postParams['model-id'], true));
            } else {
                return Json::encode([
                    'success' => false,
                    'message' => AmosDocumenti::t('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'),
                ]);
            }
        } else {
            return Json::encode([
                'success' => false,
                'message' => AmosDocumenti::t('amosdocumenti', 'Documento non più presente.'),
            ]);
        }
    }

    /**
     * Deletes an existing Documenti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     * @return \yii\web\Response
     * @throws \Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     */
    private function deleteFileOrFolder($id, $isAjaxRequest = false)
    {
        $model = Documenti::findOne(['id' => $id]);
        if ($model->is_folder) {
            $relatedDocuments = Documenti::findAll(['parent_id' => $id]);
            if (count($relatedDocuments) > 0) {
                if ($isAjaxRequest) {
                    return [
                        'success' => false,
                        'message' => AmosDocumenti::t('amosdocumenti', 'La cartella selezionata non e\' vuota. Per eliminarla, eliminare tutti i file contenuti.'),
                    ];
                }
                Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'La cartella selezionata non e\' vuota. Per eliminarla, eliminare tutti i file contenuti.'));
                return $this->redirect(Url::previous('index'));
            }
        }
        $model->delete();
        if (!$model->getErrors()) {
            if ($isAjaxRequest) {
                return [
                    'success' => true,
                ];
            }
            Yii::$app->getSession()->addFlash('success', AmosDocumenti::tHtml('amosdocumenti', 'Documento cancellato correttamente.'));
        } else {
            if ($isAjaxRequest) {
                return [
                    'success' => false,
                    'message' => AmosDocumenti::t('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'),
                ];
            }
            Yii::$app->getSession()->addFlash('danger', AmosDocumenti::tHtml('amosdocumenti', 'Non sei autorizzato a cancellare il documento.'));
        }
        return $this->redirect(Url::previous('index'));
    }

    public function actionDeleteCommunity() {
        $postParams = $this->isPostActions();

        $community = Community::findOne(['id' => $postParams['model-id']]);
        if($community) {
            if(\Yii::$app->user->can('COMMUNITY_DELETE', ['model' => $community])) {
                return Json::encode($this->deleteCommunity($postParams['model-id']));
            } else {
                return Json::encode([
                    'success' => false,
                    'message' => AmosCommunity::t('amoscommunity', 'Non hai il permesso di eliminare la stanza.'),
                ]);
            }
        } else {
            return Json::encode([
                'success' => false,
                'message' => AmosCommunity::t('amoscommunity', 'Community not found.'),
            ]);
        }
        //return Json::encode(Yii::$app->runAction('community/community/delete', ['id' => $postParams['model-id'], 'isAjaxRequest' => true]));
        //return Json::encode($this->deleteCommunity($postParams['model-id']));
    }

    /**
     * Deletes an existing Community model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * in Community model beforeDelete is overwritten to allow deletion of related models
     *
     * @param integer $id
     * @return array
     */
    private function deleteCommunity($id)
    {
        /** @var Community $model */
        $model = Community::findOne(['id' => $id]);
        if ($model) {
            if(Yii::$app->user->can('COMMUNITY_DELETE', ['model' => $model])) {
                try {
                    $model->delete();
                    return [
                        'success' => true,
                    ];
                } catch (\Exception $exception) {
                    return [
                        'success' => false,
                        'message' => AmosCommunity::t('amoscommunity', $exception->getMessage()),
                    ];
                }
            } else  {
                return [
                    'success' => false,
                    'message' => AmosCommunity::t('amoscommunity', 'Non hai il permesso di eliminare la stanza.'),
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => AmosCommunity::t('amoscommunity', 'Community not found.'),
            ];
        }
    }

    public function actionRenameModel() {
        /**
         * TODO MEV
         */
    }

    public function actionGetAree() {
        $resetScope = \Yii::$app->request->get('resetScope');
        if($resetScope === 'true') {
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh)) {
                $moduleCwh->resetCwhScopeInSession();
            }
        }

        $communitySearch = new CommunitySearch();

        /** @var \lispa\amos\cwh\AmosCwh $moduleCwh */
        $moduleCwh = Yii::$app->getModule('cwh');
        $scope = null;
        if (!empty($moduleCwh)) {
            $scope = $moduleCwh->getCwhScope();
        }
        if (!empty($scope)) {
            $scopeId = $scope['community'];
            $parentId = null;
            if(array_key_exists('links', Yii::$app->session->get('foldersPath', []))) {
                if (sizeof(Yii::$app->session->get('foldersPath', [])['links']) > 0) {
                    $parentId = Yii::$app->session->get('foldersPath', [])['links'][count(Yii::$app->session->get('foldersPath', [])['links']) - 1]['model-id'];
                    $parentId = (($parentId == "") ? null : $parentId);
                } else {
                    Yii::$app->session->set('foldersPath', [
                        'links' => [
                            [
                                'classes' => '',
                                'model-id' => '',
                                'name' => Community::findOne(['id' => $scopeId])->name
                            ],
                        ]
                    ]);
                }
            }

            $forceReset = false;
            if(empty(Community::findOne(['id' => $scopeId]))) {
                $moduleCwh = \Yii::$app->getModule('cwh');
                if (isset($moduleCwh)) {
                    $moduleCwh->resetCwhScopeInSession();
                }
                Yii::$app->session->set('stanzePath', []);
                Yii::$app->session->set('foldersPath', []);
                $forceReset = true;
                $scope = null;
                $parentId = null;
            } else {
                if(empty(Yii::$app->session->get('stanzePath', []))) {
                    Yii::$app->session->set('stanzePath', [
                        [
                            'name' => Community::findOne(['id' => $scopeId])->name,
                            'scope_id' => $scopeId,
                            'isArea' => false,
                            'isRootPathStanze' => true
                        ]
                    ]);
                    Yii::$app->session->set('foldersPath', [
                        [
                            'links' => [
                                'classes' => '',
                                'model-id' => '',
                                'name' => Community::findOne(['id' => $scopeId])->name
                            ]
                        ]
                    ]);
                }
            }

            return Json::encode([
                'forceReset' => $forceReset,
                'insideSubcommunity' => true,
                'scope' => $scopeId,
                'parentId' => $parentId,
                'routeStanze' => Yii::$app->session->get('stanzePath', []),
                'breadcrumbFolders' => Yii::$app->session->get('foldersPath', [])
            ]);
        }
        Yii::$app->session->set('stanzePath', []);
        Yii::$app->session->set('foldersPath', []);

        $result = [
            "isArea" => true,
            "canCreate" => \Yii::$app->getUser()->can('COMMUNITY_CREATE'),
        ];

        /** @var Community $area */
        /** @var Community $area */
        foreach($communitySearch->buildQuery((\Yii::$app->getUser()->can('ADMIN') ? 'admin-all' : 'all'),[])->orderBy('name')->all() as $area) {
            $permissions = [
                'open' => ['name' => AmosDocumenti::t('amosdocumenti','Visualizza informazioni')],
            ];

            if(\Yii::$app->getUser()->can('COMMUNITY_UPDATE', ['model' => $area])) {
                $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti','Modifica informazioni')];
            }

            // Da abilitare se e quando l'importatore (con node?) verra' abilitato
            /*if(\Yii::$app->getUser()->can('ADMIN')) {
                $permissions['import'] = ['name' =>  AmosDocumenti::t('amosdocumenti','Importa Documenti')];
            }*/

            $showSep2 = false;
            if($area->hasRole(\Yii::$app->user->id, [
                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                    CommunityUserMm::ROLE_EDITOR,
                    CommunityUserMm::ROLE_AUTHOR,
                ]) ||
                \Yii::$app->getUser()->can('ADMIN')) {
                $showSep2 = true;
            }

            if($showSep2) {
                $permissions['sep2'] = "---------";
            }

            if($area->hasRole(\Yii::$app->user->id, [
                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                ]) ||
                \Yii::$app->getUser()->can('ADMIN')) {
                $permissions['participants'] = ['name' => AmosDocumenti::t('amosdocumenti','Condividi con...')];
            }

            if($area->hasRole(\Yii::$app->user->id, [
                    CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                    CommunityUserMm::ROLE_EDITOR,
                    CommunityUserMm::ROLE_AUTHOR,
                ]) ||
                \Yii::$app->getUser()->can('ADMIN')) {
                $permissions['sharingGroups'] = ['name' => AmosDocumenti::t('amosdocumenti','Gruppi di condivisione')];
            }

            if($area->isCommunityManager() || \Yii::$app->getUser()->can('COMMUNITY_DELETE')) {
                $permissions['sep4'] = "---------";
                $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti','Rimuovi')];
            }

            $permissions['sep5'] = "---------";
            $permissions['cooperation'] = ['name' => AmosDocumenti::t('amosdocumenti','Collaborazione')];

            $result['areas'][] = [
                'name' => $area->name,
                'description' => strlen(strip_tags($area->description)) > 100 ? substr(strip_tags($area->description), 0, 99) . '...' : strip_tags($area->description),
                'id' => $area->id,
                'permissions' => $permissions,
            ];
        }

        return Json::encode($result);
    }

    public function actionGetSubcommunities() {
        $idArea = \Yii::$app->request->get('idArea');
        $routeStanze = \Yii::$app->request->get('routeStanze');
        $routeStanze = Json::decode($routeStanze);
        $removeStanza = \Yii::$app->request->get('removeStanza');

        $this->setScope($idArea);

        $currentCommunity = Community::findOne(['id' => $idArea]);
        $currentCommunityName = CommunitySearch::findOne(['id' => $idArea])->name;

        if($removeStanza !== "true") {
            $routeStanze[] = [
                'name' => $currentCommunityName,
                'scope_id' => $idArea,
                'isArea' => true,
            ];
        }
        Yii::$app->session->set('stanzePath', $routeStanze);

        $result = [
            "current-community-name" => $currentCommunityName,
            "isArea" => false,
            "canCreate" => \Yii::$app->getUser()->can('COMMUNITY_CREATE', ['model' => CommunitySearch::findOne(['id' => $idArea])]),
        ];

        $communitySearch = new CommunitySearch();
        $communitySearch->subcommunityMode = true;

        if(empty($currentCommunity)) {
            $moduleCwh = \Yii::$app->getModule('cwh');
            if (isset($moduleCwh)) {
                $moduleCwh->resetCwhScopeInSession();
            }
            Yii::$app->session->set('stanzePath', []);
            Yii::$app->session->set('foldersPath', []);
            return Json::encode([
                'forceReset' => true,
                'routeStanze' => Yii::$app->session->get('stanzePath', []),
                'breadcrumbFolders' => Yii::$app->session->get('foldersPath', [])
            ]);
        } else {
            $subcommunitiesQuery = $currentCommunity->getSubcommunities();
            if (!$currentCommunity->isCommunityManager()) {
                $subcommunitiesQuery->joinWith('communityUsers')->andWhere([CommunityUserMm::tableName() . '.user_id' => Yii::$app->user->id]);
            }

            //foreach($communitySearch->buildQuery('all',[])->orderBy('name')->all() as $subcommunity) {
            foreach ($subcommunitiesQuery->all() as $subcommunity) {
                $permissions = [
                    'open' => ['name' => AmosDocumenti::t('amosdocumenti', 'Visualizza informazioni')],
                ];

                if (\Yii::$app->getUser()->can('COMMUNITY_UPDATE', ['model' => $subcommunity])) {
                    $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Modifica informazioni')];
                }

                // Da abilitare se e quando l'importatore (con node?) verra' abilitato
                /*if (\Yii::$app->getUser()->can('ADMIN')) {
                    $permissions['import'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Importa Documenti')];
                }*/

                $showSep2 = false;
                if ($subcommunity->hasRole(\Yii::$app->user->id, [
                        CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                        CommunityUserMm::ROLE_EDITOR,
                        CommunityUserMm::ROLE_AUTHOR,
                    ]) ||
                    \Yii::$app->getUser()->can('ADMIN')) {
                    $showSep2 = true;
                }

                if ($showSep2) {
                    $permissions['sep2'] = "---------";
                }

                if ($subcommunity->hasRole(\Yii::$app->user->id, [
                        CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                    ]) ||
                    \Yii::$app->getUser()->can('ADMIN')) {
                    $permissions['participants'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Condividi con...')];
                }

                if ($subcommunity->hasRole(\Yii::$app->user->id, [
                        CommunityUserMm::ROLE_COMMUNITY_MANAGER,
                        CommunityUserMm::ROLE_EDITOR,
                        CommunityUserMm::ROLE_AUTHOR,
                    ]) ||
                    \Yii::$app->getUser()->can('ADMIN')) {
                    $permissions['sharingGroups'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Gruppi di condivisione')];
                }

                if (\Yii::$app->getUser()->can('COMMUNITY_DELETE', ['model' => $subcommunity])) {
                    $permissions['sep4'] = "---------";
                    $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Rimuovi')];
                }

                $permissions['sep5'] = "---------";
                $permissions['cooperation'] = ['name' => AmosDocumenti::t('amosdocumenti', 'Collaborazione')];

                $result['subcommunities'][] = [
                    'name' => $subcommunity->name,
                    'id' => $subcommunity->id,
                    'description' => strlen(strip_tags($subcommunity->description)) > 100 ? substr(strip_tags($subcommunity->description), 0, 99) . '...' : strip_tags($subcommunity->description),
                    'permissions' => $permissions,
                ];
            }
        }

        return Json::encode($result);
    }

    public function actionCreateFolder() {
        $postParams = $this->isPostActions();

        $cwhActiveQuery = new CwhActiveQuery(Documenti::className());
        $queryUsers = $cwhActiveQuery->getRecipients(CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS, [], [Community::tableName().'-'.$postParams['scope']]);
        $queryDestinatari = UserProfile::find()->andWhere([
            'in',
            'user_id',
            $queryUsers->select('user.id')->asArray()->column()
        ])->all();

        $idDestinatari = [];
        foreach ($queryDestinatari as $destinatario) {
            $idDestinatari[] = $destinatario->id;
        }

        Yii::$app->request->setBodyParams([
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
            'Documenti' => [
                'destinatari' => [
                    Community::tableName().'-'.$postParams['scope'],
                ],
                'titolo' => $postParams['folder-name'],
                'regola_pubblicazione' => CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS,
            ],
            'selection-profiles' => $idDestinatari,
        ]);

        return Json::encode(Yii::$app->runAction('documenti/documenti/create', ['isFolder' => true, 'isAjaxRequest' => true, 'regolaPubblicazione' => CwhRegolePubblicazione::ALL_USERS_IN_DOMAINS, 'parentId' => $this->parentId]));
    }

    public function setScope($scopeId) {
        $moduleCwh = \Yii::$app->getModule('cwh');
        $moduleCwh->setCwhScopeInSession([
            'community' => $scopeId, // simple cwh scope for contents filtering, required
        ],
            [
                // cwhRelation array specifying name of relation table, name of entity field on relation table and entity id field ,
                // optional for compatibility with previous versions
                'mm_name' => 'community_user_mm',
                'entity_id_field' => 'community_id',
                'entity_id' => $scopeId
            ]);
    }

    public function actionGetFolders() {
        $post = $this->isPostActions();

        $this->setScope($post['scope-id']);

        $routeFolders = JSON::decode($post['foldersPath']);
//        if(empty($routeFolders)) {
//            $routeFolders['links'][] = [
//                'classes' => "",
//                'model-id' => $post['parent-id'],
//                'name' => "prova",
//            ];
//        }

        Yii::$app->session->set('foldersPath', $routeFolders);

        /** @var ActiveQuery $folders */
        $folders = $this->getDataProviderFolders();

        $foldersFound = [
            'count' => $folders->count,
            'available' => $folders->count != 0,
            'folders' => [],
            'canCreate' => \Yii::$app->getUser()->can('DOCUMENTI_CREATE', ['model' => new Documenti()]),
        ];

        // PER VERIFICARE IL RUOLO DELL'UTENTE NELLA COMMUNITY/STANZA/AREA IN CUI CI SI TROVA
        // ED EVENTUALMENTE MOSTRARE SOLO CERTE OPZIONI NEI MENU CONTESTUALI
        // (puo tornare utile? :-) )
//        $scope = null;
//        $isCurrentUserCommunityManager = false;
//        if (!empty($moduleCwh)) {
//            $scope = $moduleCwh->getCwhScope();
//        }
//        if (!empty($scope)) {
//            $currentCommunity = CommunitySearch::findOne(['id' => $idArea]);
//            $isCurrentUserCommunityManager = $currentCommunity->hasRole(\Yii::$app->user->id, [
//                CommunityUserMm::ROLE_COMMUNITY_MANAGER,
//            ]);
//        }

        /** @var Documenti $folder */
        foreach($folders->getModels() as $folder) {

            //$folder->par
            $permissions = [
                'open' => ['name' => AmosDocumenti::t('amosdocumenti','Visualizza informazioni')],
            ];

            if(\Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $folder])) {
                $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti','Modifica informazioni')];
            }

            if(\Yii::$app->getUser()->can('DOCUMENTI_DELETE', ['model' => $folder])) {
                $permissions['sep4'] = "---------";
                $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti','Rimuovi')];
            }
            $foldersFound['folders'][] = [
                'model-id' => $folder->id,
                'name' => $folder->titolo,
                'permissions' => $permissions,
            ];
        }
        return Json::encode($foldersFound);
    }

    private function isPostActions() {
        $postParams = \Yii::$app->request->post();

        if($postParams) {
            if(array_key_exists('parent-id', $postParams) && $postParams['parent-id'] != "") {
                $this->parentId = $postParams['parent-id'];
            }
        }

        return $postParams;
    }

    public function actionGetDocuments() {
        $post = $this->isPostActions();

        $this->setScope($post['scope-id']);

        /** @var ActiveQuery $folders */
        $files = $this->getDataProviderDocuments();

        $filesFound = [
            'count' => $files->count,
            'available' => $files->count != 0,
            'files' => [],
            'canCreate' => \Yii::$app->getUser()->can('DOCUMENTI_CREATE', ['model' => new Documenti()]),
        ];

        // PER VERIFICARE IL RUOLO DELL'UTENTE NELLA COMMUNITY/STANZA/AREA IN CUI CI SI TROVA
        // ED EVENTUALMENTE MOSTRARE SOLO CERTE OPZIONI NEI MENU CONTESTUALI
        // (puo tornare utile? :-) )
//        $scope = null;
//        $isCurrentUserCommunityManager = false;
//        if (!empty($moduleCwh)) {
//            $scope = $moduleCwh->getCwhScope();
//        }
//        if (!empty($scope)) {
//            $currentCommunity = CommunitySearch::findOne(['id' => $idArea]);
//            $isCurrentUserCommunityManager = $currentCommunity->hasRole(\Yii::$app->user->id, [
//                CommunityUserMm::ROLE_COMMUNITY_MANAGER,
//            ]);
//        }

        foreach($files->getModels() as $file) {

            $permissions = [
                'open' => ['name' => AmosDocumenti::t('amosdocumenti','Visualizza informazioni')],
            ];

            if(\Yii::$app->getUser()->can('DOCUMENTI_UPDATE', ['model' => $file])) {
                if(Yii::$app->getModule('documenti')->enableDocumentVersioning) {
                    $permissions['new-version'] = ['name' => AmosDocumenti::t('amosdocumenti','Crea nuova versione')];
                } else {
                    $permissions['edit'] = ['name' => AmosDocumenti::t('amosdocumenti','Modifica informazioni')];
                }
            }

            if(\Yii::$app->getUser()->can('DOCUMENTI_DELETE', ['model' => $file])) {
                $permissions['sep4'] = "---------";
                $permissions['delete'] = ['name' => AmosDocumenti::t('amosdocumenti','Rimuovi')];
            }

            $permissions['sep5'] = "---------";
            $permissions['download'] = ['name' => AmosDocumenti::t('amosdocumenti','Scarica documento')];

            $filesFound['files'][] = [
                'name' => $file->titolo,
                'icon-class' => DocumentsUtility::getDocumentIcon($file, true),
                'url' => Url::toRoute([self::DOCUMENTI_URL, 'id' => $file->id]),
                'date' => date('d/m/Y', strtotime((isset($file->updated_at) && $file->updated_at != "") ? $file->updated_at : $file->created_at)),
                'size' => $this->getSize($file->getDocumentMainFile()->size),
                'model-id' => $file->id,
                'model-file-id' => $file->getDocumentMainFile()->id,
                'model-hash' => $file->getDocumentMainFile()->hash,
                'permissions' => $permissions,
            ];
        }
        return Json::encode($filesFound);
    }

    private function getSize($size) {
        $dimScale = [
            'b',
            'kb',
            'mb',
            'gb',
            'tb'
        ];
        $numIterations = 0;
        $size = intval($size);

        while ($size >= 920) {
            $size = $size / 1024;
            $numIterations++;
        }

        return round($size, 2) . ' ' . $dimScale[$numIterations];

    }

    /**
     * @return ActiveQuery
     */
    private function baseQuery()
    {
        /** @var ActiveQuery $query */
        $query = Documenti::find()->distinct();
        $query->andWhere(['parent_id' => $this->parentId]);
        $query = $this->addCwhQuery($query);
        return $query;
    }

    /**
     * @param ActiveQuery $query
     * @return ActiveQuery
     */
    private function addCwhQuery($query)
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        $cwhActiveQuery = null;
        $classname = Documenti::className();
        if (isset($moduleCwh)) {
            /** @var \lispa\amos\cwh\AmosCwh $moduleCwh */
            $moduleCwh->setCwhScopeFromSession();
            $cwhActiveQuery = new CwhActiveQuery($classname, ['queryBase' => $query]);
        }
        $isSetCwh = $this->isSetCwh($moduleCwh, $classname);
        if ($isSetCwh) {
            $query = $cwhActiveQuery->getQueryCwhAll();
        }
        return $query;
    }

    /**
     * @param AmosModule $moduleCwh
     * @param string $classname
     * @return bool
     */
    private function isSetCwh($moduleCwh, $classname)
    {
        if (isset($moduleCwh) && in_array($classname, $moduleCwh->modelsEnabled)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProvider($isFolderField)
    {
        $query = $this->baseQuery();
        $query->andWhere(['is_folder' => $isFolderField]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'titolo' => SORT_ASC
                ]
            ],
            'pagination' => false
        ]);
        return $dataProvider;
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProviderFolders()
    {
        return $this->getDataProvider(Documenti::IS_FOLDER);
    }

    /**
     * @return ActiveDataProvider
     */
    private function getDataProviderDocuments()
    {
        return $this->getDataProvider(Documenti::IS_DOCUMENT);
    }

    public function actionSessions() {
        pr(Yii::$app->session->get('stanzePath', []), 'routeStanze');
        pr(Yii::$app->session->get('foldersPath', []), 'routeFolders');
        die;
    }

    public function actionResetSessions() {
        DocumentsUtility::resetRoutesDocumentsExplorer();
        pr("reset successfully");
    }

}