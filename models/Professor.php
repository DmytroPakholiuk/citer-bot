<?php

namespace app\models;

use yii\log\SyslogTarget;

/**
 * @property integer $id
 * @property string $first_name
 * @property string $last_name
 * @property string $father_name
 * @property integer $main_image_id
 * @property-read ProfessorImage $mainImage
 * @property-read ProfessorImage[] $professorImages
 */
class Professor extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return 'professor';
    }

    public function rules()
    {
        return [
            [['main_image_id'], 'integer'],
            [['first_name', 'last_name', 'father_name'], 'string']
        ];
    }

    public function getMainImage()
    {
        return $this->hasOne(ProfessorImage::class, ['id' => 'main_image_id']);
    }

    public function getProfessorImages()
    {
        return $this->hasMany(ProfessorImage::class, ['professor_id' => 'id']);
    }
}