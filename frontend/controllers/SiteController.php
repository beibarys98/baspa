<?php

namespace frontend\controllers;

use common\models\Admin;
use common\models\Teacher;
use common\models\User;
use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
            ],
            'captcha' => [
                'class' => \yii\captcha\CaptchaAction::class,
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }

        if(Admin::findOne(Yii::$app->user->identity->id) != null) {
            return $this->redirect(['methodist/index']);
        }

        return $this->render('index');
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionSignup()
    {
        $user = new User();
        $teacher = new Teacher();
        if ($user->load(Yii::$app->request->post()) && $teacher->load(Yii::$app->request->post())) {

            $user->generateAuthKey();
            $user->setPassword($user->password);
            $user->save();

            $teacher->user_id = $user->id;
            $teacher->save();

            Yii::$app->session->setFlash('success', Yii::t('app', 'Регистрация прошла успешно!'));
            return $this->goHome();
        }

        return $this->render('signup', [
            'user' => $user,
            'teacher' => $teacher,
        ]);
    }
}
