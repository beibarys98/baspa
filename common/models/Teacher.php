<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $organization
 * @property int|null $lecture_id
 *
 * @property Lecture $lecture
 */
class Teacher extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%teacher}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'organization', 'lecture_id'], 'required'],
            [['lecture_id'], 'integer'],
            [['name', 'organization'], 'string', 'max' => 255],
            [['lecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lecture::class, 'targetAttribute' => ['lecture_id' => 'id']],

            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'docx'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'organization' => Yii::t('app', 'Organization'),
            'lecture_id' => Yii::t('app', 'Lecture ID'),
        ];
    }

    /**
     * Gets query for [[Lecture]].
     *
     * @return \yii\db\ActiveQuery|LectureQuery
     */
    public function getLecture()
    {
        return $this->hasOne(Lecture::class, ['id' => 'lecture_id']);
    }

    /**
     * {@inheritdoc}
     * @return TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeacherQuery(get_called_class());
    }
}
