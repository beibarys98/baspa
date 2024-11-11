<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%lecture}}".
 *
 * @property int $id
 * @property string $title
 * @property string $type
 * @property string|null $alghys
 * @property string|null $qurmet
 * @property string|null $sertifikat
 */
class Lecture extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lecture}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'type'], 'required'],
            [['title', 'type', 'alghys', 'qurmet', 'sertifikat'], 'string', 'max' => 255],
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
            'type' => Yii::t('app', 'Type'),
            'alghys' => Yii::t('app', 'Alghys'),
            'qurmet' => Yii::t('app', 'Qurmet'),
            'sertifikat' => Yii::t('app', 'Sertifikat'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return LectureQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LectureQuery(get_called_class());
    }
}
