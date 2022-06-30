<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\events
 * @category   CategoryName
 */

namespace open20\amos\documenti\events;

use open20\amos\core\helpers\Html;
use open20\amos\core\utilities\Email;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\DocumentiAclGroupsUserMm;
use open20\amos\documenti\utility\AclDocumentsUtility;
use yii\base\BaseObject;
use yii\base\ModelEvent;
use yii\db\AfterSaveEvent;

/**
 * Class AclChangePermissionsEvent
 * @package open20\amos\documenti\events
 */
class AclChangePermissionsEvent extends BaseObject
{
    /**
     * @var string $emailPath
     */
    public static $emailPath = '@vendor/open20/amos-documenti/src/views/documenti-acl/email/';
    
    /**
     * @var array $oldModelAttributes
     */
    private $oldModelAttributes;
    
    /**
     * @param ModelEvent $event
     */
    public function beforeSaveOperations($event)
    {
        $this->oldModelAttributes = $event->sender->getOldAttributes();
    }
    
    /**
     * @param AfterSaveEvent $event
     */
    public function afterSaveNotification($event)
    {
        /** @var DocumentiAclGroupsUserMm $mmModel */
        $mmModel = $event->sender;
        
        // Empty deleted_at means that this is a real update of a model and not the update of a soft delete.
        if ($this->isChangedPermissions($mmModel) && !$mmModel->deleted_at) {
            $userPermissionCode = AclDocumentsUtility::getPermissionCodeByModel($mmModel);
            switch ($userPermissionCode) {
                case AclDocumentsUtility::MANAGE_ALL_CONTENT:
                case AclDocumentsUtility::MANAGE_OWN_CONTENT_READ_ALL:
                case AclDocumentsUtility::MANAGE_OWN_CONTENT:
                case AclDocumentsUtility::READ_ALL_CONTENT:
                    $this->sendNotifyNewPermissions($mmModel);
                    break;
                case AclDocumentsUtility::NO_PERMISSION:
                    $this->sendNotifyNoPermission($mmModel);
                    break;
            }
        }
    }
    
    /**
     * @param DocumentiAclGroupsUserMm $mmModel
     * @return bool
     */
    public function isChangedPermissions(DocumentiAclGroupsUserMm $mmModel)
    {
        return (
            ($this->oldModelAttributes['update_folder_content'] != $mmModel->update_folder_content) ||
            ($this->oldModelAttributes['upload_folder_files'] != $mmModel->upload_folder_files) ||
            ($this->oldModelAttributes['read_folder_files'] != $mmModel->read_folder_files)
        );
    }
    
    /**
     * @param DocumentiAclGroupsUserMm $mmModel
     * @return bool
     */
    public function sendNotifyNewPermissions(DocumentiAclGroupsUserMm $mmModel)
    {
        $ok = true;
        
        // Se l'utente è vuoto non devo notificare nulla a nessuno.
        if (!empty($mmModel->user_id)) {
            $allowedPermissions = $this->getTranslatedAllowedPermissions($mmModel);
            $mmUser = $mmModel->user;
            $folder = $mmModel->folder;
            $from = \Yii::$app->params['email-assistenza'];
            $to = [$mmUser->email];
            $emailViewParams = [
                'mmUser' => $mmUser,
                'folder' => $folder,
                'allowedPermissions' => $allowedPermissions
            ];
            if (!empty($mmModel->group_id)) {
                // La riga è relativa all'utente di un gruppo.
                $group = $mmModel->group;
                $subject = AmosDocumenti::txt('#notify_acl_group_new_permissions_subject', ['folderName' => $folder->getTitle()]);
                $emailViewParams['group'] = $group;
                $text = \Yii::$app->controller->renderPartial(self::$emailPath . 'notify_email_group_new_permissions', $emailViewParams);
                $ok = Email::sendMail($from, $to, $subject, $text);
            } else {
                // La riga è relativa all'utente singolo.
                $subject = AmosDocumenti::txt('#notify_acl_user_new_permissions_subject', ['folderName' => $folder->getTitle()]);
                $text = \Yii::$app->controller->renderPartial(self::$emailPath . 'notify_email_user_new_permissions', $emailViewParams);
                $ok = Email::sendMail($from, $to, $subject, $text);
            }
        }
        
        return $ok;
    }
    
    /**
     * @param DocumentiAclGroupsUserMm $mmModel
     * @return bool
     */
    public function sendNotifyNoPermission(DocumentiAclGroupsUserMm $mmModel)
    {
        $ok = true;
        
        // Se l'utente è vuoto non devo notificare nulla a nessuno.
        if (!empty($mmModel->user_id)) {
            $mmUser = $mmModel->user;
            $folder = $mmModel->folder;
            $from = \Yii::$app->params['email-assistenza'];
            $to = [$mmUser->email];
            $emailViewParams = [
                'mmUser' => $mmUser,
                'folder' => $folder
            ];
            if (!empty($mmModel->group_id)) {
                // La riga è relativa all'utente di un gruppo.
                $group = $mmModel->group;
                $subject = AmosDocumenti::txt('#notify_acl_group_no_permissions_subject', ['folderName' => $folder->getTitle()]);
                $emailViewParams['group'] = $group;
                $text = \Yii::$app->controller->renderPartial(self::$emailPath . 'notify_email_group_no_permissions', $emailViewParams);
                $ok = Email::sendMail($from, $to, $subject, $text);
            } else {
                // La riga è relativa all'utente singolo.
                $subject = AmosDocumenti::txt('#notify_acl_user_no_permissions_subject', ['folderName' => $folder->getTitle()]);
                $text = \Yii::$app->controller->renderPartial(self::$emailPath . 'notify_email_user_no_permissions', $emailViewParams);
                $ok = Email::sendMail($from, $to, $subject, $text);
            }
        }
        
        return $ok;
    }
    
    /**
     * @param DocumentiAclGroupsUserMm $mmModel
     * @return string
     */
    protected function getTranslatedAllowedPermissions(DocumentiAclGroupsUserMm $mmModel)
    {
        $allowedPermissionsArray = [];
        if ($mmModel->update_folder_content == 1) {
            $allowedPermissionsArray[] = $mmModel->getAttributeLabel('update_folder_content');
        }
        if ($mmModel->upload_folder_files == 1) {
            $allowedPermissionsArray[] = $mmModel->getAttributeLabel('upload_folder_files');
        }
        if ($mmModel->read_folder_files == 1) {
            $allowedPermissionsArray[] = $mmModel->getAttributeLabel('read_folder_files');
        }
        $allowedPermissions = '';
        if (!empty($allowedPermissionsArray)) {
            $permsStr = '';
            foreach ($allowedPermissionsArray as $permissionStr) {
                $permsStr .= Html::tag('li', $permissionStr);
            }
            $allowedPermissions = Html::tag('ul', $permsStr);
        }
        return $allowedPermissions;
    }
}
