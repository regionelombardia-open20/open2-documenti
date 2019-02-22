<?php

namespace lispa\amos\documenti\i18n\grammar;

use lispa\amos\core\interfaces\ModelGrammarInterface;
use lispa\amos\documenti\AmosDocumenti;

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    piattaforma-openinnovation
 * @category   CategoryName
 */

class DocumentsGrammar implements ModelGrammarInterface
{

    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#document');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#documents');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosDocumenti::t('amosdocumenti', '#article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosDocumenti::t('amosdocumenti', '#article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosDocumenti::t('amosdocumenti', '#article_indefinite');
    }
}