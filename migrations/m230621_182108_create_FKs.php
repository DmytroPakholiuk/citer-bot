<?php

use yii\db\Migration;

/**
 * Class m230621_182108_create_FKs
 */
class m230621_182108_create_FKs extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addForeignKey(
            'professor-main_image',
            'professor',
            'main_image_id',
            'professor_image',
            'id'
        );
        $this->addForeignKey(
            'image-professor',
            'professor_image',
            'professor_id',
            'professor',
            'id'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('professor-main_image', 'professor');
        $this->dropForeignKey('image-professor', 'professor_image');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m230621_182108_create_FKs cannot be reverted.\n";

        return false;
    }
    */
}
