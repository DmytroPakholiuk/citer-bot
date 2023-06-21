<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%professor_image}}`.
 */
class m230621_172713_create_professor_image_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%professor_image}}', [
            'id' => $this->primaryKey(),
            'path' => $this->string(),
            'professor_id' => $this->integer(),
            'size' => $this->integer()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%professor_image}}');
    }
}
