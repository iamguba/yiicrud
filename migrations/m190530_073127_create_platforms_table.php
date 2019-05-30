<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%platforms}}`.
 */
class m190530_073127_create_platforms_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%platforms}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
        ]);

        // base seeding
        $this->batchInsert('{{%platforms}}', ['title'], [
            ['iOS'],
            [ 'Android'],
          ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%platforms}}');
    }
}
