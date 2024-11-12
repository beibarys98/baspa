<?php

use common\models\Certificate;
use common\models\Teacher;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var $model */
/** @var common\models\TeacherSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = $model->title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="teacher-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    Html::a('Мұғалімдер қосу', ['teacher/create', 'id' => $model->id], ['class' => 'btn btn-secondary mb-3'])
    ?>

    <?=
    Html::a('Марапаттау', ['present', 'id' => $model->id], ['class' => 'btn btn-success mb-3'])
    ?>

    <?=
    Html::a('Жуктеп алу', ['certificates', 'id' => $model->id], ['class' => 'btn btn-warning mb-3'])
    ?>

    <?=
    Html::a('Журнал', ['journal', 'id' => $model->id], ['class' => 'btn btn-primary mb-3'])
    ?>


    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => [
            'id',
            'name',
            'organization',
            [
                'label' => 'File',
                'format' => 'raw',
                'value' => function ($model) {
                    $certificate = Certificate::find()->andWhere(['teacher_id' => $model->id])->andWhere(['not', ['path' => null]])->one();

                    if ($certificate && $certificate->path) {
                        return Html::a('Файл', [$certificate->path], [
                            'data-pjax' => '0',
                            'target' => '_blank',
                        ]);
                    } else {
                        return '---';
                    }
                }
            ],

            [
                'class' => ActionColumn::className(),
                'template' => '{update} <span style="margin-left: 15px;"></span> {delete}',
                'urlCreator' => function ($action, Teacher $model, $key, $index, $column) {
                    // Adjust routes for update and delete actions
                    if ($action === 'update') {
                        return Url::toRoute(['teacher/update', 'id' => $model->id]);
                    }
                    if ($action === 'delete') {
                        return Url::toRoute(['teacher/delete', 'id' => $model->id]);
                    }
                }
            ]
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
