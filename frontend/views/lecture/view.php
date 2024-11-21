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
/** @var $lecture */
/** @var TeacherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = $lecture->title;

$this->params['breadcrumbs'][] = ['label' => $lecture->type, 'url' => ['/lecture/index', 'type' => $lecture->type]];
$this->params['breadcrumbs'][] = $lecture->title;

?>
<div class="teacher-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    Html::a('Мұғалімдер қосу', ['teacher/create', 'id' => $lecture->id], ['class' => 'btn btn-secondary mb-3'])
    ?>

    <?php if (in_array($lecture->type, ['Білім Басқармасы', 'Әдістемелік Орталық', 'Семинар', 'Семинар Ақылы'])):?>

        <?=
        Html::a('Марапаттау', ['present', 'id' => $lecture->id], ['class' => 'btn btn-success mb-3'])
        ?>

        <?=
        Html::a('Жуктеп алу', ['certificates', 'id' => $lecture->id], ['class' => 'btn btn-warning mb-3'])
        ?>

        <?=
        Html::a('Журнал', ['journal', 'id' => $lecture->id], ['class' => 'btn btn-danger mb-3'])
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

    $columns[] = [
        'headerOptions' => ['style' => 'width:15%;'],
        'label' => 'Файл',
        'format' => 'raw',
        'value' => function ($model) use ($lecture) {
            $content = '';

            if(in_array($lecture->type, ['Семинар Ақылы', 'Әдістемелік Құрал', 'Электрондық Орта',
                'Заманауи Білім Берудегі ШЖМ', 'Педагогикалық Шолу', 'Сайт'])){
                $receipt = File::find()
                    ->andWhere(['type' => 'receipt'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($receipt) {
                    $content .= Html::a('Квитанция', [$receipt->path], [
                        'data-pjax' => '0',
                        'target' => '_blank',
                    ]);
                } else {
                    $content .= 'Квитанция ' . Html::a('жүктеу',
                            ['/file/create', 'lecture_id' => $model->lecture_id, 'teacher_id' => $model->id, 'type' => 'receipt']);
                }

                $content .= '<br>';
            }

            if(in_array($lecture->type, ['Әдістемелік Құрал', 'Электрондық Орта'])) {
                $opinion1 = File::find()
                    ->andWhere(['type' => 'opinion1'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($opinion1) {
                    $content .= Html::a('Пікір #1', [$opinion1->path], [
                        'data-pjax' => '0',
                        'target' => '_blank',
                    ]);
                } else {
                    $content .= 'Пікір #1 ' . Html::a('жүктеу',
                            ['/file/create', 'lecture_id' => $model->lecture_id, 'teacher_id' => $model->id, 'type' => 'opinion1']);
                }

                $content .= '<br>';
            }

            if($lecture->type == 'Әдістемелік Құрал'){
                $opinion2 = File::find()
                    ->andWhere(['type' => 'opinion2'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($opinion2) {
                    $content .= Html::a('Пікір #2', [$opinion2->path], [
                        'data-pjax' => '0',
                        'target' => '_blank',
                    ]);
                } else {
                    $content .= 'Пікір #2 ' . Html::a('жүктеу',
                            ['/file/create', 'lecture_id' => $model->lecture_id, 'teacher_id' => $model->id, 'type' => 'opinion2']);
                }

                $content .= '<br>';
            }

            if(in_array($lecture->type, ['Әдістемелік Құрал',
                'Заманауи Білім Берудегі ШЖМ', 'Педагогикалық Шолу', 'Сайт'])) {
                $material = File::find()
                    ->andWhere(['type' => 'material'])
                    ->andWhere(['lecture_id' => $model->lecture_id])
                    ->andWhere(['teacher_id' => $model->id])
                    ->one();
                if ($material) {
                    $content .= Html::a('Материал', [$material->path], [
                        'data-pjax' => '0',
                        'target' => '_blank',
                    ]);
                } else {
                    $content .= 'Материал ' . Html::a('жүктеу',
                            ['/file/create', 'lecture_id' => $model->lecture_id, 'teacher_id' => $model->id, 'type' => 'material']);
                }

                $content .= '<br>';
            }

            if(in_array($lecture->type, ['Білім Басқармасы', 'Әдістемелік Орталық',
                'Семинар', 'Семинар Ақылы'])) {
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
                    $content .= 'Марапат';
                }
            }

            return $content;
        }
    ];

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
