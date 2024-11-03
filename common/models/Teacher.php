<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "teacher".
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string $school
 */
class Teacher extends \yii\db\ActiveRecord
{
    public $password;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'teacher';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'school'], 'required'],
            [['user_id'], 'integer'],
            [['name', 'school'], 'string', 'max' => 255],

            ['name', 'match', 'pattern' => '/^[А-ЯЁӘІҢҒҮҰҚӨҺа-яёәіңғүұқөһ\s]+$/u', 'message' => Yii::t('app', 'Имя может содержать только кириллицу!')],
            ['name', 'match', 'pattern' => '/^[^\s]/', 'message' => Yii::t('app', 'Имя не может начинаться с пробела!')],
            ['name', 'match', 'pattern' => '/\s/', 'message' => Yii::t('app', 'Имя должно содержать минимум два слова!')],

            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Name'),
            'school' => Yii::t('app', 'School'),
        ];
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
