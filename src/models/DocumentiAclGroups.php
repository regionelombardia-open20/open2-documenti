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
use open20\amos\core\interfaces\BaseContentModelInterface;
use open20\amos\core\interfaces\ModelLabelsInterface;
use open20\amos\core\user\User;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\i18n\grammar\DocumentiAclGroupsGrammar;
use yii\db\ActiveQuery;

/**
 * Class DocumentiAclGroups
 * This is the model class for table "documenti_acl_groups".
 * @package open20\amos\documenti\models
 */
class DocumentiAclGroups extends \open20\amos\documenti\models\base\DocumentiAclGroups implements BaseContentModelInterface, ModelLabelsInterface
{
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'name'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function getModelModuleName()
    {
        return AmosDocumenti::getModuleName();
    }
    
    /**
     * @inheritdoc
     */
    public function getModelControllerName()
    {
        return 'documenti-acl-groups';
    }
    
    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->name;
    }
    
    /**
     * @inheritDoc
     */
    public function getShortDescription()
    {
        return $this->__shortText($this->name, 100);
    }
    
    /**
     * @inheritDoc
     */
    public function getDescription($truncate)
    {
        $ret = $this->description;
        if ($truncate) {
            $ret = $this->__shortText($this->name, 200);
        }
        return $ret;
    }
    
    /**
     * @inheritDoc
     */
    public function getGrammar()
    {
        return new DocumentiAclGroupsGrammar();
    }
    
    /**
     * @param int|null $id
     * @return ActiveQuery
     */
    public function getAssociationTargetQuery($id = null)
    {
        if (!is_null($id)) {
            $this->id = $id;
        }
        
        /** @var AmosAdmin $amosAdmin */
        $amosAdmin = AmosAdmin::instance();
        
        /** @var User $userModel */
        $userModel = $amosAdmin->createModel('User');
        $userTable = $userModel::tableName();
        
        /** @var UserProfile $userProfileModel */
        $userProfileModel = $amosAdmin->createModel('UserProfile');
        $userProfileTable = $userProfileModel::tableName();
        
        $alreadyAssociatedUserIds = $this->getGroupUsers()->select(['id'])->column();
        
        /** @var ActiveQuery $query */
        $query = $userModel::find();
        $query->innerJoinWith('userProfile');
        $query->andFilterWhere(['not in', $userTable . '.id', $alreadyAssociatedUserIds]);
        $query->andWhere([$userTable . '.status' => User::STATUS_ACTIVE]);
        $query->andWhere([$userProfileTable . '.attivo' => UserProfile::STATUS_ACTIVE]);
        $query->andWhere(['<>', $userProfileTable . '.nome', UserProfileUtility::DELETED_ACCOUNT_NAME]);
        $query->orderBy([
            $userProfileTable . '.cognome' => SORT_ASC,
            $userProfileTable . '.nome' => SORT_ASC,
        ]);
        
        return $query;
    }
}
