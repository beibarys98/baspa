<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Teacher $model */
/** @var yii\widgets\ActiveForm $form */
/** @var $lecture */

$this->title = 'Мұғалімдер қосу';

$this->params['breadcrumbs'][] = ['label' => $lecture->type, 'url' => ['/lecture/index', 'type' => $lecture->type]];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="teacher-form">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
