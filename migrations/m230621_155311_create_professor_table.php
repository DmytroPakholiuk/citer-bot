<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%professor}}`.
 */
class m230621_155311_create_professor_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%professor}}', [
            'id' => $this->primaryKey(),
            'first_name' => $this->string(),
            'last_name' => $this->string(),
            'father_name' => $this->string(),
            'main_image_id' => $this->integer(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%professor}}');
    }
}
