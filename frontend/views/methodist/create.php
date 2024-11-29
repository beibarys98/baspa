<?php

use kartik\select2\Select2;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Methodist $methodist */
/** @var $user */

$this->title = Yii::t('app', 'Жаңа');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Методисттер'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="methodist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($user, 'username')->textInput()->label('Логин') ?>

    <?= $form->field($user, 'password')->textInput()->label('Құпия сөз') ?>

    <?= $form->field($methodist, 'type')->widget(Select2::class, [
        'data' => [
            'Білім Басқармасы' => 'Білім Басқармасы',
            'Әдістемелік Орталық' => 'Әдістемелік Орталық',
            'Семинар' => 'Семинар',
            'Семинар Ақылы' => 'Семинар Ақылы',
            'Әдістемелік Құрал' => 'Әдістемелік Құрал',
            'Электрондық Орта' => 'Электрондық Орта',
            'МКШ' => 'МКШ',
            'Педагогикалық Шолу' => 'Педагогикалық Шолу',
            'Сайт' => 'Сайт',
        ],
        'options' => [
            'multiple' => true, // Allows selecting multiple tags
        ],
        'pluginOptions' => [
            'tags' => true, // Allows creating new tags dynamically
            'tokenSeparators' => [',', ' '], // Define separators for creating tags
        ],
    ])->label('Жауап береді');
    ?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Сақтау'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
