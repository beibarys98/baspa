<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%adistemelik}}".
 *
 * @property int $id
 * @property string $title
 * @property string $alghys
 * @property string $qurmet
 */
class Adistemelik extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%adistemelik}}';
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
     * @return \common\models\query\AdistemelikQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\AdistemelikQuery(get_called_class());
    }
}
