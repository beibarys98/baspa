<?php

use common\models\Methodist;
use yii\bootstrap5\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var yii\web\View $this */
/** @var common\models\search\MethodistSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'Методисттер');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="methodist-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Жаңа'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php Pjax::begin(); ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['class' => 'table table-hover'],
        'pager' => [
            'class' => LinkPager::class,
        ],
        'columns' => [
            [
                'attribute' => 'id',
                'headerOptions' => ['style' => 'width:5%;'],
            ],
            [
                'label' => 'Логин',
                'attribute' => 'username',
                'value' => 'user.username',
            ],
            [
                'attribute' => 'type',
                'label' => 'Жауап береді',
                'format' => 'raw',
                'value' => function($model) {
                    // Explode the comma-separated values into an array
                    $types = explode(',', $model->type);

                    // Return the list of items, each on a new line
                    return implode('<br>', $types);  // <br> adds a line break between each item
                },
            ],

            [
                'class' => ActionColumn::className(),
                'template' => '{delete}',
                'urlCreator' => function ($action, Methodist $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>

    <?php Pjax::end(); ?>

</div>
