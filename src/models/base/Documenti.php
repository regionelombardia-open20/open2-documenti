<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\models\base
 * @category   CategoryName
 */

namespace lispa\amos\documenti\models\base;

use lispa\amos\core\record\ContentModel;
use lispa\amos\documenti\AmosDocumenti;
use yii\helpers\ArrayHelper;

/**
 * Class Documenti
 *
 * This is the base-model class for table "documenti".
 *
 * @property integer $id
 * @property string $titolo
 * @property string $sottotitolo
 * @property string $descrizione_breve
 * @property string $descrizione
 * @property string $metakey
 * @property string $metadesc
 * @property integer $primo_piano
 * @property integer $filemanager_mediafile_id
 * @property integer $hits
 * @property integer $abilita_pubblicazione
 * @property integer $in_evidenza
 * @property string $data_pubblicazione
 * @property string $data_rimozione
 * @property integer $documenti_categorie_id
 * @property string $status
 * @property integer $comments_enabled
 * @property integer $parent_id
 * @property integer $is_folder
 * @property integer $version
 * @property integer $version_parent_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \lispa\amos\documenti\models\DocumentiCategorie $documentiCategorie
 * @property \lispa\amos\documenti\models\Documenti $parent
 * @property \lispa\amos\documenti\models\Documenti[] $children
 * @property \lispa\amos\documenti\models\Documenti $versionParent
 *
 * @package lispa\amos\documenti\models\base
 */
abstract class Documenti extends ContentModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'documenti';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        $required = [
            'titolo',
            'status',
        ];
        if ( !empty( AmosDocumenti::instance() ) && AmosDocumenti::instance()->enableCategories){
            $required[] =  'documenti_categorie_id';
        }
        return [
            [[
                'descrizione',
                'metakey',
                'metadesc'
            ], 'string'],
            [[
                'titolo',
                'sottotitolo',
            ], 'string', 'max' => 100],
            [[
                'descrizione_breve'
            ], 'string', 'max' => 250],
            [[
                'primo_piano',
                'filemanager_mediafile_id',
                'hits',
                'abilita_pubblicazione',
                'in_evidenza',
                'documenti_categorie_id',
                'created_by',
                'updated_by',
                'deleted_by',
                'comments_enabled',
                'parent_id',
                'is_folder',
                'version',
                'version_parent_id'
            ], 'integer'],
            [[
                'data_pubblicazione',
                'data_rimozione',
                'created_at',
                'updated_at',
                'deleted_at',
                'status',
                'comments_enabled',
            ], 'safe'],
            [ $required, 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(), [
            'id' => AmosDocumenti::t('amosdocumenti', 'Id'),
            'titolo' => AmosDocumenti::t('amosdocumenti', '#documents_title_field'),
            'sottotitolo' => AmosDocumenti::t('amosdocumenti', '#documents_subtitle_field'),
            'descrizione_breve' => AmosDocumenti::t('amosdocumenti', '#documents_abstract_field'),
            'descrizione' => AmosDocumenti::t('amosdocumenti', '#documents_description_field'),
            'metakey' => AmosDocumenti::t('amosdocumenti', 'Meta key'),
            'metadesc' => AmosDocumenti::t('amosdocumenti', 'Meta descrizione'),
            'primo_piano' => AmosDocumenti::t('amosdocumenti', 'Pubblica sul sito'),
            'filemanager_mediafile_id' => AmosDocumenti::t('amosdocumenti', 'Documento pricipale'),
            'in_evidenza' => AmosDocumenti::t('amosdocumenti', 'In evidenza'),
            'hits' => AmosDocumenti::t('amosdocumenti', 'Visualizzazioni'),
            'abilita_pubblicazione' => AmosDocumenti::t('amosdocumenti', 'Abilita pubblicazione'),
            'data_pubblicazione' => AmosDocumenti::t('amosdocumenti', '#start_publication_date'),
            'data_rimozione' => AmosDocumenti::t('amosdocumenti', '#end_publication_date'),
            'documenti_categorie_id' => AmosDocumenti::t('amosdocumenti', 'Categoria'),
            'status' => AmosDocumenti::t('amosdocumenti', 'Stato'),
            'comments_enabled' => AmosDocumenti::t('amosdocumenti', '#comments_enabled'),
            'parent_id' => AmosDocumenti::t('amosdocumenti', 'Parent ID'),
            'is_folder' => AmosDocumenti::t('amosdocumenti', 'Is Folder'),
            'version' => AmosDocumenti::t('amosdocumenti', 'Version'),
            'version_parent_id' => AmosDocumenti::t('amosdocumenti', 'Version Parent ID'),
            'created_at' => AmosDocumenti::t('amosdocumenti', 'Creato il'),
            'updated_at' => AmosDocumenti::t('amosdocumenti', 'Aggiornato il'),
            'deleted_at' => AmosDocumenti::t('amosdocumenti', 'Cancellato il'),
            'created_by' => AmosDocumenti::t('amosdocumenti', 'Creato da'),
            'updated_by' => AmosDocumenti::t('amosdocumenti', 'Aggiornato da'),
            'deleted_by' => AmosDocumenti::t('amosdocumenti', 'Cancellato da'),
        ]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentiCategorie()
    {
        return $this->hasOne(\lispa\amos\documenti\models\DocumentiCategorie::className(), ['id' => 'documenti_categorie_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(\lispa\amos\documenti\models\Documenti::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany(\lispa\amos\documenti\models\Documenti::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersionParent()
    {
        return $this->hasOne(\lispa\amos\documenti\models\Documenti::className(), ['id' => 'version_parent_id']);
    }
}
