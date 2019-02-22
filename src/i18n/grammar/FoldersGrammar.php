<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\i18n\grammar
 * @category   CategoryName
 */

namespace lispa\amos\documenti\i18n\grammar;

use lispa\amos\core\interfaces\ModelGrammarInterface;
use lispa\amos\documenti\AmosDocumenti;

/**
 * Class FoldersGrammar
 * @package lispa\amos\documenti\i18n\grammar
 */
class FoldersGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder');
    }

    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#folders');
    }

    /**
     * @return mixed
     */
    public function getArticleSingular()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder_article_singular');
    }

    /**
     * @return mixed
     */
    public function getArticlePlural()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder_article_plural');
    }

    /**
     * @return string
     */
    public function getIndefiniteArticle()
    {
        return AmosDocumenti::t('amosdocumenti', '#folder_article_indefinite');
    }
}
