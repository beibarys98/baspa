<?php

namespace common\models\search;

use common\models\Lecture;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * LectureSearch represents the model behind the search form of `common\models\Lecture`.
 */
class LectureSearch extends Lecture
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'type', 'alghys', 'qurmet', 'sertifikat'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Lecture::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!empty($this->type)) {
            $query->andWhere(['type' => $this->type]);
        }

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'alghys', $this->alghys])
            ->andFilterWhere(['like', 'qurmet', $this->qurmet])
            ->andFilterWhere(['like', 'sertifikat', $this->sertifikat]);

        return $dataProvider;
    }
}
