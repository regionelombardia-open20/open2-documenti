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

/**
 * Created by PhpStorm.
 * User: michele.lafrancesca
 * Date: 16/12/2019
 * Time: 11:25
 */

namespace open20\amos\documenti\behaviors;


use open20\amos\documenti\models\Documenti;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class GoogleDriveBehavior extends Behavior
{

    /**
     * @inheritdoc
     */
    public function events()
    {
        $events = [
            ActiveRecord::EVENT_AFTER_INSERT => 'saveDrive',
            ActiveRecord::EVENT_AFTER_UPDATE => 'saveDrive',
        ];

        return $events;
    }

    /**
     * @param $event
     * @throws \Google_Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function saveDrive($event){
        /** @var  $model Documenti*/
        $model = $this->owner;
        $GoogleDriveManager = new \open20\amos\documenti\utility\GoogleDriveManager(['model' => $model]);
//        pr(\Yii::$app->request->post());die;
        $fileId = \Yii::$app->request->post('fileid');
        $filename = \Yii::$app->request->post('filename');
        if(!empty($GoogleDriveManager)){
            if($model->drive_file_id != $fileId) {
                $GoogleDriveManager->getFileAndSave($fileId, $filename);
                $GoogleDriveManager->shareWithUser($fileId);
//                die;
            }
        }
    }

}