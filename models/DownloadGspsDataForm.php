<?php
/**
 * Created by PhpStorm.
 * User: ufo
 * Date: 05.08.18
 * Time: 22:56
 */

namespace app\models;

use yii\base\Model;

/**
 * DownloadGspsDataForm is the model behind the change password form
 */
class DownloadGspsDataForm extends Model
{
    public $fileName;
    public $format;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fileName', 'format'], 'required'],
            [['fileName'], 'string', 'max' => '30'],
            ['format', 'validateFormat'],
        ];
    }

    /**
     * Validates 'format' attribute for correct data
     * @param string $attribute attribute for check data
     * @param array $params additional pairs key => value
     */
    public function validateFormat($attribute, $params)
    {
        $val = mb_strtolower($this->$attribute);
        if ($val != 'gpx' && $val != 'kml') {
            $this->addError($attribute, 'Format must be GPX or KML');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fileName' => 'File name',
            'format' => 'Choose file format',
        ];
    }


}