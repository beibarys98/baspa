<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\File $model */
/** @var yii\widgets\ActiveForm $form */
/** @var $lecture */
/** @var $teacher */
/** @var $type */

$this->title = $teacher->name;

$this->params['breadcrumbs'][] = [
    'label' => $lecture->type,
    'url' => ['/lecture/index', 'type' => $lecture->type],
];

$this->params['breadcrumbs'][] = [
    'label' => $lecture->title,
    'url' => ['/lecture/view', 'id' => $lecture->id],
];

$this->params['breadcrumbs'][] = $this->title; // Current page

?>

<div class="file-form">

    <?php
    $headings = [
        'receipt' => 'Квитанция',
        'opinion1' => 'Пікір #1',
        'opinion2' => 'Пікір #2',
        'material' => 'Материал',
    ];

    $displayTitle = $headings[$type] ?? 'Unknown Type';
    ?>

    <h1><?= $displayTitle ?></h1>


    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
