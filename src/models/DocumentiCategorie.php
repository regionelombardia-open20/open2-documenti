<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\models
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

namespace open20\amos\documenti\models;

use yii\helpers\ArrayHelper;
use open20\amos\attachments\behaviors\FileBehavior;
use open20\amos\documenti\AmosDocumenti;

/**
 * This is the model class for table "documenti_categorie".
 */
class DocumentiCategorie extends \open20\amos\documenti\models\base\DocumentiCategorie
{

    /**
     * @var mixed $file File.
     */
    public $file;

    /**
     * @var $documentMainFile
     */
    public $documentCategoryImage;
    public $documentiCategoryCommunities;
    public $documentiCategoryRoles;
    public $visibleToCommunityRole;

    /**
     */
    public function rules()
    {
        $rules = ArrayHelper::merge(parent::rules(), [
            [['documentCategoryImage'], 'file', 'extensions' => 'jpeg, jpg, png, gif','maxFiles' => 1, 'mimeTypes'=>'image/*'],
            [['documentiCategoryRoles' ,'documentiCategoryCommunities','visibleToCommunityRole'], 'safe'],
            [['years_countdown_unpublishing'],'required',
                'when' => function ($model) {
                    return $model->enable_countdown_unpublishing;
                }, 'whenClient' => "function(attribute, value) {
                        var enable = $('#documenticategorie-enable_countdown_unpublishing').prop('checked');                                
                        return enable;
                }",
                'message' => AmosDocumenti::t('amosdocumenti', '#years_countdown_unpublishing_required')
            ],
        ]);
        if($this->enable_countdown_unpublishing) {
            $rules = ArrayHelper::merge($rules, [['years_countdown_unpublishing'], 'integer', 'min' => 1, 'max' => 99]);
        }
        return $rules;
    }

    /**
     * Ritorna l'url dell'avatar.
     *
     * @param string $dimension Dimensione. Default = small.
     * @return string Ritorna l'url.
     */
    public function getAvatarUrl($dimension = 'small')
    {
        $url = '/img/img_default.jpg';
        if (!is_null($this->documentCategoryImage)) {
            $url = $this->documentCategoryImage->getUrl($dimension, false, true);
        }
        return $url;
    }

    /**
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'fileBehavior' => [
                'class' => FileBehavior::className()
            ],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();

        $this->documentCategoryImage = $this->getDocumentCategoryImage()->one();
    }

    /**
     * Getter for $this->documentCategoryImage;
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentCategoryImage()
    {
        return $this->hasOneFile('documentCategoryImage');
    }


    /**
     *
     */
    public function saveDocumentiCategorieCommunityMm(){
        /** @var DocumentiCategoryCommunityMm $documentiCategoryCommunityMmModel */
        $documentiCategoryCommunityMmModel = $this->documentsModule->createModel('DocumentiCategoryCommunityMm');
        $documentiCategoryCommunityMmModel::deleteAll(['documenti_categorie_id' => $this->id]);
        foreach ((Array) $this->documentiCategoryCommunities as $community_id){
            /** @var DocumentiCategoryCommunityMm $documentiCommunityMm */
            $documentiCommunityMm = $this->documentsModule->createModel('DocumentiCategoryCommunityMm');
            $documentiCommunityMm->documenti_categorie_id = $this->id;
            $documentiCommunityMm->community_id = $community_id;

            $documentiCommunityMm->visible_to_cm = 0;
            if(array_search('COMMUNITY_MANAGER', $this->visibleToCommunityRole) !== false){
                $documentiCommunityMm->visible_to_cm = 1;
            }
            $documentiCommunityMm->visible_to_participant = 0;
            if(array_search('PARTICIPANT', $this->visibleToCommunityRole) !== false){
                $documentiCommunityMm->visible_to_participant = 1;

            }
            $documentiCommunityMm->save();
        }
    }

    /**
     *  load documentiCategoryCommunities for Select2
     */
    public function loadDocumentiCategoryCommunities(){
        $community_ids = [];
        foreach ( (Array) $this->documentiCategoryCommunityMms as $categoryCommunityMms){
            $community_ids []= $categoryCommunityMms->community_id;
            if($categoryCommunityMms->visible_to_cm){
                $this->visibleToCommunityRole []= 'COMMUNITY_MANAGER';
            }
            if($categoryCommunityMms->visible_to_participant){
                $this->visibleToCommunityRole []= 'PARTICIPANT';
            }
        };
        $this->documentiCategoryCommunities = $community_ids;

    }

    /**
     *
     */
    public function saveDocumentiCategorieRolesMm(){
        /** @var DocumentiCategoryRolesMm $documentiCategoryRolesMmModel */
        $documentiCategoryRolesMmModel = $this->documentsModule->createModel('DocumentiCategoryRolesMm');
        $documentiCategoryRolesMmModel::deleteAll(['documenti_categorie_id' => $this->id]);
        foreach ((Array) $this->documentiCategoryRoles as $role){
            /** @var DocumentiCategoryRolesMm $newsCommunityMm */
            $newsCommunityMm = $this->documentsModule->createModel('DocumentiCategoryRolesMm');
            $newsCommunityMm->documenti_categorie_id = $this->id;
            $newsCommunityMm->role = $role;
            $newsCommunityMm->save();
        }
    }

    /**
     *  load documentiCategoryRoles for Select2
     */
    public function loadDocumentiCategoryRoles(){
        $roles = [];
        foreach ($this->documentiCategoryRolesMms as $categoryRolesMms){
            $roles []= $categoryRolesMms->role;
        };
        $this->documentiCategoryRoles = $roles;
    }
}
