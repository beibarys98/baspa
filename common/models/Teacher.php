<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%teacher}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $organization
 * @property string|null $certificate
 * @property int|null $lecture_id
 *
 * @property File[] $files
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
            [['name'], 'required'],
            [['lecture_id'], 'integer'],
            [['name', 'organization', 'certificate'], 'string', 'max' => 255],
            [['lecture_id'], 'exist', 'skipOnError' => true, 'targetClass' => Lecture::class, 'targetAttribute' => ['lecture_id' => 'id']],

            ['file', 'file', 'skipOnEmpty' => false, 'extensions' => 'docx'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Есімі'),
            'organization' => Yii::t('app', 'Мекемесі'),
            'lecture_id' => Yii::t('app', 'Қатысады'),
            'file' => 'Файл'
        ];
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery|\common\models\query\FileQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::class, ['teacher_id' => 'id']);
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
     * {@inheritdoc}
     * @return \common\models\query\TeacherQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new \common\models\query\TeacherQuery(get_called_class());
    }
}
