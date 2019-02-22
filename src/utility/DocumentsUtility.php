<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\utility
 * @category   CategoryName
 */

namespace lispa\amos\documenti\utility;

use lispa\amos\core\icons\AmosIcons;
use lispa\amos\core\user\User;
use lispa\amos\core\utilities\Email;
use lispa\amos\core\views\grid\ActionColumn;
use lispa\amos\documenti\models\Documenti;
use lispa\amos\documenti\models\DocumentiCategorie;
use yii\base\Object;
use yii\db\Query;

/**
 * Class DocumentsUtility
 * @package lispa\amos\documenti\utility
 */
class DocumentsUtility extends Object
{
    /**
     * @param Documenti $model
     * @param bool $onlyIconName If true return only the icon name ready to use in AmosIcon::show method.
     * @return string
     */
    public static function getDocumentIcon($model, $onlyIconName = false)
    {
        if ($model->is_folder) {
            $folderIconName = 'folder-open';
            if ($onlyIconName) {
                return $folderIconName;
            }
            return AmosIcons::show($folderIconName, ['class' => 'icon_widget_graph'], 'dash');
        }
        $iconName = 'file-o';
        $documentFile = $model->getDocumentMainFile();
        if (!is_null($documentFile)) {
            $docExtension = strtolower($documentFile->type);
            switch ($docExtension) {
                case 'doc':
                    //$iconName = 'file-word-o';
                    $iconName = 'file-doc-o';
                    break;
                case 'docx':
//                    $iconName = 'file-word-o';
                    $iconName = 'file-docx-o';
                    break;
                case 'rtf':
//                    $iconName = 'file-o';
                    $iconName = 'file-rtf-o';
                    break;
                case 'xls':
//                    $iconName = 'file-excel-o';
                    $iconName = 'file-xls-o';
                    break;
                case 'xlsx':
//                    $iconName = 'file-excel-o';
                    $iconName = 'file-xlsx-o';
                    break;
                case 'txt':
//                    $iconName = 'file-text-o';
                    $iconName = 'file-txt-o';
                    break;
                case 'pdf':
//                    $iconName = 'file-pdf-o';
                    $iconName = 'file-pdf-o2';
                    break;
                default:
                    $iconName = 'file-o';
                    break;
            }
        }
        if ($onlyIconName) {
            return $iconName;
        }
        return AmosIcons::show($iconName, ['class' => 'icon_widget_graph'], 'dash');
    }

    /**
     * @param $idUserIdList
     * @param $subject
     * @param $text
     * @param array $files
     * @param bool $queue
     */
    public static function sendEmail($idUserIdList, $subject, $text, $files = [], $queue = false)
    {
        $emailList = [];
        foreach ($idUserIdList as $id) {
            $user = User::findOne($id);
            if ($user) {
                $emailList [] = $user->email;
            }
        }

        $emaiFrom = \Yii::$app->params['supportEmail'];

        /** @var  $email Email */
        try {
            $emailModule = \Yii::$app->getModule('email');
            $email = new Email();
//                if(!empty($groupsModule->layoutEmail)) {
//                    $emailModule->defaultLayout = $groupsModule->layoutEmail;
//                }
            $sent = $email->sendMail(
                $emaiFrom,
                $emailList,
                $subject,
                $text,
                $files,
                [],
                [],
                0,
                $queue
            );
            return $sent;
        } catch (\Exception $e) {
            return false;
//                pr($e->getTrace());
        }
    }

    /**
     * This method returns an array with document categories ids used in the documents present in the platform.
     * @return int[]
     */
    public static function getDocumentsCategoriesIds()
    {
        $query = new Query();
        $query->select(['documenti_categorie_id'])->distinct();
        $query->from(Documenti::tableName());
        $query->where(['deleted_at' => null]);
        $documentsCategoriesIds = $query->column();
        return $documentsCategoriesIds;
    }

    /**
     * This method returns an array with document categories ready for select used in the documents present in the platform.
     * @return array
     */
    public static function getSearchCategoriesReadyForSelect()
    {
        $documentsCategoriesIds = self::getDocumentsCategoriesIds();
        $documentsCategoriesForSelect = [];
        foreach ($documentsCategoriesIds as $documentCategoryId) {
            $documentCategory = DocumentiCategorie::findOne($documentCategoryId);
            if (!is_null($documentCategory)) {
                $documentsCategoriesForSelect[$documentCategory->id] = $documentCategory->titolo;
            }
        }
        return $documentsCategoriesForSelect;
    }

    /**
     * Return the options for the specified action column button.
     * @param string $type
     * @return array
     */
    public static function getGridActionColumnsButtonsOptions($type)
    {
        $options = [];
        $actionColumnsObject = new ActionColumn();

        switch ($type) {
            case 'view':
                $options = $actionColumnsObject->viewOptions;
                break;
            case 'update':
                $options = $actionColumnsObject->updateOptions;
                break;
            case 'delete':
                $options = $actionColumnsObject->deleteOptions;
                break;
        }

        return $options;
    }
}
