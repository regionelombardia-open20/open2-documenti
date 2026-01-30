<?php

namespace open20\amos\documenti\models;

use open20\amos\core\models\AttributesChangeLog;
use open20\amos\core\models\ModelsClassname;
use open20\amos\core\models\UserActivityLog;

class UserActivityDocLog extends UserActivityLog
{
    /**
     * @param $name
     * @param $model
     * @param $type
     * @param $description
     * @param $user_id
     * @return UserActivityLog
     */
    public static function registerLog($name,  $model = null, $type=null, $description = null, $user_id = null){
        $module = \Yii::$app->getModule('documenti');
        if($module && $module->enableLogDocumentActivity) {
            return UserActivityLog::registerLog($name, $model, $type, $description, $user_id);
        }
        return null;
    }



    public function getChangesLog()
    {
        return $this->hasMany(AttributesChangeLog::className(), ['user_activity_log_id' => 'id']);
    }


}