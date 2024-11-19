<?php

namespace frontend\controllers;

use common\models\search\TeacherSearch;
use common\models\Teacher;
use PhpOffice\PhpWord\IOFactory;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class TeacherController extends Controller
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

    public function actionIndex()
    {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate($id = '')
    {
        $model = new Teacher();

        if($id){
            $model->lecture_id = $id;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->file = UploadedFile::getInstance($model, 'file');

                if($model->file){
                    $directory = 'uploads/';
                    if (!is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $filePath = $directory . '/' .Yii::$app->security->generateRandomString(8)
                        . '.' . $model->file->extension;
                    $model->file->saveAs($filePath);
                    $data = $this->parseDocx($filePath);
                    $this->storeTeacherData($data, $model->lecture_id);
                    unlink($filePath);
                }

                if($id){
                    return $this->redirect(['lecture/view', 'id' => $id]);
                }

                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function parseDocx($newFilePath)
    {
        $phpWord = IOFactory::load($newFilePath);
        $data = [];

        // Loop through each section in the document
        foreach ($phpWord->getSections() as $section) {
            // Loop through each element in the section
            foreach ($section->getElements() as $element) {
                // Check if element is a table
                if ($element instanceof \PhpOffice\PhpWord\Element\Table) {
                    // Loop through each row in the table
                    foreach ($element->getRows() as $row) {
                        $cells = $row->getCells();

                        if (count($cells) == 3) { // Ensure the row has three cells
                            // Access and extract text from the first cell (name)
                            $name = $this->extractCellText($cells[0]);

                            // Access and extract text from the second cell (organization)
                            $organization = $this->extractCellText($cells[1]);

                            // Access and extract text from the third cell (certificate)
                            $certificate = $this->extractCellText($cells[2]);

                            // Store the extracted data
                            $data[] = [
                                'name' => $name,
                                'organization' => $organization,
                                'certificate' => $certificate,
                            ];
                        }
                    }
                }
            }
        }

        return $data;
    }

    private function extractCellText($cell)
    {
        $text = '';
        foreach ($cell->getElements() as $cellElement) {
            if ($cellElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                foreach ($cellElement->getElements() as $textElement) {
                    if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                        $text .= $textElement->getText();
                    }
                }
            }
        }
        return $text;
    }

    public function storeTeacherData($data, $lecture_id)
    {
        foreach ($data as $item) {
            $teacher = new Teacher();
            $teacher->name = $item['name'];
            $teacher->organization = $item['organization'];
            $teacher->certificate = $item['certificate'];
            $teacher->lecture_id = $lecture_id;

            $teacher->save(false);
        }
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
        if (($model = Teacher::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
