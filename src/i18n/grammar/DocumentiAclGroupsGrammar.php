<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\documenti\i18n\grammar
 * @category   CategoryName
 */

namespace open20\amos\documenti\i18n\grammar;

use open20\amos\core\interfaces\ModelGrammarInterface;
use open20\amos\documenti\AmosDocumenti;

/**
 * Class DocumentiAclGroupsGrammar
 * @package open20\amos\documenti\i18n\grammar
 */
class DocumentiAclGroupsGrammar implements ModelGrammarInterface
{
    /**
     * @return string
     */
    public function getModelSingularLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#documenti_acl_groups_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getModelLabel()
    {
        return AmosDocumenti::t('amosdocumenti', '#documenti_acl_groups_plural');
    }
    
    /**
     * @inheritdoc
     */
    public function getArticleSingular()
    {
        return AmosDocumenti::t('amosdocumenti', '#documenti_acl_article_singular');
    }
    
    /**
     * @inheritdoc
     */
    public function getArticlePlural()
    {
        return AmosDocumenti::t('amosdocumenti', '#documenti_acl_article_plural');
    }
    
    /**
     * @inheritdoc
     */
    public function getIndefiniteArticle()
    {
        return AmosDocumenti::t('amosdocumenti', '#documenti_acl_article_indefinite');
    }
}
