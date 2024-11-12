<?php

namespace frontend\controllers;

use common\models\Certificate;
use common\models\Lecture;
use common\models\LectureSearch;
use common\models\Teacher;
use common\models\TeacherSearch;
use Yii;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

class LectureController extends Controller
{
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

    public function actionIndex($type)
    {
        $searchModel = new LectureSearch();

        $params = $this->request->queryParams;
        $params['LectureSearch']['type'] = $type;

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'type' => $type,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id){

        $model = $this->findModel($id);

        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search(array_merge(
            $this->request->queryParams,
            ['lectureId' => $id]
        ));

        return $this->render('view', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate($type)
    {
        $model = new Lecture();

        $model->type = $type;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->alghys = UploadedFile::getInstance($model, 'alghys');
                $model->qurmet = UploadedFile::getInstance($model, 'qurmet');
                if ($type == 'seminar') {
                    $model->sertifikat = UploadedFile::getInstance($model, 'sertifikat');
                }

                foreach (array_filter(['alghys', 'qurmet', $type === 'seminar' ? 'sertifikat' : null]) as $attribute) {
                    if ($model->$attribute) {
                            $directory = 'templates/' . $type . '/' . $model->title;
                            if (!is_dir($directory)) {
                                mkdir($directory, 0755, true);
                            }

                            $filePath = $directory . '/' . $attribute . '.' . $model->$attribute->extension;
                            $model->$attribute->saveAs($filePath);
                            $model->$attribute = $filePath;
                        }
                    }

                $model->save(false);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionPresent($id)
    {
        $lecture = Lecture::findOne($id);
        $teachers = Teacher::find()->andWhere(['lecture_id' => $lecture->id])->all();

        foreach ($teachers as $teacher) {
            $this->certificate($teacher, $lecture);
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function certificate($teacher, $lecture)
    {
        $fileAttribute = $lecture->alghys ?? $lecture->qurmet ?? $lecture->sertifikat;
        $imgPath = Yii::getAlias("@webroot/") . $fileAttribute;
        $image = imagecreatefromjpeg($imgPath);
        $textColor = imagecolorallocate($image, 227, 41, 29);
        $fontPath = Yii::getAlias('@frontend/fonts/times.ttf');

        //writing name
        $averageCharWidth = 9.5;
        $numChars = strlen($teacher->name);
        $textWidth = $numChars * $averageCharWidth;
        $cx = 950;
        $x = (int)($cx - ($textWidth / 2));
        imagettftext($image, 28, 0, $x, 760, $textColor, $fontPath, $teacher->name);

        //writing number
        $formattedId = str_pad($teacher->id, 5, '0', STR_PAD_LEFT);
        imagettftext($image, 28, 0, 1480, 1100, $textColor, $fontPath, $formattedId);

        $directoryPath = 'certificates/' . $lecture->type . '/' . $lecture->title;
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0755, true);
        }
        $newPath = $directoryPath . '/' . $teacher->name . '.jpg';
        imagejpeg($image, $newPath);
        imagedestroy($image);

        $certificate = new Certificate();
        $certificate->lecture_id = $lecture->id;
        $certificate->teacher_id = $teacher->id;
        $certificate->path = $newPath;
        $certificate->save(false);
    }

    public function actionCertificates($id){
        $lecture = Lecture::findOne($id);
        $filePaths = Certificate::find()
            ->andWhere(['lecture_id' => $id])
            ->select('path')
            ->all();
        $zipFileName = $lecture->title . '.zip';

        $zip = new \ZipArchive();
        $zipFilePath = Yii::getAlias('@webroot/uploads/' . $zipFileName);
        if ($zip->open($zipFilePath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== TRUE) {
            throw new HttpException(500, 'Could not create ZIP file.');
        }
        foreach ($filePaths as $filePath) {
            $realPath = Yii::getAlias('@webroot') . '/' . $filePath;
            if (file_exists($realPath)) {
                $zip->addFile($realPath, basename($realPath));
            } else {
                Yii::error("File not found: $realPath");
            }
        }
        $zip->close();
        return Yii::$app->response->sendFile($zipFilePath)->on(Response::EVENT_AFTER_SEND, function () use ($zipFilePath) {
            @unlink($zipFilePath);
        });
    }

    public function actionJournal($id)
    {
        //save results in pdf
        $results = new ActiveDataProvider([
            'query' => Result::find()
                ->select(['teacher_id', 'result'])
                ->andWhere(['test_id' => $id])
                ->groupBy(['teacher_id', 'result']) // Group by both teacher ID and result
                ->orderBy(['result' => SORT_DESC]), // Sort by result
            'pagination' => [
                'pageSize' => false,
            ],
        ]);

        $content2 = $this->renderPartial('journal', [
            'results' => $results,
        ]);
        $pdf2 = new Pdf([
            'mode' => Pdf::MODE_UTF8,
            'content' => $content2,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => '.kv-heading-1{font-size:18px}',
            'filename' => 'Нәтиже.pdf',
        ]);
        $pdf2Output = $pdf2->render();
        $pdfFilePath2 = Yii::getAlias('@webroot/journals/')
            . Yii::$app->security->generateRandomString(8)
            . '.pdf';
        file_put_contents($pdfFilePath2, $pdf2Output);

        $result_pdf = new ResultPdf();
        $result_pdf->test_id = $id;
        $result_pdf->path = $pdfFilePath2; // Use the path for the Excel file
        $result_pdf->save(false);

        return Yii::$app->response->sendFile($result_pdf->path);
    }

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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = Lecture::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
