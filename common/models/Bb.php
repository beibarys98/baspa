<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%bb}}".
 *
 * @property int $id
 * @property string $title
 * @property string $alghys
 * @property string $qurmet
 */
class Bb extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%bb}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'alghys', 'qurmet'], 'required'],
            [['title', 'alghys', 'qurmet'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'alghys' => Yii::t('app', 'Alghys'),
            'qurmet' => Yii::t('app', 'Qurmet'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\BbQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\BbQuery(get_called_class());
    }
}
