<?php

namespace open20\amos\documenti\models\base;

use Yii;

/**
 * This is the base-model class for table "documenti_ms_teams".
 *
 * @property integer $id
 * @property integer $documenti_id
 * @property string $msgraph_drive_item_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\documenti\models\Documenti $documenti
 */
class  DocumentiMsTeams extends \open20\amos\core\record\Record
{
    public $isSearch = false;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documenti_ms_teams';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['documenti_id'], 'required'],
            [['documenti_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['msgraph_drive_item_id'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['msgraph_drive_item_id'], 'unique'],
            [['documenti_id'], 'exist', 'skipOnError' => true, 'targetClass' => \open20\amos\documenti\models\Documenti::className(), 'targetAttribute' => ['documenti_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'documenti_id' => Yii::t('app', 'Comment id'),
            'msgraph_drive_item_id' => Yii::t('app', 'Commen channel teams id'),
            'created_at' => Yii::t('app', 'Created at'),
            'updated_at' => Yii::t('app', 'Updated at'),
            'deleted_at' => Yii::t('app', 'Deleted at'),
            'created_by' => Yii::t('app', 'Created by'),
            'updated_by' => Yii::t('app', 'Updated by'),
            'deleted_by' => Yii::t('app', 'Deleted by'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumenti()
    {
        return $this->hasOne(\open20\amos\documenti\models\Documenti::className(), ['id' => 'documenti_id']);
    }
}
