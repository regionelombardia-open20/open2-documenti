<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-documenti
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

namespace open20\amos\documenti\models\search;

use open20\amos\core\models\ModelsClassname;
use open20\amos\documenti\models\Documenti;
use open20\amos\documenti\models\UserActivityDocLog;
use yii\data\ActiveDataProvider;

class UserActivityDocLogSearch extends UserActivityDocLog
{
    public $id;
    public $activityType;
    public $dateFrom;
    public $dateTo;
    public $documentTitle;

    public function rules()
    {
        return [
            [['id', 'name', 'activityType', 'documentTitle', 'dateTo', 'dateFrom'], 'safe']
        ];
    }

    /**
     * @return null
     */
    public function getCurrentCommunityId(){
        $moduleCwh = \Yii::$app->getModule('cwh');
        if (isset($moduleCwh) && !empty($moduleCwh->getCwhScope())) {
            $scope = $moduleCwh->getCwhScope();
            if (isset($scope['community'])) {
                return $scope['community'];
            }
        }
        return null;
    }

    public function search($params)
    {
        $modelClassname = ModelsClassname::find()->andWhere(['classname' => Documenti::className()])->one();
        if ($modelClassname) {
            $communityId = $this->getCurrentCommunityId();
            $query = UserActivityDocLog::find()
                ->andWhere(['models_classname_id' => $modelClassname->id]);
            if($communityId){
                $query->andWhere(['community_id' => $communityId]);
            }else{
                $query->andWhere(['community_id' => null]);
            }
            $this->load($params);

            if (!empty($this->id)) {
                // messo where in modo da recuperare anche i documenti cancellati
                $docIds = Documenti::find()->select('id')->where(['OR',['version_parent_id' => $this->id],['id' => $this->id]]);
                $query->andWhere(['record_id' => $docIds]);
            }

            $query->andFilterWhere(['type' => $this->activityType]);
            $query->andFilterWhere(['>=', 'created_at', $this->dateFrom]);
            $query->andFilterWhere(['<=', 'created_at', $this->dateTo]);
            if ($this->documentTitle) {
                // messo where in modo da recuperare anche i documenti cancellati
                $docsIdsTitle = Documenti::find()
                    ->select('id')
                    ->where(['LIKE', 'titolo', $this->documentTitle]);
                $query->andFilterWhere(['record_id' => $docsIdsTitle]);
            }

            $dataProvider = new ActiveDataProvider([
                'query' => $query,
            ]);
            $dataProvider->setSort([
                'attributes'=>['user_activity_log.created_at'],
                'defaultOrder' => ['user_activity_log.created_at' => SORT_DESC]
            ]);
            return $dataProvider;
        }
        $query = UserActivityDocLog::find()->andWhere(0);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $dataProvider;
    }

}