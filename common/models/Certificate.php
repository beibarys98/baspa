<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%certificate}}".
 *
 * @property int $id
 * @property int $lecture_id
 * @property int $teacher_id
 * @property string $path
 *
 * @property Lecture $lecture
 * @property Teacher $teacher
 */
class Certificate extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%certificate}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lecture_id', 'teacher_id', 'path'], 'required'],
            [['lecture_id', 'teacher_id'], 'integer'],
            [['path'], 'string', 'max' => 255],
            [['lecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lecture::class, 'targetAttribute' => ['lecture_id' => 'id']],
            [['teacher_id'], 'exist', 'skipOnError' => true, 'targetClass' => Teacher::class, 'targetAttribute' => ['teacher_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lecture_id' => Yii::t('app', 'Lecture ID'),
            'teacher_id' => Yii::t('app', 'Teacher ID'),
            'path' => Yii::t('app', 'Path'),
        ];
    }

    /**
     * Gets query for [[Lecture]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\LectureQuery
     */
    public function getLecture()
    {
        return $this->hasOne(Lecture::class, ['id' => 'lecture_id']);
    }

    /**
     * Gets query for [[Teacher]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\TeacherQuery
     */
    public function getTeacher()
    {
        return $this->hasOne(Teacher::class, ['id' => 'teacher_id']);
    }

    /**
     * {@inheritdoc}
     * @return \common\models\query\CertificateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\CertificateQuery(get_called_class());
    }
}
