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
            return $this->redirect(['teacher/index']);
        }

        return $this->render('index');
    }

    public function actionPay($id){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->identity->id]);
        $payment = new File();
        $payment->teacher_id = $teacher->id;
        $payment->test_id = $id;

        $purpose = Purpose::find()->one();

        if (Yii::$app->request->isPost) {

            $payment->file = UploadedFile::getInstance($payment, 'file');

            if ($payment->file) {
                $directoryPath = 'receipts/' . $teacher->subject->title;
                if (!is_dir($directoryPath)) {
                    mkdir($directoryPath, 0755, true);
                }
                $filePath = $directoryPath . '/'
                    . $teacher->name . '.'
                    . $payment->file->extension;
                if ($payment->file->saveAs($filePath)) {
                    $payment->type = 'receipt';
                    $payment->path = $filePath;
                    $payment->save(false);
                    return $this->redirect(['site/index']);
                }
            }
        }

        return $this->render('pay', [
            'teacher' => $teacher,
            'purpose' => $purpose,
            'payment' => $payment,
        ]);
    }

    public function actionView($id)
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $question = Question::findOne([$id]);
        $test = Test::findOne($question->test_id);

        $teacher = Teacher::findone(['test_id' => $test->id]);

        if(!$teacher->start_time){
            $teacher->start_time = (new \DateTime())->format('Y-m-d H:i:s');
            $teacher->save(false);
        }

        return $this->render('/site/view', [
            'test' => $test,
            'question' => $question,
            'teacher' => $teacher,
        ]);
    }

    public function actionSubmit()
    {
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $answerId = Yii::$app->request->get('answer_id');
        $questionId = Yii::$app->request->get('question_id');

        $teacherId = Teacher::findOne(['user_id' => Yii::$app->user->id])->id;
        $teacherAnswer = TeacherAnswer::findOne([
            'teacher_id' => $teacherId,
            'question_id' => $questionId,
        ]);

        if (!$teacherAnswer) {
            $teacherAnswer = new TeacherAnswer();
            $teacherAnswer->teacher_id = $teacherId;
            $teacherAnswer->question_id = $questionId;
        }
        $teacherAnswer->answer_id = $answerId;
        $teacherAnswer->save(false);

        $nextQuestion = Question::find()
            ->andWhere(['test_id' => Question::findOne($questionId)->test_id])
            ->andWhere(['>', 'id', $questionId])
            ->orderBy(['id' => SORT_ASC])
            ->one();

        if (!$nextQuestion) {
            $nextQuestion = Question::find()
                ->andWhere(['test_id' => Question::findOne($questionId)->test_id])
                ->orderBy(['id' => SORT_ASC])
                ->one();
        }

        return $this->redirect(['site/view', 'id' => $nextQuestion->id]);
    }

    public function actionEnd($id){
        if(Yii::$app->user->isGuest){
            return $this->redirect(['/site/login']);
        }

        $test = Test::findOne($id);
        $questions = Question::find()->andWhere(['test_id' => $test->id])->all();
        $teacher = Teacher::findOne(['user_id' => Yii::$app->user->id]);

        //unanswered questions? return to test
        $now = new \DateTime();
        $startTime = new \DateTime($teacher->start_time);
        $testDuration = new \DateTime($test->duration);
        $durationInSeconds = ($testDuration->format('H') * 3600)
            + ($testDuration->format('i') * 60)
            + $testDuration->format('s');
        $timeElapsed = $now->getTimestamp() - $startTime->getTimestamp();

        if ($timeElapsed < $durationInSeconds) {
            $unansweredQuestion = Question::find()
                ->leftJoin('teacher_answer', 'question.id = teacher_answer.question_id AND teacher_answer.teacher_id = :teacher_id', [':teacher_id' => $teacher->id])
                ->andWhere(['question.test_id' => $id])
                ->andWhere(['teacher_answer.answer_id' => null])
                ->one();

            if ($unansweredQuestion) {
                Yii::$app->session->setFlash('warning', Yii::t('app', 'Ответьте на все вопросы!'));
                return $this->redirect(['site/view', 'id' => $unansweredQuestion->id]);
            }
        }

        //save end time
        $teacher->end_time = (new \DateTime())->format('Y-m-d H:i:s');
        $teacher->save(false);

        //save results in db
        $score = 0;
        foreach ($questions as $q) {
            $teacherAnswerModel = TeacherAnswer::findOne(['teacher_id' => $teacher->id, 'question_id' => $q->id]);
            if ($teacherAnswerModel !== null) {
                $teacherAnswer = $teacherAnswerModel->answer_id;
                if ($teacherAnswer == $q->answer_id) {
                    $score++;
                }
            }
        }
        $teacher->result = $score;
        $teacher->save(false);

        return $this->redirect(['/site/index']);
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

    public function actionLanguage($view)
    {
        if(Yii::$app->language == 'kz-KZ'){
            Yii::$app->session->set('language', 'ru-RU');
        }else{
            Yii::$app->session->set('language', 'kz-KZ');
        }
        return $this->redirect([$view]);
    }
}
