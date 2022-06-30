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

use open20\amos\core\rules\BasicContentRule;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiAcl;
use open20\amos\documenti\utility\AclDocumentsUtility;

/**
 * Class AclReadFileRule
 * @package open20\amos\documenti\rules\acl
 */
class AclReadFileRule extends BasicContentRule
{
    /**
     * @inheritdoc
     */
    public $name = 'aclReadFile';
    
    /**
     * @inheritdoc
     */
    public function ruleLogic($user, $item, $params, $model)
    {
        // pr(get_class($model));die();
        /** @var DocumentiAcl $model */
        if ($model->isFolder()) {
            // Return false because folders can only be updated by the ACL administrator that has the basic permission directly assigned.
            return false;
        }
    
        /** @var Documenti $docModelAcl */
        $docModelAcl = AmosDocumenti::instance()->createModel('DocumentiAcl');
        $parentFolder = $docModelAcl::findOne(['id' => $model->parent_id]);
        if (is_null($parentFolder)) {
            // Can't establish the permission based on the parent folder user permissions.
            return false;
        }
        
        $aclUtility = new AclDocumentsUtility();
        $userPermissionCode = $aclUtility->userPermissionOnFolder($user, $parentFolder->id);
        
        if (
            ($userPermissionCode == AclDocumentsUtility::MANAGE_ALL_CONTENT) ||
            ($userPermissionCode == AclDocumentsUtility::MANAGE_OWN_CONTENT_READ_ALL) ||
            ($userPermissionCode == AclDocumentsUtility::READ_ALL_CONTENT)
        ) {
            return true;
        }
        // if ($model->id == 45) {
        //     pr($userPermissionCode);
        //     pr(($userPermissionCode == AclDocumentsUtility::MANAGE_OWN_CONTENT) ||
        //         ($userPermissionCode == AclDocumentsUtility::MANAGE_OWN_CONTENT_READ_ALL));
        //     // pr(($model->created_by == $user));
        //     // pr($model->attributes);
        //     die();
        // }
        
        if (($userPermissionCode == AclDocumentsUtility::MANAGE_OWN_CONTENT) && ($model->created_by == $user)) {
            return true;
        }
        
        return false;
    }
}
