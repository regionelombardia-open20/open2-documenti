<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\documenti\migrations
 * @category   CategoryName
 */

use lispa\amos\core\migration\AmosMigrationWorkflow;
use lispa\amos\documenti\models\Documenti;
use yii\helpers\ArrayHelper;

/**
 * Class m180531_090605_remove_documenti_validato_davalidare_transition
 */
class m180531_090605_remove_documenti_validato_davalidare_transition extends AmosMigrationWorkflow
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->setProcessInverted(true);
    }

    /**
     * @inheritdoc
     */
    protected function setWorkflow()
    {
        return ArrayHelper::merge(parent::setWorkflow(), [
            [
                'type' => AmosMigrationWorkflow::TYPE_WORKFLOW_TRANSITION,
                'workflow_id' => Documenti::DOCUMENTI_WORKFLOW,
                'start_status_id' => 'VALIDATO',
                'end_status_id' => 'DAVALIDARE'
            ],
        ]);
    }
}
