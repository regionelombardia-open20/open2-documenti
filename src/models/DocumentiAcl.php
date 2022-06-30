<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models
 * @category   CategoryName
 */

namespace open20\amos\documenti\models;

use open20\amos\admin\AmosAdmin;
use open20\amos\admin\models\UserProfile;
use open20\amos\admin\utility\UserProfileUtility;
use open20\amos\documenti\AmosDocumenti;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Class DocumentiAcl
 *
 * @property \open20\amos\documenti\models\DocumentiAclGroupsUserMm[] $documentiAclGroupsMms
 * @property \open20\amos\documenti\models\DocumentiAclGroupsUserMm[] $parentDocumentiAclGroupsMms
 * @property \open20\amos\documenti\models\DocumentiAclGroups[] $folderGroups
 *
 * @package open20\amos\documenti\models
 */
class DocumentiAcl extends Documenti
{
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_FOLDER][] = 'descrizione';
        return $scenarios;
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        $descriptionLabel = ($this->isFolder() ? AmosDocumenti::t('amosdocumenti', 'Descrizione') : AmosDocumenti::t('amosdocumenti', '#MAIN_DOCUMENT'));
        return ArrayHelper::merge(parent::attributeLabels(), [
            'descrizione' => $descriptionLabel,
        ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiAclGroupsMms()
    {
        return $this->hasMany($this->documentsModule->model('DocumentiAclGroupsUserMm'), ['document_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentDocumentiAclGroupsMms()
    {
        return $this->hasMany($this->documentsModule->model('DocumentiAclGroupsUserMm'), ['document_id' => 'parent_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolderGroups()
    {
        return $this->hasMany($this->documentsModule->model('DocumentiAclGroups'), ['id' => 'group_id'])->via('documentiAclGroupsMms');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolderUserProfiles()
    {
        return $this->hasMany(AmosAdmin::instance()->model('UserProfile'), ['user_id' => 'user_id'])
            ->via('documentiAclGroupsMms', function ($query) {
                /** @var ActiveQuery $query */
                $query->andWhere(['group_id' => null]);
            });
    }
    
    /**
     * @param int|null $id
     * @return ActiveQuery
     */
    public function getAssociationTargetQueryGroups($id = null)
    {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        $alreadyAssociatedIds = $this->getDocumentiAclGroupsMms()->select(['group_id'])->andWhere(['is not', 'group_id', null])->groupBy(['group_id'])->column();
        
        /** @var DocumentiAclGroups $documentiAclGroupsModel */
        $documentiAclGroupsModel = $this->documentsModule->model('DocumentiAclGroups');
        
        /** @var ActiveQuery $query */
        $query = $documentiAclGroupsModel::find();
        $query->andFilterWhere(['not in', 'id', $alreadyAssociatedIds]);
        $query->orderBy(['name' => SORT_ASC]);
        
        return $query;
    }
    
    /**
     * @param int|null $id
     * @return ActiveQuery
     */
    public function getAssociationTargetQueryUsers($id = null)
    {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        /** @var AmosAdmin $amosAdmin */
        $amosAdmin = AmosAdmin::instance();
        
        /** @var UserProfile $userProfileModel */
        $userProfileModel = $amosAdmin->createModel('UserProfile');
        $userProfileTable = $userProfileModel::tableName();
        
        $alreadyAssociatedIds = $this->getDocumentiAclGroupsMms()->select(['user_id'])->andWhere(['group_id' => null])->column();
        
        /** @var ActiveQuery $query */
        $query = $userProfileModel::find();
        $query->andFilterWhere(['not in', 'id', $alreadyAssociatedIds]);
        $query->andWhere([$userProfileTable . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere(['<>', $userProfileTable . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        $query->orderBy([
            $userProfileModel::tableName() . '.cognome' => SORT_ASC,
            $userProfileModel::tableName() . '.nome' => SORT_ASC,
        ]);
        
        return $query;
    }
    
    /**
     * @inheritdoc
     */
    public function getViewUrl()
    {
        return 'documenti/documenti-acl/view';
    }
    
    /**
     * @inheritdoc
     */
    public function getCreateUrl()
    {
        return 'documenti/documenti/create';
    }
    
    /**
     * @inheritdoc
     */
    public function getUpdateUrl()
    {
        return 'documenti/documenti/update';
    }
    
    /**
     * @param bool $absolute
     * @return array|string|null
     */
    public function getFolderUrl($absolute = false)
    {
        if (!$this->isFolder()) {
            return null;
        }
        $url = ['/documenti/documenti-acl/shared-with-me', 'parentId' => $this->id];
        if ($absolute) {
            $url = \Yii::$app->urlManager->createAbsoluteUrl($url);
        }
        return $url;
    }
}
