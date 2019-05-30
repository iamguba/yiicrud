<?php

namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\base\Security;

use app\models\Platform;
use app\models\Device;

class Person extends ActiveRecord
{
    
    /** @var UploadedFile|null user pic */
    public $picImage;


    /** @var boolean whether to remove pic */
    public $removePicImage = false;

    
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
            // required
            [['name', 'email', 'platform_id', 'device_id'], 'required'],

            // email regex
            [['email'], 'email'],

            // integer for ids
            [['platform_id', 'device_id'], 'integer'],

            // check unique by db field
            [['email'], 'unique', 
              'targetAttribute' => 'email'],

            // check if platform_id exists
            [['platform_id'], 'exist', 
              'targetClass' => Platform::className(), 
              'targetAttribute' => ['platform_id' => 'id']],

            // check if device_id exists
            [['device_id'], 'exist', 
              'targetClass' => Device::className(), 
              'targetAttribute' => ['device_id' => 'id']],

            // check image if uploaded
            [['picImage'], 'image', 'extensions' => 'png, jpg',
              'minWidth' => 100, 'maxWidth' => 1000,
              'minHeight' => 100, 'maxHeight' => 1000,
              'skipOnEmpty' => true],


            // small hack, forces this param to boolean
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



    /**
     * @return Platform|null parent platform.
     */
    public function getPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }


    /**
     * @return Device|null parent device.
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }


    /**
     * @return $this.
     * if picImage is enabled
     * saves it to public folder and write filename to record
     */
    public function savePicImage()
    {   
        if ($this->validate()) {
            // generate random name
            $picName = (new Security)->generateRandomString() . '.' . $this->picImage->extension;
            $this->picImage->saveAs('uploads/' . $picName);

            // write name to record
            $this->pic = $picName;
        }


        return $this;
    }


    /**
     * @return $this.
     * depends of form data
     * controls pic field
     */
    public function loadOrRemovePicImage()
    {
        if ($this->removePicImage) {
            $this->pic = null;
            return $this;
        }
        
        $this->picImage = UploadedFile::getInstance($this, 'picImage');
        return $this;
    }


     /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }


        if ($this->picImage) {
            // save to public folder
            $this->savePicImage();
        }

        return true;
    }
}