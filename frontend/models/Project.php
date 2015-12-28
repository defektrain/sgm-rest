<?php

namespace app\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "project".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $executor_id
 * @property string $name
 * @property string $text
 * @property integer $date_create
 * @property integer $date_end
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 * @property Task[] $tasks
 */
class Project extends \yii\db\ActiveRecord
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
        return 'project';
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
            [['executor_id', 'name', 'text', 'date_create', 'date_end'], 'required'],
            [['user_id', 'executor_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['text'], 'string'],
            [['created_at', 'updated_at', 'date_create', 'date_end'], 'safe'],
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
            'executor_id' => Yii::t('app', 'Исполнитель'),
            'name' => Yii::t('app', 'Название'),
            'text' => Yii::t('app', 'Описание'),
            'date_create' => Yii::t('app', 'Дата создания'),
            'date_end' => Yii::t('app', 'Планируемая дата завершения'),
            'status' => Yii::t('app', 'Статус'),
            'statusinfo' => Yii::t('app', 'Статус'),
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
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
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
    public function getTasks()
    {
        return $this->hasMany(Task::className(), ['project_id' => 'id']);
    }
}
