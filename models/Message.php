<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $telegram_message_id
 * @property int $customer_id
 * @property int $customer_status
 * @property int $telegram_date_sent
 * @property string $type
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 * @property-read Customer $customer
 *
 */
class Message extends ActiveRecord
{
    public static function tableName()
    {
        return 'messages';
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                      ],
                  ],
        ];
    }

    public function rules()
    {
        return [
            [['telegram_message_id', 'customer_id', 'customer_status', 'telegram_date_sent'], 'integer'],
            [['type', 'text'], 'string'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }
}