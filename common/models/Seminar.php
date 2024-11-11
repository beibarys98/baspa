<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%seminar}}".
 *
 * @property int $id
 * @property string $title
 * @property string $alghys
 * @property string $qurmet
 * @property string $sertifikat
 */
class Seminar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%seminar}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'alghys', 'qurmet', 'sertifikat'], 'required'],
            [['title', 'alghys', 'qurmet', 'sertifikat'], 'string', 'max' => 255],
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
            'sertifikat' => Yii::t('app', 'Sertifikat'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\SeminarQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\SeminarQuery(get_called_class());
    }
}
