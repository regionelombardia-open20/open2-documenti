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

namespace open20\amos\documenti\events;


use open20\amos\core\models\AttributesChangeLog;
use open20\amos\documenti\AmosDocumenti;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\DocumentiCategorie;
use open20\amos\documenti\models\UserActivityDocLog;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\rest\Controller;

class AfterInsertDocuments
{

    public $attributesToLog = [];


    /**
     * @param $event
     */
    public function logInsertDocs($event)
    {
//        if (!(\Yii::$app->controller instanceof Controller)) {
        /** @var  $document Documenti */
        $document = $event->sender;
//            pr($document->attributes);
        if (!empty($document->version_parent_id)) {
            $labelLog = AmosDocumenti::t('amosdocumenti', 'Nuova versione documento');
            if ($document->is_folder) {
                $labelLog = AmosDocumenti::t('amosdocumenti', 'Nuova versione cartella');
            }
            $versionParent = $document->versionParent;
            UserActivityDocLog::registerLog($labelLog, $versionParent, Documenti::LOG_TYPE_NEW_DOC_VERS, (integer)$document->version + 1, $document->created_by);
        } else {
            $labelLog = AmosDocumenti::t('amosdocumenti', 'Nuovo documento');
            if ($document->is_folder) {
                $labelLog = AmosDocumenti::t('amosdocumenti', 'Nuova cartella');
            }
            UserActivityDocLog::registerLog($labelLog, $document, Documenti::LOG_TYPE_CREATE_DOC, 1, $document->created_by);
        }

//        }
    }

    public function filterWhiteListAttribute($attributes)
    {
        $filteredAttributes = [];
        $whiteList = ['titolo', 'descrizione', 'sottotitolo', 'descrizione_breve', 'link_document','documenti_categorie_id'];
//        $whiteList = ['begin_date_hour', 'end_date_hour', 'status', 'event_location_id', 'event_location_entrance_id'];
        foreach ($attributes as $attribute => $value) {
            if (in_array($attribute, $whiteList)) {
                $filteredAttributes[$attribute] = $value;
            }
        }
        return $filteredAttributes;
    }

    public function saveChangedValues($event)
    {
        $owner = $event->sender;
        $j = 0;

//        pr($owner->title,'evento');
//        pr($owner->getAttributes(),'evento');
        if (!empty($owner) && !$owner->doNotLog) {
            \Yii::$app->db->beginTransaction();

            $parentVersion = $owner;
            if($owner->version_parent_id) {
                $parentVersion = $owner->versionParent;
            }
            $log = UserActivityDocLog::registerLog(AmosDocumenti::t('amosdocumenti', 'Documento modificato'), $parentVersion, Documenti::LOG_TYPE_EDIT_DOC, $owner->version, \Yii::$app->user->id);

            $oldAttributes = $owner->getOldAttributes();
            $this->attributesToLog = $owner->getAttributes();

            // salvo modifica allegato
            if (!empty($_FILES['Documenti']['name']['documentMainFile'])) {
                $fieldsLog = new AttributesChangeLog();
                $fieldsLog->model_classname = $owner->className();
                $fieldsLog->model_id = $owner->getPrimaryKey();
                $fieldsLog->model_attribute = 'documentMainFile';
                $fieldsLog->user_activity_log_id = $log->id;
                if (!empty(\Yii::$app->request->post('oldMainFileName'))) {
                    $fieldsLog->old_value = \Yii::$app->request->post('oldMainFileName');
                } else {
                    $fieldsLog->old_value = '-';
                }
                $fieldsLog->new_value = $_FILES['Documenti']['name']['documentMainFile'];
                $fieldsLog->save(false);
            }

            $this->attributesToLog = $this->filterWhiteListAttribute($this->attributesToLog);
            $hash = hash('md5', date('Y-m-d H:i:s'));
            $changedValue = [];
            if ($this->isChanged($event, $oldAttributes)) {
                foreach ($this->attributesToLog as $attributeName => $value) {
                    $oldValue = (($event->name == ActiveRecord::EVENT_AFTER_INSERT) ? null : $oldAttributes[$attributeName]);
                    $newValue = $this->attributesToLog[$attributeName];
                    if ($oldValue != $newValue) {
                        $oldValue = $this->filterLog($oldValue, $newValue, 'old', $attributeName);
                        $newValue = $this->filterLog($oldValue, $newValue, 'new', $attributeName);

                        $changedValue[$attributeName]['oldValue'] = $oldValue;
                        $changedValue[$attributeName]['newValue'] = $newValue;

                        $fieldsLog = new AttributesChangeLog();

                        $fieldsLog->model_classname = $owner->className();
                        $fieldsLog->model_id = $owner->getPrimaryKey();
                        $fieldsLog->model_attribute = $attributeName;
                        $fieldsLog->user_activity_log_id = $log->id;

                        $fieldsLog->old_value = $oldValue;
                        $fieldsLog->new_value = $newValue;
                        if($fieldsLog->save(false)){
                            $j++;
                        }
                    }
                }


            }

            if ($j > 0) {
                \Yii::$app->db->transaction->commit();
            } else {
                \Yii::$app->db->transaction->rollBack();
            }

            if ($owner->status != $oldAttributes['status']) {
                if ($owner->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_BOZZA && $oldAttributes['status'] == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE) {
                    $logStatus = UserActivityDocLog::registerLog(AmosDocumenti::t('amosdocumenti', 'Richiesta integrazione'), $parentVersion, Documenti::LOG_TYPE_WORFLOW_INTEGRATION, $parentVersion->version, $owner->created_by);
                } else if ($owner->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_VALIDATO) {
                    $logStatus = UserActivityDocLog::registerLog(AmosDocumenti::t('amosdocumenti', 'Pubblicazione documento'), $parentVersion, Documenti::LOG_TYPE_WORFLOW_PUBLISH, $parentVersion->version, $owner->created_by);
                } else if ($owner->status == Documenti::DOCUMENTI_WORKFLOW_STATUS_DAVALIDARE) {
                    $logStatus = UserActivityDocLog::registerLog(AmosDocumenti::t('amosdocumenti', 'Richiesta pubblicazione'), $parentVersion, Documenti::LOG_TYPE_WORFLOW_REQUEST_VALIDATION, $parentVersion->version, $owner->created_by);
                }
            }
        }
//        pr($j,'count');

        return true;

    }

    public
    function isChanged($event, $oldAttributes)
    {
        foreach ($this->attributesToLog as $attributeName => $value) {
            $oldValue = (($event->name == ActiveRecord::EVENT_AFTER_INSERT) ? null : $oldAttributes[$attributeName]);
            $newValue = $this->attributesToLog[$attributeName];
            if ($oldValue != $newValue) {
                return true;
            }

        }
        return false;
    }

    public
    function filterLog($old, $new, $type = 'old', $attributeName = '')
    {
        if($attributeName == 'documenti_categorie_id') {
            if($type == 'old'){
               $cat =  DocumentiCategorie::findOne($old);
               if($cat){
                   return $cat->titolo;
               }
            }else{
                $cat =  DocumentiCategorie::findOne($new);
                if($cat){
                    return $cat->titolo;
                }
            }
        }

        if ($type == 'old') {
            return $old;
        }
        return $new;

    }

    public function logDeleteAction($event){

        $document = $event->sender->owner;
        $parentVersion = $document;
        if($document->version_parent_id) {
            $parentVersion = $document->versionParent;
        }
        $logStatus = UserActivityDocLog::registerLog(AmosDocumenti::t('amosdocumenti', 'Eliminazione documento'), $parentVersion, Documenti::LOG_TYPE_DELETE_DOC, $parentVersion->version, \Yii::$app->user->id);

    }





//    public function deleteDocsToEvent($event)
//    {
//        $document = $event->sender;
//        if ($document) {
//            if (!empty($document->deleted_at)) {
//                $landing = \open20\amos\events\models\EventLanding::find()
//                    ->innerJoin('event_landing_documents', 'event_landing_documents.event_landing_id = event_landing.event_id')
//                    ->andWhere(['documenti_id' => $document->id])->one();
//                if ($landing) {
//                    EventsUtility::updateLuyaPageTimestampForCache($landing);
//                }
//            }
//        }
//    }


}