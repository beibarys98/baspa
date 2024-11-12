<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Lecture $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="bb-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alghys')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'qurmet')->fileInput(['maxlength' => true])->label($model->type == 'seminar' ? 'Diplom' : 'Qurmet') ?>

    <?php if($model->type == 'seminar'): ?>
        <?= $form->field($model, 'sertifikat')->fileInput(['maxlength' => true]) ?>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
