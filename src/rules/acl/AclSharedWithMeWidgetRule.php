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

use open20\amos\documenti\widgets\icons\WidgetIconDocumentiAclSharedWithMeAdmin;
use yii\rbac\Rule;

/**
 * Class AclSharedWithMeWidgetRule
 * @package open20\amos\documenti\rules\acl
 */
class AclSharedWithMeWidgetRule extends Rule
{
    /**
     * @inheritdoc
     */
    public $name = 'aclSharedWithMeWidget';
    
    /**
     * @inheritdoc
     */
    public function execute($user, $item, $params)
    {
        return (!\Yii::$app->user->can(WidgetIconDocumentiAclSharedWithMeAdmin::className()));
    }
}
