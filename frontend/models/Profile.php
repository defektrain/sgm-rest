<?php

namespace app\models;

use common\models\User;
use webvimark\image\Image;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $fio
 * @property string $birthday
 * @property string $avatar
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property User $user
 */
class Profile extends \yii\db\ActiveRecord
{
    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
    }

    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function beforeDelete()
    {
        if (unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPath'] . $this->avatar) &&
            unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPreviewPath'] . $this->avatar)
        ) {
            return parent::beforeDelete();
        } else {
            return false;
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name', 'fio', 'birthday'], 'required'],
            [['user_id'], 'integer'],
            [['birthday'], 'safe'],
            [['name', 'fio'], 'string', 'max' => 32],
            [['avatar'], 'string'],
            [['user_id'], 'unique'],
//            [
//                ['imageFile'],
//                'required',
//                'when' => function ($model) {
//                    return !$model->avatar;
//                },
//                'whenClient' => "function (attribute, value) {
//                    return !$('#profile-avatar').val();
//                }"
//            ],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, gif'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Имя'),
            'fio' => Yii::t('app', 'Фамилия'),
            'birthday' => Yii::t('app', 'Дата рождения'),
            'avatar' => Yii::t('app', 'Аватар'),
            'imageFile' => Yii::t('app', 'Аватар'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function getFullname()
    {
        return $this->name . ' ' . $this->fio;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function uploadImage()
    {
        if ($this->validate()) {
            if (!$this->imageFile) {
                return true;
            }
            if ($this->avatar) {
                unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPath'] . $this->avatar);
                unlink(\Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPreviewPath'] . $this->avatar);
            }
            $imageName = Yii::$app->security->generateRandomString() . '.' . $this->imageFile->extension;
            $imageDatePath = date('Ymd') . '/';
            $imageDir = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPath'] . $imageDatePath;
            FileHelper::createDirectory($imageDir);
            $resultSaveImage = $this->imageFile->saveAs($imageDir . $imageName);
            if ($resultSaveImage) {
                $imagePreview = Image::factory($imageDir . $imageName);
                $imagePreview->resize(200, 200);
                $imagePreviewDir = \Yii::getAlias('@webroot') . '/' . Yii::$app->params['uploadPreviewPath'] . $imageDatePath;
                FileHelper::createDirectory($imagePreviewDir);
                $resultSaveImagePreview = $imagePreview->save($imagePreviewDir . $imageName);
                if ($resultSaveImagePreview) {
                    $this->avatar = $imageDatePath . $imageName;
                    return true;
                }
            }
        }
        return false;
    }
}
