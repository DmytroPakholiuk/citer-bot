<?php

namespace app\models;

use yii\log\SyslogTarget;

class Professor extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'professor';
    }
}