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

    <?php if (!in_array($model->type, ['adistemelik_qural', 'digital_orta', 'mksh'])):?>

        <?= $form->field($model, 'alghys')->fileInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'qurmet')->fileInput(['maxlength' => true])->label(in_array($model->type, ['seminar', 'seminar_plat', 'mksh']) ? 'Diplom' : 'Qurmet') ?>

    <?php endif; ?>

    <?php if (in_array($model->type, ['seminar', 'seminar_plat'])): ?>

        <?= $form->field($model, 'sertifikat')->fileInput(['maxlength' => true]) ?>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
