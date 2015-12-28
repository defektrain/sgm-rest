<?php

namespace frontend\controllers;

use app\models\Profile;
use common\models\User;
use filsh\yii2\oauth2server\filters\auth\CompositeAuth;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

/**
 * Created by PhpStorm.
 * User: RAIN
 * Date: 26.12.2015
 * Time: 23:51
 */
class ProfileController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\Profile';

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
        return [];
    }

    public function actionView()
    {
        $model = Profile::find()->where('user_id = ' . Yii::$app->user->id)->one();
        if (!$model) {
            $modelProfile = new Profile();
            $modelProfile->user_id = Yii::$app->user->id;
            $modelProfile->save(false);
        } else {
            $modelProfile = $model;
        }

        return $modelProfile;
    }

    public function actionUpdate()
    {
        $model = Profile::find()->where('user_id = ' . Yii::$app->user->id)->one();

        if ($model->load(Yii::$app->request->post(), '')) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->uploadImage() && $model->save(false)) {
                return $model;
            } else {
                throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
            }
        } else {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model;
    }
}