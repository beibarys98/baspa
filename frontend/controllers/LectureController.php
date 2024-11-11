<?php

namespace frontend\controllers;

use common\models\Lecture;
use common\models\LectureSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * LectureController implements the CRUD actions for Lecture model.
 */
class LectureController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Lecture models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LectureSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionBb()
    {
        $searchModel = new LectureSearch();

        $params = $this->request->queryParams;
        $params['LectureSearch']['type'] = 'bb';

        $dataProvider = $searchModel->search($params);

        return $this->render('bb', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateBb()
    {
        $model = new Lecture();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->alghys = UploadedFile::getInstance($model, 'alghys');
                $model->qurmet = UploadedFile::getInstance($model, 'qurmet');

                foreach (['alghys', 'qurmet'] as $attribute) {
                    if ($model->$attribute) { // If a new file is uploaded

                        $directory = 'templates/bb/' . $model->title;
                        if (!is_dir($directory)) {
                            mkdir($directory, 0755, true);
                        }

                        $filePath = $directory . '/' . $attribute . '.' . $model->$attribute->extension;
                        $model->$attribute->saveAs($filePath);
                        $model->$attribute = $filePath; // Update to new file path
                    }
                }

                $model->type = 'bb';
                $model->save(false);
                return $this->redirect(['bb',]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create-bb', [
            'model' => $model,
        ]);
    }

    public function actionAdistemelik()
    {
        $searchModel = new LectureSearch();

        $params = $this->request->queryParams;
        $params['LectureSearch']['type'] = 'adistemelik';

        $dataProvider = $searchModel->search($params);

        return $this->render('adistemelik', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateAdistemelik()
    {
        $model = new Lecture();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->alghys = UploadedFile::getInstance($model, 'alghys');
                $model->qurmet = UploadedFile::getInstance($model, 'qurmet');

                foreach (['alghys', 'qurmet'] as $attribute) {
                    if ($model->$attribute) { // If a new file is uploaded

                        $directory = 'templates/adistemelik/' . $model->title;
                        if (!is_dir($directory)) {
                            mkdir($directory, 0755, true);
                        }

                        $filePath = $directory . '/' . $attribute . '.' . $model->$attribute->extension;
                        $model->$attribute->saveAs($filePath);
                        $model->$attribute = $filePath; // Update to new file path
                    }
                }

                $model->type = 'adistemelik';
                $model->save(false);
                return $this->redirect(['adistemelik',]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create-adistemelik', [
            'model' => $model,
        ]);
    }

    public function actionSeminar()
    {
        $searchModel = new LectureSearch();

        $params = $this->request->queryParams;
        $params['LectureSearch']['type'] = 'seminar';

        $dataProvider = $searchModel->search($params);

        return $this->render('seminar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateSeminar()
    {
        $model = new Lecture();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->alghys = UploadedFile::getInstance($model, 'alghys');
                $model->qurmet = UploadedFile::getInstance($model, 'qurmet');
                $model->sertifikat = UploadedFile::getInstance($model, 'sertifikat');

                foreach (['alghys', 'qurmet', 'sertifikat'] as $attribute) {
                    if ($model->$attribute) { // If a new file is uploaded

                        $directory = 'templates/seminar/' . $model->title;
                        if (!is_dir($directory)) {
                            mkdir($directory, 0755, true);
                        }

                        $filePath = $directory . '/' . $attribute . '.' . $model->$attribute->extension;
                        $model->$attribute->saveAs($filePath);
                        $model->$attribute = $filePath; // Update to new file path
                    }
                }

                $model->type = 'seminar';
                $model->save(false);
                return $this->redirect(['seminar']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create-seminar', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Lecture model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Lecture model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Lecture();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Lecture model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Lecture model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Lecture model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Lecture the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Lecture::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
