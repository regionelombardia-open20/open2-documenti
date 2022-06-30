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
 * Class DocumentiAclGroups
 *
 * This is the base-model class for table "documenti_acl_groups".
 *
 * @property integer $id
 * @property string $name
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\documenti\models\DocumentiAclGroupsUserMm[] $documentiAclGroupsMms
 * @property \open20\amos\core\user\User[] $groupUsers
 * @property \open20\amos\documenti\models\Documenti[] $groupFolders
 *
 * @package open20\amos\documenti\models\base
 */
abstract class DocumentiAclGroups extends Record
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
        return 'documenti_acl_groups';
    }
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [[
                'created_by',
                'updated_by',
                'deleted_by'
            ], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => AmosDocumenti::t('amosdocumenti', 'ID'),
            'name' => AmosDocumenti::t('amosdocumenti', '#group_name'),
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
    public function getDocumentiAclGroupsMms()
    {
        return $this->hasMany($this->documentsModule->model('DocumentiAclGroupsUserMm'), ['group_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupUsers()
    {
        return $this->hasMany(AmosAdmin::instance()->model('User'), ['id' => 'user_id'])->via('documentiAclGroupsMms');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupUserProfiles()
    {
        return $this->hasMany(AmosAdmin::instance()->model('UserProfile'), ['user_id' => 'user_id'])->via('documentiAclGroupsMms');
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroupFolders()
    {
        /** @var \open20\amos\documenti\models\Documenti $documentiModel */
        $documentiModel = $this->documentsModule->createModel('Documenti');
        return $this->hasMany($this->documentsModule->model('Documenti'), ['id' => 'document_id'])->via('documentiAclGroupsMms')
            ->andWhere([$documentiModel::tableName() . '.is_folder' => $documentiModel::IS_FOLDER]);
    }
}
