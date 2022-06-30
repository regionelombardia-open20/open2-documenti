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

use open20\amos\core\record\ContentModel;
use open20\amos\documenti\AmosDocumenti;
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
 * @property string $link_document
 * @property integer $drive_file_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $deleted_at
 * @property integer $created_by
 * @property integer $updated_by
 * @property integer $deleted_by
 *
 * @property \open20\amos\documenti\models\DocumentiCategorie $documentiCategorie
 * @property \open20\amos\documenti\models\Documenti $parent
 * @property \open20\amos\documenti\models\Documenti[] $children
 * @property \open20\amos\documenti\models\Documenti $versionParent
 *
 * @package open20\amos\documenti\models\base
 */
abstract class Documenti extends ContentModel
{
    /**
     * @var AmosDocumenti $documentsModule
     */
    protected $documentsModule = null;

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
    public function init()
    {
        parent::init();
        $this->documentsModule = \Yii::$app->getModule(AmosDocumenti::getModuleName());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $defaultRequired = [
            'titolo',
            'status',
        ];
        $required = ArrayHelper::merge($defaultRequired, $this->documentsModule->documentExtraRequiredFields);

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
            [['link_document'], 'string', 'max' => 255],
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
                'drive_file_id',
                'drive_file_modified_at'
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
            'link_document' => AmosDocumenti::t('amosdocumenti', '#link_document_field'),
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
        return $this->hasOne($this->documentsModule->model('DocumentiCategorie'), ['id' => 'documenti_categorie_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne($this->documentsModule->model('Documenti'), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChildren()
    {
        return $this->hasMany($this->documentsModule->model('Documenti'), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVersionParent()
    {
        return $this->hasOne($this->documentsModule->model('Documenti'), ['id' => 'version_parent_id']);
    }
}
