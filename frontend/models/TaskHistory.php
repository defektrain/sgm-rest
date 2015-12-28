<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "task_history".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $task_id
 * @property integer $last_executor_id
 * @property integer $new_executor_id
 * @property string $comment
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $lastExecutor
 * @property User $newExecutor
 * @property Task $task
 * @property User $user
 */
class TaskHistory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task_history';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeSave($insert)
    {
        if (!$this->user_id) $this->user_id = Yii::$app->user->id;
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['task_id', 'last_executor_id', 'new_executor_id'], 'required'],
            [['user_id', 'task_id', 'last_executor_id', 'new_executor_id', 'created_at', 'updated_at'], 'integer'],
            [['comment'], 'string'],
            [['user_id'], 'safe'],
            [
                ['comment'],
                'required',
//                'when' => function ($model) {
//                    return !$model->avatar;
//                },
                'whenClient' => "function (attribute, value) {
                    return changed;
                }"
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Пользователь'),
            'task_id' => Yii::t('app', 'Задание'),
            'last_executor_id' => Yii::t('app', 'Предыдущий исполнитель'),
            'new_executor_id' => Yii::t('app', 'Новый исполнитель'),
            'comment' => Yii::t('app', 'Комментарий'),
            'created_at' => Yii::t('app', 'Дата'),
            'updated_at' => Yii::t('app', 'Изменено'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLastExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'last_executor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNewExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'new_executor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTask()
    {
        return $this->hasOne(Task::className(), ['id' => 'task_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
