<?php

namespace frontend\controllers;

use app\models\Project;
use common\models\User;
use Yii;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Created by PhpStorm.
 * User: RAIN
 * Date: 26.12.2015
 * Time: 23:51
 */
class ProjectController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Project';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
                ['class' => QueryParamAuth::className(), 'tokenParam' => 'access_token'],
                ['class' => HttpBasicAuth::className(), 'auth' => [$this, 'auth']],
            ],
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
        unset($actions['index'], $actions['delete'], $actions['view']);
        return $actions;
    }

    public function actionList()
    {
        $model = Project::find()
            ->andFilterWhere([
                'OR',
                ['=', 'user_id', Yii::$app->user->id],
                ['=', 'executor_id', Yii::$app->user->id],
            ])
            ->andFilterWhere([
                'OR',
                ['=', 'status', Project::STATUS_ACTIVE],
                ['=', 'status', Project::STATUS_NOT_ACTIVE]
            ])
            ->all();

        return $model;
    }

    public function actionIndexMy()
    {
        $model = Project::find()
            ->andFilterWhere([
                '=', 'user_id', Yii::$app->user->id
            ])
            ->andFilterWhere([
                'OR',
                ['=', 'status', Project::STATUS_ACTIVE],
                ['=', 'status', Project::STATUS_NOT_ACTIVE]
            ])
            ->all();

        return $model;
    }

    public function actionTrash()
    {
        $model = Project::find()
            ->andFilterWhere([
                'OR',
                ['=', 'user_id', Yii::$app->user->id],
                ['=', 'executor_id', Yii::$app->user->id],
            ])
            ->andFilterWhere(['=', 'status', Project::STATUS_TRASH])
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
        $model->status = Project::STATUS_TRASH;
        if (!$model->save()) {
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }

    protected function findModel($id)
    {
        $model = Project::find()
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