<?php
/**
 * Created by PhpStorm.
 * User: ufo
 * Date: 29.06.18
 * Time: 22:22
 */

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

class GpsDataSearch extends GpsData
{
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'title' => 'Title',
            'updated_at' => 'Updated',
            'created_at' => 'Created',
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
            [['title'], 'safe'],
            [['created_at', 'updated_at'], 'date', 'format' => 'dd.MM.yyyy', 'message' => '{attribute} must be DD.MM.YYYY format.'],
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find()->select(['token', 'title', 'created_at', 'updated_at'])->where(['user_id' => Yii::$app->user->id]);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', "(date_format( FROM_UNIXTIME(`created_at` ), '%d.%m.%Y %h:%i:%s %p' ))", $this->created_at])
            ->andFilterWhere(['like', "(date_format( FROM_UNIXTIME(`updated_at` ), '%d.%m.%Y %h:%i:%s %p' ))", $this->updated_at]);

        return $dataProvider;
    }


}