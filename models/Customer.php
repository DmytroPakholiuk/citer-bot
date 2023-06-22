<?php

namespace app\models;

/**
 * @property int $id
 * @property int $telegram_user_id
 * @property string $username
 * @property int $status
 */
class Customer extends \yii\db\ActiveRecord
{
    public const STATUS_IDLE = 0;
    public const STATUS_CHOOSING_PROF = 1;
    public const STATUS_CONFIRMING_PROF = 2;
    public const STATUS_WRITING_CONTENT = 3;
    public const STATUS_DELETED = 10;


    public static function tableName()
    {
        return 'customers';
    }

    public function rules()
    {
        return [
            [['telegram_user_id', 'status'], 'integer'],
            [['username'], 'string'],
            [['telegram_user_id'], 'required']
        ];
    }
}