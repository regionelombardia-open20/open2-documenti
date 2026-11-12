<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-documenti
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