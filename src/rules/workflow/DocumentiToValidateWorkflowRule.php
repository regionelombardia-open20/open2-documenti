<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\projectmanagement\rules\workflow
 * @category   CategoryName
 */

namespace  lispa\amos\documenti\rules\workflow;

use lispa\amos\core\rules\ToValidateWorkflowContentRule;

class DocumentiToValidateWorkflowRule extends ToValidateWorkflowContentRule
{

    public $name = 'documentiToValidateWorkflow';
    public $validateRuleName = 'DocumentValidate';

}