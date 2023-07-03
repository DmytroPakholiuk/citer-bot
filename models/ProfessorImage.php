<?php

namespace app\models;

use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property int $size
 * @property string $path
 * @property int $professor_id
 * @property-read Professor $professor
 */
class ProfessorImage extends ActiveRecord
{
    public static function tableName()
    {
        return 'professor_image';
    }

    public static function baseImagePath()
    {
        return \Yii::$app->params['profImageDir'];
    }

    public function getFilePath(): string
    {
        return self::baseImagePath() . "/" . $this->path ;
    }

    public function rules()
    {
        return [
            [['size', 'professor_id'], 'integer'],
            [['path'], 'string']
        ];
    }

    public function getProfessor()
    {
        return $this->hasOne(Professor::class, ['id' => 'professor_id']);
    }
}