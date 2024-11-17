<?php

use common\models\File;
use common\models\search\TeacherSearch;
use common\models\Teacher;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var $model */
/** @var TeacherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = $model->title;

// Add breadcrumbs
$this->params['breadcrumbs'][] = [
    'label' => $model->type,
    'url' => ['/lecture/index', 'type' => $model->type], // Link to lecture/index with type as a parameter
];
$this->params['breadcrumbs'][] = $this->title; // Current page
?>
<div class="teacher-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    Html::a('Мұғалімдер қосу', ['teacher/create', 'id' => $model->id], ['class' => 'btn btn-secondary mb-3'])
    ?>

    <?php if (!in_array($model->type, ['adistemelik_qural', 'digital_orta', 'mksh'])):?>

        <?=
        Html::a('Марапаттау', ['present', 'id' => $model->id], ['class' => 'btn btn-success mb-3'])
        ?>

        <?=
        Html::a('Жуктеп алу', ['certificates', 'id' => $model->id], ['class' => 'btn btn-warning mb-3'])
        ?>

        <?=
        Html::a('Журнал', ['journal', 'id' => $model->id], ['class' => 'btn btn-danger mb-3'])
        ?>

    <?php endif; ?>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $columns = [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:5%;'],
        ],
        'name',
        'organization',
    ];

    if ($model->type == 'seminar_plat') {

        $columns[] = [
            'label' => 'File',
            'format' => 'raw',
            'value' => function ($model) {
                $content = '';

                $payment = File::find()
                    ->andWhere(['type' => 'payment'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();

                if ($payment && $payment->path) {
                    $content .= Html::a('Квитанция', [$payment->path], [
                        'data-pjax' => '0',
                        'target' => '_blank',
                    ]);
                } else {
                    $content .= '<div class="d-flex justify-content-between">
                        <span>---</span>' . Html::a('+',
                            ['/payment/create', 'teacher_id' => $model->id, 'lecture_id' => $model->lecture_id],
                            ['class' => 'btn btn-secondary btn-sm'])
                        . '</div>';
                }

                $certificate = File::find()
                    ->andWhere(['type' => 'certificate'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($certificate) {
                    $content .= Html::a('Марапат', [$certificate->path], [
                        'data-pjax' => '0',
                        'target' => '_blank',
                    ]);
                } else {
                    $content .= '---';
                }

                return $content;
            }

        ];

    } elseif ($model->type == 'adistemelik_qural') {
        $columns[] = [
            'headerOptions' => ['style' => 'width:15%;'],
            'label' => 'File',
            'format' => 'raw',
            'value' => function ($model) {
                $content = '';

                $payment = File::find()
                    ->andWhere(['type' => 'payment'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($payment && $payment->path) {
                    $content .= Html::a('Квитанция', [$payment->path], [
                            'data-pjax' => '0',
                            'target' => '_blank',
                        ]) . '<br>';
                } else {
                    $content .= '<div class="d-flex justify-content-between">
                        <span>---</span>' . Html::a('+',
                            ['/payment/create', 'teacher_id' => $model->id, 'lecture_id' => $model->lecture_id],
                            ['class' => 'btn btn-primary btn-sm'])
                        . '</div>';
                }

                $opinion = File::find()
                    ->andWhere(['type' => 'opinion'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($opinion) {
                    $content .= Html::a('Пікір #1', ['view-opinion', 'id' => $opinion->id], [
                            'data-pjax' => '0',
                            'target' => '_blank',
                        ]) . '<br>';
                } else {
                    $content .= '<div class="d-flex justify-content-between mt-1">
                        <span>---</span>' . Html::a('+',
                            ['/opinion/create', 'teacher_id' => $model->id, 'lecture_id' => $model->lecture_id],
                            ['class' => 'btn btn-primary btn-sm'])
                        . '</div>';
                }

                $material = File::find()
                    ->andWhere(['type' => 'material'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($material) {
                    $content .= Html::a('Материал', ['view-material', 'id' => $material->id], [
                            'data-pjax' => '0',
                            'target' => '_blank',
                        ]) . '<br>';
                } else {
                    $content .= '<div class="d-flex justify-content-between mt-1">
                        <span>---</span>' . Html::a('+',
                        ['/material/create', 'teacher_id' => $model->id, 'lecture_id' => $model->lecture_id],
                        ['class' => 'btn btn-primary btn-sm'])
                        . '</div>';
                }

                return $content;
            }
        ];
    } else {

        $columns[] = [
            'label' => 'File',
            'format' => 'raw',
            'value' => function ($model) {
                $certificate = File::find()
                    ->andWhere(['type' => 'certificate'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();

                if ($certificate && $certificate->path) {
                    return Html::a('Марапат', [$certificate->path], [
                        'data-pjax' => '0',
                        'target' => '_blank',
                    ]);
                } else {
                    return '---';
                }
            }
        ];
    }

    $columns[] = [
        'class' => ActionColumn::className(),
        'template' => '<span class="ms-3"></span> {update} <span class="ms-3"></span> {delete}',
        'urlCreator' => function ($action, Teacher $model, $key, $index, $column) {
            // Adjust routes for update and delete actions
            if ($action === 'update') {
                return Url::toRoute(['teacher/update', 'id' => $model->id]);
            }
            if ($action === 'delete') {
                return Url::toRoute(['teacher/delete', 'id' => $model->id]);
            }
        }
    ];
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => $columns,
    ]); ?>

    <?php Pjax::end(); ?>

</div>
