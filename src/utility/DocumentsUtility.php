<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\utility
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

namespace open20\amos\documenti\utility;

use open20\amos\attachments\FileModule;
use open20\amos\attachments\models\AttachFileCategorie;
use open20\amos\community\models\Community;
use open20\amos\core\helpers\Html;
use open20\amos\core\icons\AmosIcons;
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\core\views\grid\ActionColumn;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\documenti\models\DocumentiCategoryRolesMm;
use yii\base\BaseObject;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * Class DocumentsUtility
 * @package open20\amos\documenti\utility
 */
class DocumentsUtility extends BaseObject
{

    public static function getIconMsTeams(){
        $iconTeams = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" data-toggle="tooltip" title="File da Microsoft Teams" fill="none" class="m-l-5">
  <g clip-path="url(#clip0_2333_13015)">
    <path d="M13.9501 7.5H19.1162C19.6043 7.5 19.9999 7.92532 19.9999 8.45V13.5085C19.9999 15.4368 18.5458 17 16.752 17H16.7367C14.9429 17.0003 13.4885 15.4373 13.4883 13.509C13.4883 13.5088 13.4883 13.5087 13.4883 13.5085V7.9965C13.4883 7.72229 13.6951 7.5 13.9501 7.5Z" fill="#5059C9"/>
    <path d="M17.4417 6.5C18.5976 6.5 19.5347 5.49264 19.5347 4.25C19.5347 3.00736 18.5976 2 17.4417 2C16.2857 2 15.3486 3.00736 15.3486 4.25C15.3486 5.49264 16.2857 6.5 17.4417 6.5Z" fill="#5059C9"/>
    <path d="M10.9305 6.50001C12.6002 6.50001 13.9537 5.04493 13.9537 3.25C13.9537 1.45508 12.6002 0 10.9305 0C9.26079 0 7.90723 1.45508 7.90723 3.25C7.90723 5.04493 9.26079 6.50001 10.9305 6.50001Z" fill="#7B83EB"/>
    <path d="M14.9615 7.5H6.43408C5.95183 7.51283 5.57034 7.94306 5.58153 8.46151V14.231C5.51418 17.3421 7.80383 19.9239 10.6978 20C13.5918 19.9239 15.8814 17.3421 15.8141 14.231V8.46151C15.8253 7.94306 15.4438 7.51283 14.9615 7.5Z" fill="#7B83EB"/>
    <path opacity="0.1" d="M11.1627 7.5V15.585C11.1604 15.9558 10.9514 16.2888 10.6325 16.43C10.5309 16.4762 10.4218 16.5 10.3115 16.5H5.9906C5.93014 16.335 5.87433 16.17 5.82781 16C5.665 15.4263 5.58192 14.8298 5.58129 14.23V8.46C5.57011 7.94239 5.95098 7.51283 6.43246 7.5H11.1627Z" fill="black"/>
    <path opacity="0.2" d="M10.6976 7.5V16.085C10.6976 16.2035 10.6754 16.3208 10.6325 16.43C10.5012 16.7728 10.1913 16.9975 9.84642 17H6.20921C6.13014 16.835 6.05572 16.67 5.9906 16.5C5.92548 16.33 5.87433 16.17 5.82781 16C5.665 15.4263 5.58192 14.8298 5.58129 14.23V8.46C5.57011 7.94239 5.95098 7.51283 6.43246 7.5H10.6976Z" fill="black"/>
    <path opacity="0.2" d="M10.6976 7.5V15.085C10.694 15.5888 10.315 15.9962 9.84641 16H5.82781C5.665 15.4263 5.58192 14.8298 5.58129 14.23V8.46C5.57011 7.94239 5.95098 7.51283 6.43246 7.5H10.6976Z" fill="black"/>
    <path opacity="0.2" d="M10.2325 7.5V15.085C10.2289 15.5888 9.84991 15.9962 9.38129 16H5.82781C5.665 15.4263 5.58192 14.8298 5.58129 14.23V8.46C5.57011 7.94239 5.95098 7.51283 6.43246 7.5H10.2325Z" fill="black"/>
    <path opacity="0.1" d="M11.1623 4.91499V6.48999C11.0833 6.49499 11.0088 6.49999 10.9298 6.49999C10.8507 6.49999 10.7763 6.495 10.6972 6.48999C10.5402 6.47879 10.3845 6.45201 10.2321 6.40999C9.29023 6.17022 8.5121 5.46013 8.13907 4.5C8.07489 4.33876 8.02506 4.17134 7.99023 4H10.3112C10.7805 4.00191 11.1605 4.41044 11.1623 4.91499Z" fill="black"/>
    <path opacity="0.2" d="M10.6978 5.41499V6.48999C10.5408 6.47879 10.3851 6.45202 10.2327 6.41C9.29081 6.17022 8.51268 5.46014 8.13965 4.5H9.84662C10.316 4.50191 10.696 4.91045 10.6978 5.41499Z" fill="black"/>
    <path opacity="0.2" d="M10.6978 5.41499V6.48999C10.5408 6.47879 10.3851 6.45202 10.2327 6.41C9.29081 6.17022 8.51268 5.46014 8.13965 4.5H9.84662C10.316 4.50191 10.696 4.91045 10.6978 5.41499Z" fill="black"/>
    <path opacity="0.2" d="M10.2327 5.415V6.41C9.29081 6.17022 8.51268 5.46014 8.13965 4.5H9.38151C9.85086 4.50192 10.2309 4.91046 10.2327 5.415Z" fill="black"/>
    <path d="M0.852554 4.5H9.37999C9.85085 4.5 10.2325 4.91033 10.2325 5.4165V14.5835C10.2325 15.0897 9.85084 15.5 9.37999 15.5H0.852554C0.381698 15.5 0 15.0897 0 14.5835V5.4165C0 4.91033 0.381706 4.5 0.852554 4.5Z" fill="url(#paint0_linear_2333_13015)"/>
    <path d="M7.36049 7.98899H5.65583V12.979H4.56979V7.98899H2.87305V7.021H7.36049V7.98899Z" fill="white"/>
  </g>
  <defs>
    <linearGradient id="paint0_linear_2333_13015" x1="1.7776" y1="3.78387" x2="9.20512" y2="15.7505" gradientUnits="userSpaceOnUse">
      <stop stop-color="#5A62C3"/>
      <stop offset="0.5" stop-color="#4D55BD"/>
      <stop offset="1" stop-color="#3940AB"/>
    </linearGradient>
    <clipPath id="clip0_2333_13015">
      <rect width="20" height="20" fill="white"/>
    </clipPath>
  </defs>
</svg>';
        return $iconTeams;
    }

    /**
     * @param Documenti $model
     * @param bool $onlyIconName If true return only the icon name ready to use in AmosIcon::show method.
     * @return string
     */
    public static function getDocumentIcon($model, $onlyIconName = false)
    {
        $iconName     = 'file-o';
        $classDocIcon = 'icon icon-link icon-sm mdi mdi-file-link';

        if ($model->is_folder) {
            $folderIconName = 'folder';
            if ($onlyIconName) {
                return $folderIconName;
            }
            if ($model->drive_file_id) {
                return AmosIcons::show($folderIconName, ['class' => 'icon_widget_graph'], 'dash').AmosIcons::show('google-drive',
                        ['class' => 'google-sync'], 'am');
            }
            return AmosIcons::show($folderIconName, ['class' => 'icon_widget_graph'], 'dash');
        }

        if (!(empty($model->link_document))) {
            $linkIcon = 'doc-www';
            if ($onlyIconName === false) {
                if (!empty(\Yii::$app->params['befe'])) {
                    return Html::tag('span', null, ['class' => $classDocIcon]);
                } else {
                    $linkIcon = AmosIcons::show($linkIcon, ['class' => 'icon_widget_graph'], 'dash');
                }
            }

            return $linkIcon;
        }

        $documentFile = $model->getDocumentMainFile();        
        if (!is_null($documentFile)) {
            $docExtension = strtolower($documentFile->type);

            $extensions = [
                'doc' => 'file-doc-o',
                'docx' => 'file-docx-o',
                'rtf' => 'file-rtf-o',
                'xls' => 'file-xls-o',
                'xlsx' => 'file-xlsx-o',
                'txt' => 'file-txt-o',
                'pdf' => 'file-pdf-o2',
            ];

            

            $extensionsBefe = [
                'jpg' => 'icon icon-image icon-sm mdi mdi-file-image',
                'png' => 'icon icon-image icon-sm mdi mdi-file-image',
                'jpeg' => 'icon icon-image icon-sm mdi mdi-file-image',
                'svg' => 'icon icon-image icon-sm mdi mdi-file-image',
                'pdf' => 'icon icon-pdf icon-sm mdi mdi-file-pdf-box',
                'docx' => 'icon icon-word icon-sm mdi mdi-file-word',
                'doc' => 'icon icon-word icon-sm mdi mdi-file-word',
                'ppt' => 'icon icon-powerpoint icon-sm mdi mdi-file-powerpoint',
                'pptx' => 'icon icon-powerpoint icon-sm mdi mdi-file-powerpoint',
                'xls' => 'icon icon-excel icon-sm mdi mdi-file-excel',
                'xlsx' => 'icon icon-excel icon-sm mdi mdi-file-excel',
                'csv' => 'icon icon-black icon-sm mdi mdi-file-delimited',
                'rtf' => 'icon icon-black icon-sm mdi mdi-file-document',
                'txt' => 'icon icon-black icon-sm mdi mdi-file-document',
                'zip' => 'icon icon-link icon-sm mdi mdi-folder-zip',
                'rar' => 'icon icon-link icon-sm mdi mdi-folder-zip',
            ];

            if (!empty(\Yii::$app->params['befe'])) {
                if (!empty($extensionsBefe[$docExtension])) {
                    $classDocIcon = $extensionsBefe[$docExtension];
                } else {
                    $classDocIcon = 'icon icon-link icon-sm mdi mdi-file-link';
                }
            }

            if (isset($extensions[$docExtension])) {
                $iconName = $extensions[$docExtension];
            }
        }else{
            $classDocIcon = 'icon icon-black icon-sm mdi mdi-file-document';
        }

        if ($onlyIconName) {
            return $iconName;
        }

        if ($model->drive_file_id) {
            return AmosIcons::show($iconName,
                    [
                    'class' => 'icon_widget_graph'
                    ], 'dash')
                .AmosIcons::show('google-drive', [
                    'class' => 'google-sync'
                    ], 'am'
            );
        } else {
            if (!empty(\Yii::$app->params['befe'])) {
                return Html::tag('span', null, ['class' => $classDocIcon, 'title' => $docExtension]);
            }
            return AmosIcons::show($iconName, ['class' => 'icon_widget_graph'], 'dash');
        }
    }

    /**
     * @param $idUserIdList
     * @param $subject
     * @param $text
     * @param array $files
     * @param bool $queue
     */
    public static function sendEmail($idUserIdList, $subject, $text, $files = [], $queue = false)
    {
        $emailList = [];
        foreach ($idUserIdList as $id) {
            $user = User::findOne($id);
            if ($user) {
                $emailList [] = $user->email;
            }
        }

        $emaiFrom = \Yii::$app->params['supportEmail'];

        /** @var  $email Email */
        try {
            $emailModule = \Yii::$app->getModule('email');
            $email       = new Email();
            $sent        = $email->sendMail(
                $emaiFrom, $emailList, $subject, $text, $files, [], [], 0, $queue
            );
            return $sent;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * This method returns an array with document categories ids used
     * in the documents present in the platform.
     * @return int[]
     */
    public static function getDocumentsCategoriesIds()
    {
        $query                  = new Query();
        $query->select(['documenti_categorie_id'])->distinct();
        $query->from(Documenti::tableName());
        $query->where(['deleted_at' => null]);
        $documentsCategoriesIds = $query->column();

        return $documentsCategoriesIds;
    }

    /**
     * This method returns an array with document categories ready for select used
     * in the documents present in the platform.
     * @return array
     */
    public static function getSearchCategoriesReadyForSelect()
    {
        $documentsCategoriesIds       = self::getDocumentsCategoriesIds();
        $documentsCategoriesForSelect = [];
        /** @var AmosDocumenti $documentsModule */
        $documentsModule              = AmosDocumenti::instance();
        foreach ($documentsCategoriesIds as $documentCategoryId) {
            /** @var DocumentiCategorie $documentiCategorieModel */
            $documentiCategorieModel = $documentsModule->createModel('DocumentiCategorie');
            $documentCategory        = $documentiCategorieModel::findOne($documentCategoryId);
            if (!is_null($documentCategory)) {
                $documentsCategoriesForSelect[$documentCategory->id] = $documentCategory->titolo;
            }
        }

        return $documentsCategoriesForSelect;
    }

    /**
     * Return the options for the specified action column button.
     * @param string $type
     * @return array
     */
    public static function getGridActionColumnsButtonsOptions($type)
    {
        $options             = [];
        $actionColumnsObject = new ActionColumn();

        switch ($type) {
            case 'view':
                $options = $actionColumnsObject->viewOptions;
                break;
            case 'update':
                $options = $actionColumnsObject->updateOptions;
                break;
            case 'delete':
                $options = $actionColumnsObject->deleteOptions;
                break;
        }

        return $options;
    }

    /**
     *
     */
    public static function resetRoutesDocumentsExplorer()
    {
        \Yii::$app->session->set('stanzePath', []);
        \Yii::$app->session->set('foldersPath', []);
    }

    /**
     * @return ActiveQuery
     * @throws \yii\base\InvalidConfigException
     */
    public static function getDocumentiCategorie()
    {
        /** @var DocumentiCategorie $documentiCategorieModel */
        $documentiCategorieModel = AmosDocumenti::instance()->createModel('DocumentiCategorie');

        /** @var ActiveQuery $query */
        $query = $documentiCategorieModel::find();
        if (\Yii::$app->getModule('documenti')->filterCategoriesByRole) {
            //check enabled role for category active - user can publish
            //under a category if there's at least one match betwwn category and user roles
            $query->joinWith('documentiCategoryRolesMms')
                ->innerJoin('auth_assignment',
                    'item_name='.DocumentiCategoryRolesMm::tableName()
                    .'.role and user_id ='.\Yii::$app->user->id
            );
        }
        if (\Yii::$app->getModule('documenti')->enableCategoriesForCommunity) {
            $moduleCwh       = \Yii::$app->getModule('cwh');
            $moduleCommunity = \Yii::$app->getModule('community');

            if ($moduleCwh && $moduleCommunity) {
                $scope = $moduleCwh->getCwhScope();
                if (!empty($scope) && isset($scope['community'])) {
                    $isCommunityManager = DocumentsUtility::isCommunityManager($scope['community']);
                    if (\Yii::$app->getModule('documenti')->showAllCategoriesForCommunity) {
                        $query->joinWith('documentiCategoryCommunityMms')->andWhere([
                            'OR',
                            ['IS', 'community_id', null],
                            ['community_id' => $scope['community']]
                        ]);
                    } else {
                        $query2 = clone $query;
                        $count  = $query2
                                ->joinWith('documentiCategoryCommunityMms')
                                ->andWhere(['community_id' => $scope['community']])->count();

                        // if you have at least a category for this community show only them
                        if ($count > 0) {
                            $query
                                ->joinWith('documentiCategoryCommunityMms')
                                ->andWhere(['community_id' => $scope['community']]);
                            if (!$isCommunityManager) {
                                $query->andWhere(['visible_to_participant' => true]);
                            }
                        } else {
                            // If you don't have categories for this specific community,
                            // show all the categories the the aren't assigned to some community
                            $query->joinWith('documentiCategoryCommunityMms')
                                ->andWhere(['IS', 'community_id', NULL]);
                        }
                    }
                } else {
                    // if you are on dashboard
                    $query
                        ->joinWith('documentiCategoryCommunityMms')
                        ->andWhere(['IS', 'community_id', null]);
                }
            }
            //check enabled role for category active - user can publish under
            //a category if there's at least one match betwwn category and user roles
        }

        return $query;
    }

    /**
     * @param $community_id
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public static function isCommunityManager($community_id)
    {
        $count = \open20\amos\community\models\CommunityUserMm::find()
                ->andWhere(['community_id' => $community_id])
                ->andWhere(['user_id' => \Yii::$app->user->id])
                ->andWhere(['role' => \open20\amos\community\models\Community::ROLE_COMMUNITY_MANAGER])->count();

        return ($count > 0);
    }

    public static function getLinkOptions($model)
    {
        $linkOptions = ['href' => '#', 'title' => $model->titolo, 'alt' => $model->titolo, 'class' => 'js-pjax'];
        if ($model->is_folder) {
            $href = [
                '/documenti/documenti/view',
                'parentId' => $model->id
            ];
            if (\Yii::$app->request->get('currentView')) {
                $href['currentView'] = \Yii::$app->request->get('currentView');
            }
            $linkOptions['href'] = \Yii::$app->urlManager->createUrl($href);
        } else {
            $linkOptions['data-pjax'] = '0';
            if (!is_null($model->getDocumentMainFile())) {

                $linkOptions['href'] = $model->getDocumentMainFile()->getUrl('original',true);
            }
            if (!empty($model->link_document)) {
                $linkOptions['href']   = $model->link_document;
                $linkOptions['target'] = '_blank';
            }
        }

        return $linkOptions;
    }

    /**
     * @return bool|mixed
     */
    public static function canCreateForExplorer()
    {
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            $isSetScope = (!empty($scope)) ? true : false;
        }
        $canCreateController = false;
        if (\Yii::$app->controller instanceof \open20\amos\core\controllers\CrudController) {
            $forceCreateNewButtonWidget = false;
            if (isset(\Yii::$app->view->params['forceCreateNewButtonWidget']) && (\Yii::$app->view->params['forceCreateNewButtonWidget'] === true)) {
                $forceCreateNewButtonWidget = \Yii::$app->view->params['forceCreateNewButtonWidget'];
            }
            $canCreateController = $forceCreateNewButtonWidget || \Yii::$app->controller->can('CREATE');
        }
        $isGuest = \Yii::$app->user->isGuest;
        $canCreate = (isset(\Yii::$app->view->params['canCreate'])) ? \Yii::$app->view->params['canCreate'] : !$isGuest && $canCreateController;
        if ($isSetScope && !empty($scope) && isset($scope['community'])) {
            $community = \open20\amos\community\models\Community::findOne($scope['community']);
            if (!empty($community)) {
                $canCreateMemberActive = \open20\amos\community\utilities\CommunityUtil::userIsCommunityMemberActive($community->id, \Yii::$app->user->id);
                $canCreate = $canCreate && $canCreateMemberActive;
            }
        }
        return $canCreate;
    }

    /**
     * @param $community
     * @param $percorso
     * @return mixed|string
     */
    public static function getPercorsoCommunity($community, $percorso) {
            if (!is_null($community)){
                $percorso = '/' . $community->name . $percorso;
                $communityPadre = Community::findOne(['id' => $community->parent_id]);
                self::getPercorsoCommunity($communityPadre, $percorso);
            }
            if (!is_null($community->parent_id)){
                $communityPadre = Community::findOne(['id' => $community->parent_id]);
                $percorso = '/' . $communityPadre->name . $percorso;

            }
        return $percorso;
    }

    /**
     * @param $model Documenti
     * @return void
     */
    public static function categorizeMainDocumentAttachment($model) {
        $attachmentsModule = \Yii::$app->getModule(FileModule::getModuleName());
        if($attachmentsModule) {
            // Verifico che il model sia stato configurato e la categorizzazione abilitata
            $categorizationEnabled = ((!empty($attachmentsModule->categorizationEnabled))
                && (!empty($attachmentsModule->categorizationModelsEnabled))
                && (in_array($model->className(),
                    $attachmentsModule->categorizationModelsEnabled)));

            if($categorizationEnabled) {
                $mainDocument = $model->getDocumentMainFile();
                if(!is_null($mainDocument)) {
                    $cat = DocumentiCategorie::findOne($model->documenti_categorie_id);
                    $catAttachments = AttachFileCategorie::find()
                        ->andWhere(['like binary', 'titolo', $cat->titolo])
                        ->one();
                    // Se non esiste la categoria, essa viene creata
                    if (!$catAttachments) {
                        $attachFileCategorieModel = new AttachFileCategorie();
                        $attachFileCategorieModel->titolo = $cat->titolo;
                        $attachFileCategorieModel->save();
                        $idCategory = $attachFileCategorieModel->id;
                    } else {
                        $idCategory = $catAttachments->id;
                    }
                    $mainDocument->attach_file_categorie_id = $idCategory;
                    $mainDocument->save();
                }
            }
        }
    }
}
