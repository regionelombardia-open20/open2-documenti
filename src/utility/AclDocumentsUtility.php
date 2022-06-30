<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\utility
 * @category   CategoryName
 */

namespace open20\amos\documenti\utility;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\user\User;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiAclGroupsUserMm;
use yii\base\BaseObject;
use yii\db\Expression;
use yii\db\Query;

/**
 * Class AclDocumentsUtility
 *
 * MANAGE_ALL_CONTENT, MANAGE_OWN_CONTENT_READ_ALL, READ_ALL_CONTENT are permissions that allow the user to read all the shared with him folder content.
 * Only MANAGE_OWN_CONTENT permission has a restriction on the shared folder content. It allows the user to read only the files created by him.
 *
 * @package open20\amos\documenti\utility
 */
class AclDocumentsUtility extends BaseObject
{
    const NO_PERMISSION = 0;
    const MANAGE_ALL_CONTENT = 1;
    const MANAGE_OWN_CONTENT_READ_ALL = 2;
    const MANAGE_OWN_CONTENT = 3;
    const READ_ALL_CONTENT = 4;
    
    /**
     * @var AmosDocumenti $documentsModule
     */
    protected $documentsModule;
    
    /**
     * @var AmosAdmin $adminModule
     */
    protected $adminModule;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->documentsModule = AmosDocumenti::instance();
        $this->adminModule = AmosAdmin::instance();
        parent::init();
    }
    
    /**
     * @param int $userId
     * @param int $folderId
     * @return int
     * @throws \yii\base\InvalidConfigException
     */
    public function userPermissionOnFolder($userId, $folderId)
    {
        /** @var Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        
        /** @var User $userModel */
        $userModel = $this->adminModule->createModel('User');
        
        /** @var Documenti $folder */
        $folder = $documentiModel::findOne($folderId);
        if (is_null($folder)) {
            return self::NO_PERMISSION;
        }
        
        /** @var User $user */
        $user = $userModel::findOne($userId);
        if (is_null($user)) {
            return self::NO_PERMISSION;
        }
        
        /** @var DocumentiAclGroupsUserMm $documentiAclGroupsUserMmModel */
        $documentiAclGroupsUserMmModel = $this->documentsModule->createModel('DocumentiAclGroupsUserMm');
        
        $query = new Query();
        $query->select(new Expression('user_id, document_id, SUM(update_folder_content) AS can_update_folder_content, SUM(upload_folder_files) AS can_upload_folder_files, SUM(read_folder_files) AS can_read_folder_files'));
        $query->from($documentiAclGroupsUserMmModel::tableName());
        $query->andWhere(['deleted_at' => null]);
        $query->andWhere(['user_id' => $userId, 'document_id' => $folderId]);
        $permissions = $query->one();
        
        if (empty($permissions['user_id']) || empty($permissions['document_id'])) {
            return self::NO_PERMISSION;
        }
        
        return self::getPermissionCodeByFieldValues(
            $permissions['can_update_folder_content'],
            $permissions['can_upload_folder_files'],
            $permissions['can_read_folder_files']
        );
    }
    
    /**
     * This method returns the user permission code by the params passed. These params represent the fields permissions.
     * @param int $updateFolderContent
     * @param int $uploadFolderFiles
     * @param int $readFolderFiles
     * @return int
     */
    public static function getPermissionCodeByFieldValues(int $updateFolderContent, int $uploadFolderFiles, int $readFolderFiles)
    {
        if ($updateFolderContent > 0) {
            return self::MANAGE_ALL_CONTENT;
        }
        
        if (($uploadFolderFiles > 0) && ($readFolderFiles > 0)) {
            return self::MANAGE_OWN_CONTENT_READ_ALL;
        }
        
        if (($uploadFolderFiles > 0) && (!$readFolderFiles)) {
            return self::MANAGE_OWN_CONTENT;
        }
        
        if ((!$uploadFolderFiles) && ($readFolderFiles > 0)) {
            return self::READ_ALL_CONTENT;
        }
        
        return self::NO_PERMISSION;
    }
    
    /**
     * This method returns the user permission code by the DocumentiAclGroupsUserMm permissions fields.
     * @param DocumentiAclGroupsUserMm $model
     * @return int
     */
    public static function getPermissionCodeByModel(DocumentiAclGroupsUserMm $model)
    {
        return self::getPermissionCodeByFieldValues(
            $model->update_folder_content,
            $model->upload_folder_files,
            $model->read_folder_files
        );
    }
}
