<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\rules\acl
 * @category   CategoryName
 */

namespace open20\amos\documenti\rules\acl;

use open20\amos\documenti\models\DocumentiAcl;
use open20\amos\documenti\utility\AclDocumentsUtility;
use yii\rbac\Rule;

/**
 * Class AclCreateFileRule
 * @package open20\amos\documenti\rules\acl
 */
class AclCreateFileRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'aclCreateFile';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        if (isset($params['model'])) {
            /** @var DocumentiAcl $model */
            $model = $params['model'];
            if (!$model->id) {
                $post = \Yii::$app->getRequest()->post();
                $get = \Yii::$app->getRequest()->get();
                if (isset($get['parentId'])) {
                    $model = $this->instanceModel($model, $get['parentId']);
                } elseif (isset($post['parentId'])) {
                    $model = $this->instanceModel($model, $post['parentId']);
                }
            }
            
            if (empty($model)) {
                // Can't establish the permission based on the folder user permissions.
                return false;
            }
            
            if (!$model->isFolder()) {
                // Return false because this rule checks if a non ACL administrator user can create a document in the provided folder.
                // Then the model must be a folder to do the proper check.
                return false;
            }
            
            // if (!$model->id) {
            //     // Can't establish the permission based on the folder user permissions.
            //     return false;
            // }
            
            $aclUtility = new AclDocumentsUtility();
            $userPermissionCode = $aclUtility->userPermissionOnFolder($user, $model->id);
            
            if (
                ($userPermissionCode == AclDocumentsUtility::MANAGE_ALL_CONTENT) ||
                ($userPermissionCode == AclDocumentsUtility::MANAGE_OWN_CONTENT_READ_ALL) ||
                ($userPermissionCode == AclDocumentsUtility::MANAGE_OWN_CONTENT)
            ) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * @param DocumentiAcl $model
     * @param int $modelId
     * @return mixed
     */
    protected function instanceModel($model, $modelId)
    {
        $modelClass = $model->className();
        /** @var DocumentiAcl $modelClass */
        $instancedModel = $modelClass::findOne($modelId);
        if (!is_null($instancedModel)) {
            $model = $instancedModel;
        }
        return $model;
    }
}
