<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%devices}}`.
 */
class m190530_074412_create_devices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%devices}}', [
            'id' => $this->primaryKey(),
            'platform_id' => $this->integer(),
            'title' => $this->string(),
            'sort' => $this->integer(),
        ]);


        $this->addForeignKey(
            $name = 'fk-devices-platform_id',
            $table = '{{%devices}}',
            $column = 'platform_id',
            $refTable = '{{%platforms}}',
            $refColumn = 'id',
            $onDelete = 'SET NULL',
            $onUpdate = 'SET NULL'
        );


        $query = new yii\db\Query;

        $platforms = $query->select('*')
                        ->from('{{%platforms}}')
                        ->all();

        
        $iosId = null;
        $androidId = null;

        foreach ($platforms as $platform) {
            if ($platform['title'] == 'iOS') $iosId = $platform['id'];
            if ($platform['title'] == 'Android') $androidId = $platform['id'];
        }


        $this->batchInsert('{{%devices}}', ['platform_id', 'title', 'sort'], [
            [$iosId, 'iPhone SE', 1],
            [$iosId, 'iPhone 7', 2],
            [$iosId, 'iPhone 8', 3],
            [$iosId, 'iPhone X', 4],
            [$iosId, 'iPhone XS', 5],

            [$androidId, 'Google Pixel 2 XL', 1],
            [$androidId, 'Huawei P30', 2],
            [$androidId, 'OnePlus 7', 3],
            [$androidId, 'Samsung Galaxy S9', 4],
            [$androidId, 'Meizu 16', 5],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            $name = 'fk-devices-platform_id',
            $table = '{{%devices}}'
        );
        
        $this->dropTable('{{%devices}}');
    }
}
