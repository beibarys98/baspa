<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\File $model */
/** @var yii\widgets\ActiveForm $form */
/** @var $lecture */
/** @var $teacher */

$this->title = 'Квитанция: ' . $teacher->name;

// Add breadcrumbs
$this->params['breadcrumbs'][] = [
    'label' => $lecture->type,
    'url' => ['/lecture/index', 'type' => $lecture->type], // Link to lecture/index with type as a parameter
];

$this->params['breadcrumbs'][] = [
    'label' => $lecture->title, // Replace with the lecture's specific attribute or title
    'url' => ['/lecture/view', 'id' => $lecture->id], // Link to lecture/view with lecture_id as a parameter
];

$this->params['breadcrumbs'][] = $this->title; // Current page

?>

<div class="file-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
