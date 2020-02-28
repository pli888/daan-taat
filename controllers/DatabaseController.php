<?php

namespace app\controllers;

use Yii;
use app\models\Database;
use app\models\DatabaseSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

// New
use app\models\Table;

/**
 * DatabaseController implements the CRUD actions for Database model.
 */
class DatabaseController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Database models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DatabaseSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Database model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Displays the list of tables in a database.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionExamine($id)
    {
        $tab_names = array();  // For storing table names
        $db1_ht = array();  // For storing columns for each table

        // Query table names
        $tables = Yii::$app->db1->createCommand("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name")->queryAll();
        // Extract table names into an array
        foreach ($tables as $table)
            $tab_names[] = $table['table_name'];

        // For each table, get their column names
        foreach ($tab_names as $tab_name) {
            $columns = Yii::$app->db1->createCommand("SELECT column_name, data_type FROM information_schema.columns WHERE table_schema = 'public' AND table_name = '$tab_name'")->queryAll();
            Yii::error($columns, 'basket');
            $col_names = array();
            foreach ($columns as $column) {
                $col_names[] = [$column['column_name'], $column['data_type']];
            }
            $db1_ht[$tab_name] = $col_names; // Add entry
        }

        return $this->render('examine', [
            'model' => $this->findModel($id),
            'table_names' => $tab_names,
            'tables' => $db1_ht
        ]);
    }

    /**
     * Import table and column metadata into application.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionImport($id)
    {
        // Query table names
        $rows = Yii::$app->db1->createCommand("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public' ORDER BY table_name")->queryAll();
        Yii::error($rows, 'basket');

        // Update database with table names
        foreach ($rows as $row) {
            $table_name = $row['table_name'];
            $table = new Table();
            $table->database_id = $id;
            $table->name = $table_name;
            $table->save();
        }

        // Update imported column to true
        $database = $this->findModel($id);
        $database->imported = TRUE;
        $database->save();

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Database model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Database();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Database model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Database model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Database model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Database the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Database::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
