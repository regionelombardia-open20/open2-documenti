<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\models\base
 * @category   CategoryName
 */

namespace open20\amos\documenti\models\base;

use open20\amos\admin\AmosAdmin;
use open20\amos\core\record\Record;
use open20\amos\documenti\AmosDocumenti;

/**
 * Class DocumentiAclGroupsUserMm
 *
 * This is the base-model class for table "documenti_acl_groups_user_mm".
 *
 * @property integer $id
 * @property integer $group_id
 * @property integer $user_id
 * @property integer $document_id
 * @property integer $update_folder_content
 * @property integer $upload_folder_files
 * @property integer $read_folder_files
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\documenti\models\DocumentiAclGroups $group
 * @property \open20\amos\core\user\User $user
 * @property \open20\amos\admin\models\UserProfile $userProfile
 * @property \open20\amos\documenti\models\DocumentiAcl $folder
 *
 * @package open20\amos\documenti\models\base
 */
abstract class DocumentiAclGroupsUserMm extends Record
{
    /**
     * @var AmosDocumenti $documentsModule
     */
    protected $documentsModule;
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->documentsModule = AmosDocumenti::instance();
        parent::init();
    }
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documenti_acl_groups_user_mm';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [[
                'group_id',
                'user_id',
                'document_id',
                'update_folder_content',
                'upload_folder_files',
                'read_folder_files',
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDocumenti::t('amosdocumenti', 'ID'),
            'group_id' => AmosDocumenti::t('amosdocumenti', '#group_name'),
            'user_id' => AmosDocumenti::t('amosdocumenti', 'User ID'),
            'document_id' => AmosDocumenti::t('amosdocumenti', 'Document ID'),
            'update_folder_content' => AmosDocumenti::t('amosdocumenti', '#update_folder_content'),
            'upload_folder_files' => AmosDocumenti::t('amosdocumenti', '#upload_folder_files'),
            'read_folder_files' => AmosDocumenti::t('amosdocumenti', '#read_folder_files'),
            'created_at' => AmosDocumenti::t('amosdocumenti', 'Created At'),
            'updated_at' => AmosDocumenti::t('amosdocumenti', 'Updated At'),
            'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Deleted At'),
            'created_by' => AmosDocumenti::t('amosdocumenti', 'Created By'),
            'updated_by' => AmosDocumenti::t('amosdocumenti', 'Updated By'),
            'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Deleted By'),
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne($this->documentsModule->model('DocumentiAclGroups'), ['id' => 'group_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(AmosAdmin::instance()->model('User'), ['id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUserProfile()
    {
        return $this->hasOne(AmosAdmin::instance()->model('UserProfile'), ['user_id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFolder()
    {
        return $this->hasOne($this->documentsModule->model('DocumentiAcl'), ['id' => 'document_id'])->andWhere(['is_folder' => \open20\amos\documenti\models\Documenti::IS_FOLDER]);
    }
}
