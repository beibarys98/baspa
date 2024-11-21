<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Lecture $model */
/** @var yii\widgets\ActiveForm $form */

$this->title = 'Жаңа';

$this->params['breadcrumbs'][] = ['label' => $model->type, 'url' => ['/lecture/index', 'type' => $model->type]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="bb-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?php if (in_array($model->type, ['Білім Басқармасы', 'Әдістемелік Орталық', 'Семинар', 'Семинар Ақылы'])):?>

        <?= $form->field($model, 'alghys')->fileInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'qurmet')->fileInput(['maxlength' => true])->label(in_array($model->type, ['Семинар', 'Семинар Ақылы']) ? 'Диплом' : 'Құрмет Грамотасы') ?>

    <?php endif; ?>

    <?php if (in_array($model->type, ['Семинар', 'Семинар Ақылы'])): ?>

        <?= $form->field($model, 'sertifikat')->fileInput(['maxlength' => true]) ?>

    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
