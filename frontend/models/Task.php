<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "task".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $project_id
 * @property string $name
 * @property string $text
 * @property integer $executor_id
 * @property integer $status
 * @property integer $date_end
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $executor
 * @property Project $project
 * @property User $user
 * @property TaskHistory[] $taskHistories
 */
class Task extends \yii\db\ActiveRecord
{
    const ACCESS_MY = 1;
    const ACCESS_ALL = 0;

    const STATUS_ACTIVE = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_TRASH = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
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
            [['project_id', 'name', 'text', 'executor_id'], 'required'],
            [['user_id', 'project_id', 'executor_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at', 'date_end'], 'safe'],
            [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'Владелец'),
            'project_id' => Yii::t('app', 'Проект'),
            'name' => Yii::t('app', 'Название'),
            'text' => Yii::t('app', 'Описание'),
            'executor_id' => Yii::t('app', 'Исполнитель'),
            'status' => Yii::t('app', 'Статус'),
            'statusinfo' => Yii::t('app', 'Статус'),
            'date_end' => Yii::t('app', 'Срок выполнения'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getStatusinfo()
    {
        if ($this->status == self::STATUS_NOT_ACTIVE) {
            return 'Неактивен';
        } elseif ($this->status == self::STATUS_TRASH) {
            return 'В корзине';
        } else {
            return 'Активен';
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getExecutor()
    {
        return $this->hasOne(User::className(), ['id' => 'executor_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::className(), ['id' => 'project_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTaskHistories()
    {
        return $this->hasMany(TaskHistory::className(), ['task_id' => 'id']);
    }
}
