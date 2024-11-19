<?php

use common\models\Lecture;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Teacher $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="teacher-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'lecture_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(Lecture::find()->all(), 'id', 'title'), // All data from the Lecture model
        'options' => ['placeholder' => 'Select Lecture', 'id' => 'seminar-id-dropdown'],
        'pluginOptions' => [
            'allowClear' => true,
            'width' => '100%',
        ],
    ]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
