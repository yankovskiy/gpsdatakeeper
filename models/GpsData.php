<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 * This is the model class for table "gpsdata".
 *
 * @property string $token
 * @property int $user_id
 * @property string $title
 * @property string $data
 * @property int $zoom
 * @property string $center
 * @property int created_at
 * @property int updated_at
 *
 * @property User $user
 */
class GpsData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gpsdata';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['token', 'user_id', 'title', 'data', 'zoom', 'center'], 'required'],
            [['user_id'], 'integer'],
            [['zoom'], 'integer'],
            [['data'], 'string'],
            [['token'], 'string', 'max' => 32],
            [['title'], 'string', 'max' => 64],
            [['center'], 'string', 'max' => 64],
            [['token'], 'unique'],
            ['data', 'validateData'],
            ['center', 'validateCenter'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * Validates 'center' attribute for correct data
     * @param string $attribute attribute for check data
     * @param array $params additional pairs key => value
     */
    public function validateCenter($attribute, $params)
    {
        $data = Json::decode($this->$attribute);
        $hasError = false;
        if (!is_array($data) || count($data) != 2) {
            $hasError = true;
        } else if (!is_float($data[0]) || !is_float($data[1])){
            $hasError = true;
        }

        if ($hasError) {
            $this->addError($attribute, 'Incorrect map center');
            Yii::error('Incorrect map center : ' . $this->$attribute);
        }
    }

    /**
     * Validates 'data' attribute for correct data
     * @param string $attribute attribute for check data
     * @param array $params additional pairs key => value
     */
    public function validateData($attribute, $params)
    {
        $data = Json::decode($this->$attribute);
        $hasError = false;
        /*
         * {"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"LineString","coordinates":[["131.934299","43.100231"],["131.91576","43.106874"],["131.902885","43.102612"]]}},{"type":"Feature","geometry":{"type":"LineString","coordinates":[["131.923485","43.112388"],["131.896706","43.118528"],["131.892414","43.125795"],["131.915417","43.123665"],["131.899452","43.109881"]]}},{"type":"Feature","geometry":{"type":"Point","coordinates":["131.930351","43.112638"]}},{"type":"Feature","geometry":{"type":"Point","coordinates":["131.876793","43.108879"]}},{"type":"Feature","geometry":{"type":"LineString","coordinates":[["131.924686","43.089451"],["131.91576","43.100356"],["131.881428","43.097975"],["131.885548","43.107124"]]}},{"type":"Feature","geometry":{"type":"Point","coordinates":["131.901855","43.118528"]}}]}
         */
        if (!isset($data['type']) || $data['type'] != 'FeatureCollection') {
            $hasError = true;
        } else if (!isset($data['features']) || !is_array($data['features'])) {
            $hasError = true;
        } else if (count($data['features']) == 0) {
            $hasError = true;
        } else {
            foreach ($data['features'] as $feature) {
                if (!isset($feature['type']) || $feature['type'] != 'Feature') {
                    $hasError = true;
                } else if (!isset($feature['geometry']) || !is_array($feature['geometry'])) {
                    $hasError = true;
                } else if (count($feature['geometry']) != 2) {
                    $hasError = true;
                } else {
                    $geometry = $feature['geometry'];
                    if (!isset($geometry['type'])) {
                        $hasError = true;
                    } else if ($geometry['type'] == 'Point') {
                        if (!isset($geometry['coordinates'])
                            || !is_array($geometry['coordinates'])
                            || count($geometry['coordinates']) != 2) {
                            $hasError = true;
                        } else if (!is_float($geometry['coordinates'][0])
                            || !is_float($geometry['coordinates'][1])) {
                            $hasError = true;
                        }
                    } else if ($geometry['type'] == 'LineString') {
                        if (!isset($geometry['coordinates'])
                            || !is_array($geometry['coordinates'])
                            || count($geometry['coordinates']) < 2) {
                            $hasError = true;
                        } else {
                            foreach ($geometry['coordinates'] as $coordinates) {
                                if (!is_array($coordinates) || count($coordinates) != 2) {
                                    $hasError = true;
                                    break;
                                } else if (!is_float($coordinates[0])
                                    || !is_float($coordinates[1])) {
                                    $hasError = true;
                                    break;
                                }
                            }
                        }
                    } else {
                        $hasError = true;
                    }
                }

                if ($hasError) {
                    break;
                }
            }
        }

        if ($hasError) {
            $this->addError($attribute, 'Incorrect GPS-data');
            Yii::error('Incorrect GPS-data');
            Yii::info(print_r($data, true));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'token' => 'Token',
            'user_id' => 'User ID',
            'title' => 'Title',
            'data' => 'Data',
        ];
    }

    /**
     * Gets gpsdata owner
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * Saves new GPS-data into database
     * Before save data method validate input data.
     * @param string $data gps-data for save
     * @param string $title the title for gps-data
     * @param int $zoom map zoom
     * @param string $center map center coordinates
     * @return boolean true for successfully saved data
     */
    public function saveData($data, $title, $zoom, $center)
    {
        if ($this->isNewRecord) {
            $this->token = Yii::$app->security->generateRandomString();
            $this->user_id = Yii::$app->user->isGuest ? 0: Yii::$app->user->id;
        }

        $this->title = Html::encode($title);
        $this->data = $data;
        $this->zoom = $zoom;
        $this->center = $center;

        return $this->save();
    }


    /**
     * Gets GPS-data by token.
     *
     * @param string $token token for search in database
     * @param boolean $isOwner true if search data only for currently login user
     * @return GpsData|null|array
     */
    public static function getDataByToken($token, $isOwner = false)
    {
        $data = self::find()->where(['token' => $token]);
        if ($isOwner) {
            $data->andWhere(['user_id' => Yii::$app->user->id]);
        }

        return $data->one();
    }
}
