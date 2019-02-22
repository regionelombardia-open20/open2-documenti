<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\libs\common\MigrationCommon;
use lispa\amos\documenti\AmosDocumenti;
use lispa\amos\documenti\models\Documenti;
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
        $retval = \lispa\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            Documenti::className(),
            \lispa\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \lispa\amos\notificationmanager\models\NotificationChannels::MANAGE_UP);
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
        $retval = \lispa\amos\notificationmanager\AmosNotify::manageNewChannelNotifications(
            Documenti::className(),
            \lispa\amos\notificationmanager\models\NotificationChannels::CHANNEL_FAVOURITES,
            \lispa\amos\notificationmanager\models\NotificationChannels::MANAGE_DOWN);
        if (!$retval['success']) {
            foreach ($retval['errors'] as $error) {
                MigrationCommon::printConsoleMessage($error);
            }
        }
        return $retval['success'];
    }
}
