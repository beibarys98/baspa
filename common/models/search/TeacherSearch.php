<?php

namespace common\models\search;

use common\models\Teacher;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * TeacherSearch represents the model behind the search form of `common\models\Teacher`.
 */
class TeacherSearch extends Teacher
{
    public $lecture_title;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'lecture_id'], 'integer'],
            [['name', 'organization', 'lecture_title'], 'safe'],
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
        $query = Teacher::find();

        $query->joinWith('lecture');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id',
                    'name',
                    'organization',
                    'lecture_title' => [
                        'asc' => ['lecture.title' => SORT_ASC],
                        'desc' => ['lecture.title' => SORT_DESC]
                    ],
                ],
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if (isset($params['lectureId'])) {
            $query->andWhere(['lecture.id' => $params['lectureId']]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'teacher.id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'teacher.name', $this->name])
            ->andFilterWhere(['like', 'teacher.organization', $this->organization])
            ->andFilterWhere(['like', 'lecture.title', $this->lecture_title]);

        return $dataProvider;
    }
}
