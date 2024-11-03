<?php

use common\models\Question;
use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var $file */

\yii\web\YiiAsset::register($this);

?>
<div class="test-view">

    <div class="mt-1">
        <?php
        $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data'],
        ]); ?>

        <div class="shadow-sm p-3" style="border: 1px solid black; border-radius: 10px;" >
            <?= $form->field($file, 'file')->fileInput()->label('Квитанция') ?>
            <?= Html::submitButton(Yii::t('app', 'Загрузить'), ['class' => 'btn btn-primary w-100']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
