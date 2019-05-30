<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\base\Security;

use app\models\Platform;
use app\models\Device;

class Person extends ActiveRecord
{
    public $picImage;
    public $removePicImage;
    
    /**
     * @return string table name.
     */
    public static function tableName()
    {
        return '{{%persons}}';
    }


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['name', 'email', 'platform_id', 'device_id'], 'required'],

            [['email'], 'email'],

            [['platform_id', 'device_id'], 'integer'],

            [['email'], 'unique', 
              'targetAttribute' => 'email'],

            [['platform_id'], 'exist', 
              'targetClass' => Platform::className(), 
              'targetAttribute' => ['platform_id' => 'id']],

            [['device_id'], 'exist', 
              'targetClass' => Device::className(), 
              'targetAttribute' => ['device_id' => 'id']],

            [['picImage'], 'image', 'extensions' => 'png, jpg',
              'minWidth' => 100, 'maxWidth' => 1000,
              'minHeight' => 100, 'maxHeight' => 1000,
              'skipOnEmpty' => true],

              [['removePicImage'], 'boolean'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'platform_id' => 'Platform',
            'device_id' => 'Device',
        ];
    }


    public function getPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }


    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }


    public function savePicImage()
    {   
        if ($this->validate()) {
            $picName = (new Security)->generateRandomString() . '.' . $this->picImage->extension;
            $this->picImage->saveAs('uploads/' . $picName);
            $this->pic = $picName;
        }
    }


    public function loadOrRemovePicImage()
    {
        if ($this->removePicImage) {
            $this->pic = null;
            return true;
        }
        
        $this->picImage = UploadedFile::getInstance($this, 'picImage');
        return true;
    }


    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }


        if ($this->picImage) {
            $this->savePicImage();
        }

        return true;
    }
}