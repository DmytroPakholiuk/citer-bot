<?php

namespace app\models;

/**
 * @property integer $id
 * @property integer $customer_id
 * @property string $key
 * @property string $value
 */
class CustomerParam extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'customer_params';
    }

    public function rules()
    {
        return [
            [['key', 'value'], 'string'],
            [['customer_id'], 'integer'],
            [['customer_id', 'key'], 'required']
        ];
    }

    public function getCustomer()
    {
        return $this->hasOne(Customer::class, ['id' => 'customer_id']);
    }
}