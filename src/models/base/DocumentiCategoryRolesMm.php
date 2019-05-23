<?php

namespace lispa\amos\documenti\models\base;

use Yii;

/**
* This is the base-model class for table "documenti_category_roles_mm".
*
    * @property integer $id
    * @property integer $documenti_categorie_id
    * @property string $role
    * @property string $created_at
    * @property string $updated_at
    * @property string $deleted_at
    * @property integer $created_by
    * @property integer $updated_by
    * @property integer $deleted_by
    *
            * @property \lispa\amos\documenti\models\DocumentiCategorie $documentiCategorie
    */
 class  DocumentiCategoryRolesMm extends \lispa\amos\core\record\Record
{


/**
* @inheritdoc
*/
public static function tableName()
{
return 'documenti_category_roles_mm';
}

/**
* @inheritdoc
*/
public function rules()
{
return [
            [['documenti_categorie_id', 'role'], 'required'],
            [['documenti_categorie_id', 'created_by', 'updated_by', 'deleted_by'], 'integer'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['role'], 'string', 'max' => 255],
            [['documenti_categorie_id'], 'exist', 'skipOnError' => true, 'targetClass' => \lispa\amos\documenti\models\DocumentiCategorie::className(), 'targetAttribute' => ['documenti_categorie_id' => 'id']],
];
}

/**
* @inheritdoc
*/
public function attributeLabels()
{
return [
    'id' => Yii::t('amosdocumenti', 'ID'),
    'documenti_categorie_id' => Yii::t('amosdocumenti', 'Documenti Category ID'),
    'role' => Yii::t('amosdocumenti', 'Community'),
    'created_at' => Yii::t('amosdocumenti', 'Created at'),
    'updated_at' => Yii::t('amosdocumenti', 'Updated at'),
    'deleted_at' => Yii::t('amosdocumenti', 'Deleted at'),
    'created_by' => Yii::t('amosdocumenti', 'Created by'),
    'updated_by' => Yii::t('amosdocumenti', 'Updated by'),
    'deleted_by' => Yii::t('amosdocumenti', 'Deleted by'),
];
}

    /**
    * @return \yii\db\ActiveQuery
    */
    public function getDocumentiCategorie()
    {
    return $this->hasOne(\lispa\amos\documenti\models\DocumentiCategorie::className(), ['id' => 'documenti_categorie_id']);
    }
}
