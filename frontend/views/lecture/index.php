<?php

use common\models\Lecture;
use yii\bootstrap5\LinkPager;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var \common\models\search\LectureSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

/** @var $type */

$this->title = Yii::t('app', $type);
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bb-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create ' . $type), ['create', 'type' => $type], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    $columns = [
        [
            'attribute' => 'id',
            'headerOptions' => ['style' => 'width:5%;'],
        ],
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a($model->title, ['view', 'id' => $model->id], ['data-pjax' => '0']);
            }
        ],
    ];

    if (!in_array($type, ['adistemelik_qural', 'digital_orta', 'mksh'])) {
        $columns[] = [
            'attribute' => 'alghys',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a($model->alghys, [$model->alghys], ['data-pjax' => '0', 'target' => '_blank']);
            }
        ];

        $columns[] = [
            'label' => in_array($type, ['seminar', 'seminar_plat', 'mksh']) ? 'Diplom' : 'Qurmet',
            'attribute' => 'qurmet',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a($model->qurmet, [$model->qurmet], ['data-pjax' => '0', 'target' => '_blank']);
            }
        ];
    }

    if (in_array($type, ['seminar', 'seminar_plat'])) {
        $columns[] = [
            'attribute' => 'sertifikat',
            'format' => 'raw',
            'value' => function ($model) {
                return Html::a($model->sertifikat, [$model->sertifikat], ['data-pjax' => '0', 'target' => '_blank']);
            }
        ];
    }

    $columns[] = [
        'class' => ActionColumn::className(),
        'template' => '{delete}',
        'urlCreator' => function ($action, Lecture $model, $key, $index, $column) {
            return Url::toRoute([$action, 'id' => $model->id]);
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
