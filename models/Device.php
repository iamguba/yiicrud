<?php

namespace app\models;

use yii\db\ActiveRecord;

use app\models\Platform;
use app\models\Person;

class Device extends ActiveRecord
{

    /**
     * @return string table name.
     */
    public static function tableName()
    {
        return '{{%devices}}';
    }


    /**
     * @return Platform|null parent platform.
     */
    public function getPlatform()
    {
        return $this->hasOne(Platform::className(), ['id' => 'platform_id']);
    }


    /**
     * @return Person[] list of persons with device.
     */
    public function getPersons()
    {
        return $this->hasMany(Person::className(), ['device_id' => 'id'])
                    ->orderBy('created_at DESC');
    }
}