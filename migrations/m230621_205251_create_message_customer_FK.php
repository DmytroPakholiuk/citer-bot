<?php

use yii\db\Migration;

/**
 * Class m230621_205251_create_message_customer_FK
 */
class m230621_205251_create_message_customer_FK extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'message-customer',
            'message',
            'customer_id',
            'customer',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('message-customer', 'message');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230621_205251_create_message_customer_FK cannot be reverted.\n";

        return false;
    }
    */
}
