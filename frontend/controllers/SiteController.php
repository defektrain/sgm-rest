<?php
namespace frontend\controllers;

use app\models\SignupForm;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Site controller
 */
class SiteController extends \yii\rest\ActiveController
{
    public $modelClass = 'app\models\LoginForm';

    public function actions()
    {
        return [];
    }

    protected function verbs()
    {
        return [
            'signup' => ['POST'],
        ];
    }

    public function checkAccess($action, $model = null, $params = [])
    {
        if (\Yii::$app->user->id) {
            throw new ForbiddenHttpException;
        }
    }

    public function actionSignup()
    {
        $this->checkAccess($this->id);

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post(), '')) {
            if ($user = $model->signup()) {
                $response = Yii::$app->getResponse();
                $response->setStatusCode(201);
                return true;
            }
        }

        throw new ServerErrorHttpException('Failed to sign up for unknown reason.');
    }
}
