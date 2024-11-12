<?php

namespace frontend\controllers;

use common\models\Teacher;
use common\models\TeacherSearch;
use PhpOffice\PhpWord\IOFactory;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TeacherController implements the CRUD actions for Teacher model.
 */
class TeacherController extends Controller
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
     * Lists all Teacher models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TeacherSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Teacher model.
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
     * Creates a new Teacher model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($id)
    {
        $model = new Teacher();

        if($id){
            $model->lecture_id = $id;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                $model->file = UploadedFile::getInstance($model, 'file');

                if($model->file){
                    $directory = 'teachers/' . $model->lecture->type;
                    if (!is_dir($directory)) {
                        mkdir($directory, 0755, true);
                    }

                    $filePath = $directory . '/' .Yii::$app->security->generateRandomString(8)
                        . '.' . $model->file->extension;
                    $model->file->saveAs($filePath);
                    $data = $this->parseDocx($filePath);
                    $this->storeTeacherData($data, $model->lecture_id);
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

                        if (count($cells) == 2) {
                            // Accessing the first cell (name)
                            $cell0 = $cells[0];
                            $name = '';

                            // Extract text from the first cell (if it contains TextRun elements)
                            foreach ($cell0->getElements() as $cellElement) {
                                if ($cellElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                    // Iterate through the Text elements within the TextRun
                                    foreach ($cellElement->getElements() as $textElement) {
                                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                            $name .= $textElement->getText(); // Append the text to $name
                                        }
                                    }
                                }
                            }

                            // Accessing the second cell (organization)
                            $cell1 = $cells[1];
                            $organization = '';

                            // Extract text from the second cell (if it contains TextRun elements)
                            foreach ($cell1->getElements() as $cellElement) {
                                if ($cellElement instanceof \PhpOffice\PhpWord\Element\TextRun) {
                                    // Iterate through the Text elements within the TextRun
                                    foreach ($cellElement->getElements() as $textElement) {
                                        if ($textElement instanceof \PhpOffice\PhpWord\Element\Text) {
                                            $organization .= $textElement->getText(); // Append the text to $organization
                                        }
                                    }
                                }
                            }

                            // Store the name and organization in the $data array
                            $data[] = [
                                'name' => $name,
                                'organization' => $organization,
                            ];
                        }
                    }
                }
            }
        }

        return $data;
    }

    public function storeTeacherData($data, $lecture_id)
    {
        foreach ($data as $item) {
            $teacher = new Teacher();
            $teacher->name = $item['name'];
            $teacher->organization = $item['organization'];
            $teacher->lecture_id = $lecture_id;

            $teacher->save(false);
        }
    }


    /**
     * Updates an existing Teacher model.
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
     * Deletes an existing Teacher model.
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
     * Finds the Teacher model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Teacher the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Teacher::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
