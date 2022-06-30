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
use open20\amos\core\user\User;
use open20\amos\core\utilities\Email;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\events\AclChangePermissionsEvent;
use open20\amos\documenti\utility\AclDocumentsUtility;

/**
 * Class DocumentiAclGroupsUserMm
 * This is the model class for table "documenti_acl_groups_user_mm".
 * @package open20\amos\documenti\models
 */
class DocumentiAclGroupsUserMm extends \open20\amos\documenti\models\base\DocumentiAclGroupsUserMm
{
    /**
     * @var AclChangePermissionsEvent $aclChangePermissionsEvent
     */
    public $aclChangePermissionsEvent;
    
    /**
     * @inheritdoc
     */
    public function representingColumn()
    {
        return [
            'group_id',
            'user_id',
            'document_id'
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->aclChangePermissionsEvent = new AclChangePermissionsEvent();
        parent::init();
        $this->on(self::EVENT_BEFORE_UPDATE, [$this->aclChangePermissionsEvent, 'beforeSaveOperations']);
        $this->on(self::EVENT_AFTER_UPDATE, [$this->aclChangePermissionsEvent, 'afterSaveNotification']);
    }
    
    /**
     * @param bool $absolute
     * @return array|string
     */
    public function getFolderUrl($absolute = false)
    {
        $url = ['/documenti/documenti-acl/shared-with-me', 'parentId' => $this->document_id];
        if ($absolute) {
            $url = \Yii::$app->urlManager->createAbsoluteUrl($url);
        }
        return $url;
    }
    
    /**
     * @inheritdoc
     */
    public function beforeDelete()
    {
        $userId = $this->user_id;
        $groupId = $this->group_id;
        $folderId = $this->document_id;
        
        $beforeDeleteRes = parent::beforeDelete();
        
        // Not null deleted_at means that the delete was successfully.
        if (!empty($this->deleted_at)) {
            $userPermissionCode = AclDocumentsUtility::getPermissionCodeByModel($this);
            if ($userPermissionCode != AclDocumentsUtility::NO_PERMISSION) {
                // Se l'utente è vuoto non devo notificare nulla a nessuno.
                if (!empty($userId)) {
                    /** @var User $userModel */
                    $userModel = AmosAdmin::instance()->createModel('User');
                    /** @var User $mmUser */
                    $mmUser = $userModel::findOne($userId);
                    /** @var DocumentiAcl $folderModel */
                    $folderModel = $this->documentsModule->createModel('DocumentiAcl');
                    /** @var DocumentiAcl $folder */
                    $folder = $folderModel::findOne($folderId);
                    $from = \Yii::$app->params['email-assistenza'];
                    $to = [$mmUser->email];
                    $emailViewParams = [
                        'mmUser' => $mmUser,
                        'folder' => $folder
                    ];
                    if (!empty($groupId)) {
                        // La riga è relativa all'utente di un gruppo.
                        /** @var DocumentiAclGroups $groupModel */
                        $groupModel = $this->documentsModule->createModel('DocumentiAclGroups');
                        /** @var DocumentiAclGroups $group */
                        $group = $groupModel::findOne($groupId);
                        $subject = AmosDocumenti::txt('#notify_acl_group_no_permissions_subject', ['folderName' => $folder->getTitle()]);
                        $emailViewParams['group'] = $group;
                        $text = \Yii::$app->controller->renderPartial(AclChangePermissionsEvent::$emailPath . 'notify_email_group_no_permissions', $emailViewParams);
                        Email::sendMail($from, $to, $subject, $text);
                    } else {
                        // La riga è relativa all'utente singolo.
                        $subject = AmosDocumenti::txt('#notify_acl_user_no_permissions_subject', ['folderName' => $folder->getTitle()]);
                        $text = \Yii::$app->controller->renderPartial(AclChangePermissionsEvent::$emailPath . 'notify_email_user_no_permissions', $emailViewParams);
                        Email::sendMail($from, $to, $subject, $text);
                    }
                }
            }
        }
        
        return $beforeDeleteRes;
    }
}
