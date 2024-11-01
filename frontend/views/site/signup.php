<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var $user */
/** @var $teacher */

use common\models\Subject;
use kartik\select2\Select2;
use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;

$this->title = 'Signup';
?>
<div class="site-signup mt-1">
    <div style="margin: 0 auto; width: 500px;">
        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($user, 'username')->textInput(['autofocus' => true, 'placeholder' => 'ИИН'])->label(false) ?>

            <?= $form->field($teacher, 'name')->textInput(['placeholder' => 'Имя'])->label(false) ?>

            <?= $form->field($teacher, 'school')->textInput(['placeholder' => 'Организация'])->label(false) ?>

            <?= $form->field($user, 'password')->passwordInput(['placeholder' => 'Пароль'])->label(false) ?>

            <div class="form-group text-center">
                <?= Html::submitButton('Регистрация', ['class' => 'btn btn-success', 'name' => 'signup-button']) ?>
            </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>
