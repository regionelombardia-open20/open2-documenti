<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos\documenti\migrations
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

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use yii\db\Migration;

/**
 * Class m170619_152325_add_documenti_favourite_channel_notifications
 */
class m170619_152325_add_documenti_favourite_channel_notifications extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $notifyModule = Yii::$app->getModule('notify');
        if (is_null($notifyModule)) {
            MigrationCommon::printConsoleMessage(AmosDocumenti::t('amosdocumenti', 'Notify module not installed. Nothing to do.'));
            return true;
        }
        $retval = \open20\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            AmosDocumenti::instance()->model('Documenti'),
            \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \open20\amos\notificationmanager\models\NotificationChannels::MANAGE_UP);
        if (!$retval['success']) {
            foreach ($retval['errors'] as $error) {
                MigrationCommon::printConsoleMessage($error);
            }
        }
        return $retval['success'];
    }
    
    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $notifyModule = Yii::$app->getModule('notify');
        if (is_null($notifyModule)) {
            MigrationCommon::printConsoleMessage(AmosDocumenti::t('amosdocumenti', 'Notify module not installed. Nothing to do.'));
            return true;
        }
        $retval = \open20\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            AmosDocumenti::instance()->model('Documenti'),
            \open20\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \open20\amos\notificationmanager\models\NotificationChannels::MANAGE_DOWN);
        if (!$retval['success']) {
            foreach ($retval['errors'] as $error) {
                MigrationCommon::printConsoleMessage($error);
            }
        }
        return $retval['success'];
    }
}
