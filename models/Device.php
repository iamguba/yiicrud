<?php

namespace app\models;

use yii\db\ActiveRecord;

use app\models\Platform;
use app\models\Person;

class Device extends ActiveRecord
{

    public static function tableName()
    {
        return '{{%devices}}';
    }


    public function getPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }


    public function getPersons()
    {
        return $this->hasMany(Person::className(), ['device_id' => 'id'])
                    ->orderBy('created_at DESC');
    }
}