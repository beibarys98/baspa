<?php

/** @var \yii\web\View $this */
/** @var string $content */

use common\models\Admin;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <link rel="icon" href="<?= Yii::getAlias('@web') ?>/images/adort2.png" type="image/jpg">

    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header>
    <?php
    NavBar::begin([
        'brandLabel' => Html::img('@web/images/adort2.png', ['alt' => 'Logo', 'style' => 'height:30px;']) . Yii::t('app', 'Baspa'),
        'brandUrl' => Admin::findOne(Yii::$app->user->id) ? Url::to('/methodist/index') : Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-md navbar-light bg-light fixed-top shadow-sm'
        ],
    ]);

    $user = \common\models\User::findOne(Yii::$app->user->id);

    $items = [
        ['label' => 'ББ', 'url' => ['/lecture/index', 'type' => 'Білім Басқармасы']],
        ['label' => 'Әдістемелік_Орталық', 'url' => ['/lecture/index', 'type' => 'Әдістемелік Орталық']],
        ['label' => 'Семинар', 'url' => ['/lecture/index', 'type' => 'Семинар']],
        ['label' => 'Семинар_Ақылы', 'url' => ['/lecture/index', 'type' => 'Семинар Ақылы']],
        ['label' => 'Әдістемелік_Құрал', 'url' => ['/lecture/index', 'type' => 'Әдістемелік Құрал']],
        ['label' => 'Электрондық_Орта', 'url' => ['/lecture/index', 'type' => 'Электрондық Орта']],
        ['label' => 'МКШ', 'url' => ['/lecture/index', 'type' => 'Заманауи Білім Берудегі ШЖМ']],
        ['label' => 'Педагогикалық_Шолу', 'url' => ['/lecture/index', 'type' => 'Педагогикалық Шолу']],
        ['label' => 'Сайт', 'url' => ['/lecture/index', 'type' => 'Сайт']],
    ];

    if (Admin::findOne(Yii::$app->user->id)) {
        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'items' => $items
        ]);
    } elseif ($user && $user->methodist) {
        $allowedTypes = explode(',', $user->methodist->type);
        $filteredItems = array_filter($items, function ($item) use ($allowedTypes) {
            return in_array($item['url']['type'], $allowedTypes);
        });

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav me-auto'],
            'items' => $filteredItems,
        ]);
    }

    if (!Yii::$app->user->isGuest) {
        echo Html::beginForm(['/site/logout'], 'post', ['class' => 'd-flex'])
            . Html::submitButton(
                Yii::t('app', 'Шығу') . ' (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout text-decoration-none',
                    'style' => 'color: black']
            )
            . Html::endForm();
    }
    NavBar::end();
    ?>
</header>

<main role="main" class="flex-shrink-0">
    <div class="container">
        <?= Breadcrumbs::widget([
            'homeLink' => ['label' => 'Baspa', 'url' => Yii::$app->homeUrl],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer class="footer mt-auto py-3 text-muted">
    <div class="container">
        <p class="float-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage();
