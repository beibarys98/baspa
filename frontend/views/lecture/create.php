<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Lecture $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = Yii::t('app', $model->type);
$this->params['breadcrumbs'][] = [
    'label' => Yii::t('app', $model->type), // Translated model->type
    'url' => ['index'] // Change 'index' to the appropriate URL if needed
];
$this->params['breadcrumbs'][] = Yii::t('app', 'Жаңа ' . $model->type); // Translated "Create"
?>

<div class="bb-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if (in_array($model->type, ['ББ', 'Әдістемелік', 'Семинар', 'Семинар_Ақылы'])):?>

        <?= $form->field($model, 'alghys')->fileInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'qurmet')->fileInput(['maxlength' => true])->label(in_array($model->type, ['Семинар', 'Семинар_Ақылы']) ? 'Диплом' : 'Құрмет Грамотасы') ?>

    <?php endif; ?>

    <?php if (in_array($model->type, ['Семинар', 'Семинар_Ақылы'])): ?>

        <?= $form->field($model, 'sertifikat')->fileInput(['maxlength' => true]) ?>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
