<?php

namespace app\models;

use yii\db\ActiveRecord;

use app\models\Device;
use app\models\Person;

class Platform extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%platforms}}';
    }


    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['platform_id' => 'id'])
                    ->orderBy('sort');
    }


    public function getPersons()
    {
        return $this->hasMany(Person::className(), ['platform_id' => 'id'])
                    ->orderBy('created_at DESC');
    }
}