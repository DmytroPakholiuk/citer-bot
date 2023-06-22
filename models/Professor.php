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
            [['first_name', 'last_name', 'father_name'], 'string'],
            [['first_name', 'last_name', 'father_name'], 'required']
        ];
    }

    public static function findProfessor(string $fullname)
    {
        $nameArray = explode(' ', $fullname);
        $last_name = $nameArray[0];
        $first_name = $nameArray[1] ?? '';
        $father_name = $nameArray[2] ?? '';

        $professors = Professor::find()
            ->where(['like', 'last_name', $last_name])
            ->andWhere(['like', 'first_name', $first_name])
            ->andWhere(['like', 'father_name', $father_name])->all();

        return $professors;

    }

    public function getFullName()
    {
        return $this->last_name . " " . $this->first_name . " " . $this->father_name;
    }

    public function getInitials()
    {

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