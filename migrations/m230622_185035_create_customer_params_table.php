<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer_params}}`.
 */
class m230622_185035_create_customer_params_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer_params}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(),
            'key' => $this->string(),
            'value' => $this->string()
        ]);
        $this->addForeignKey(
            'param_customer',
            'customer_params',
            'customer_id',
            'customers',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('param_customer', 'customer_params');
        $this->dropTable('{{%customer_params}}');
    }
}
