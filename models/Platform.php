<?php

namespace app\models;

use yii\db\ActiveRecord;

use app\models\Device;
use app\models\Person;

class Platform extends ActiveRecord
{
    /**
     * @return string table name.
     */
    public static function tableName()
    {
        return '{{%platforms}}';
    }


    /**
     * @return Device[] list of devices with platform.
     */
    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['platform_id' => 'id'])
                    ->orderBy('sort');
    }


    /**
     * @return Person[] list of persons with platform.
     */
    public function getPersons()
    {
        return $this->hasMany(Person::className(), ['platform_id' => 'id'])
                    ->orderBy('created_at DESC');
    }
}