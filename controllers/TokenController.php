<?php

namespace app\controllers;

use Yii;
use app\models\Token;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TokenController implements the CRUD actions for Token model.
 */
class TokenController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Token models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Token::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Token model.
     * @param integer $user_id
     * @param string $code
     * @param integer $type
     * @return mixed
     */
    public function actionView($user_id, $code, $type)
    {
        $model = $this->findModel($user_id, $code, $type);
        return $this->render('view', [
            'model' => $this->findModel($user_id, $code, $type),
        ]);
    }

    /**
     * Creates a new Token model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Token();

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'code' => $model->code, 'type' => $model->type]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Token model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $user_id
     * @param string $code
     * @param integer $type
     * @return mixed
     */
    public function actionUpdate($user_id, $code, $type)
    {
        $model = $this->findModel($user_id, $code, $type);

        if ($model->loadAll(Yii::$app->request->post()) && $model->saveAll()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'code' => $model->code, 'type' => $model->type]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Token model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $user_id
     * @param string $code
     * @param integer $type
     * @return mixed
     */
    public function actionDelete($user_id, $code, $type)
    {
        $this->findModel($user_id, $code, $type)->deleteWithRelated();

        return $this->redirect(['index']);
    }

    
    /**
     * Finds the Token model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $user_id
     * @param string $code
     * @param integer $type
     * @return Token the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $code, $type)
    {
        if (($model = Token::findOne(['user_id' => $user_id, 'code' => $code, 'type' => $type])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
