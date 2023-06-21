<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%messages}}`.
 */
class m230621_202052_create_messages_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%messages}}', [
            'id' => $this->primaryKey(),
            'telegram_message_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'customer_status' => $this->integer(),
            'type' => $this->string(),
            'text' => $this->string(),
            'telegram_date_sent' => $this->integer(),
            'created_at' => $this->string(),
            'updated_at' => $this->string()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%messages}}');
    }
}
