<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%persons}}`.
 */
class m190530_080432_create_persons_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%persons}}', [
            'id' => $this->primaryKey(),
            'platform_id' => $this->integer(),
            'device_id' => $this->integer(),
            'name' => $this->string(),
            'email' => $this->string()->unique(),
            'pic' => $this->string(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);


        $this->addForeignKey(
            $name = 'fk-persons-platform_id',
            $table = '{{%persons}}',
            $column = 'platform_id',
            $refTable = '{{%platforms}}',
            $refColumn = 'id',
            $onDelete = 'SET NULL',
            $onUpdate = 'SET NULL'
        );

        $this->addForeignKey(
            $name = 'fk-persons-device_id',
            $table = '{{%persons}}',
            $column = 'device_id',
            $refTable = '{{%devices}}',
            $refColumn = 'id',
            $onDelete = 'SET NULL',
            $onUpdate = 'SET NULL'
        );



        $query = new yii\db\Query;

        $devices = $query->select('*')
                        ->from('{{%devices}}')
                        ->all();

        $insertData = [
            [null, null, 'Jack', 'jack@gmail.com'],
            [null, null, 'Bob', 'bob@gmail.com'],
            [null, null, 'Helena', 'helena@gmail.com'],
            [null, null, 'Artur', 'artur@gmail.com'],
        ];

        $insertData = array_map(function($row) use ($devices) {
            $key = array_rand($devices);
            $device = $devices[$key];

            $row[0] = $device['platform_id'];
            $row[1] = $device['id'];

            return $row;
        }, $insertData);
        

        $this->batchInsert('{{%persons}}', 
                    ['platform_id', 'device_id', 'name', 'email'], 
                    $insertData);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            $name = 'fk-persons-platform_id',
            $table = '{{%persons}}'
        );

        $this->dropForeignKey(
            $name = 'fk-persons-device_id',
            $table = '{{%persons}}'
        );
        
        $this->dropTable('{{%persons}}');
    }
}
