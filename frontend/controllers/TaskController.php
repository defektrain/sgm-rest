<?php

namespace frontend\controllers;

use app\models\Task;
use app\models\TaskHistory;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: RAIN
 * Date: 26.12.2015
 * Time: 23:51
 */
class TaskController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Task';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
            'auth' => [$this, 'auth']
        ];
        return $behaviors;
    }

    public function auth($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {
            return $user;
        } else {
            return null;
        }
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['delete'], $actions['view'], $actions['update']);
        return $actions;
    }

    protected function verbs()
    {
        return [
            'list' => ['GET', 'HEAD'],
            'view' => ['GET', 'HEAD'],
            'create' => ['POST'],
            'update' => ['PUT', 'PATCH'],
            'delete' => ['DELETE'],
        ];
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $last_executor_id = $model->executor_id;
        $modelHistory = new TaskHistory();

        if ($model->load(Yii::$app->request->post(), '') && $model->save()) {
            if ($last_executor_id != $model->executor_id && $modelHistory->load(Yii::$app->request->post(), '')) {
                $modelHistory->task_id = $model->id;
                $modelHistory->last_executor_id = $last_executor_id;
                $modelHistory->new_executor_id = $model->executor_id;
                $modelHistory->save();
            }

            return $model;
        }

        throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
    }

    public function actionList()
    {
        $model = Task::find()
            ->andFilterWhere([
                'OR',
                ['=', 'user_id', Yii::$app->user->id],
                ['=', 'executor_id', Yii::$app->user->id],
            ])
            ->andFilterWhere([
                'OR',
                ['=', 'status', Task::STATUS_ACTIVE],
                ['=', 'status', Task::STATUS_NOT_ACTIVE]
            ])
            ->all();

        return $model;
    }

    public function actionIndexMy()
    {
        $model = Task::find()
            ->andFilterWhere([
                '=',
                'user_id',
                Yii::$app->user->id
            ])
            ->andFilterWhere([
                'OR',
                ['=', 'status', Task::STATUS_ACTIVE],
                ['=', 'status', Task::STATUS_NOT_ACTIVE]
            ])
            ->all();

        return $model;
    }

    public function actionTrash()
    {
        $model = Task::find()
            ->andFilterWhere([
                'OR',
                ['=', 'user_id', Yii::$app->user->id],
                ['=', 'executor_id', Yii::$app->user->id],
            ])
            ->andFilterWhere(['=', 'status', Task::STATUS_TRASH])
            ->all();

        return $model;
    }

    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $model;
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = Task::STATUS_TRASH;
        if (!$model->save()) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    protected function findModel($id)
    {
        $model = Task::find()
            ->andFilterWhere([
                'OR',
                ['=', 'user_id', Yii::$app->user->id],
                ['=', 'executor_id', Yii::$app->user->id]
            ])
            ->andFilterWhere([
                'id' => $id
            ])
            ->one();

        if ($model !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}