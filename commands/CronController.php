<?php
namespace app\commands;

use app\models\GpsData;
use yii\console\Controller;
use yii\console\ExitCode;

class CronController extends Controller
{
    /**
     * Removes old gps data for guest
     * @return int
     */
    public function actionRemoveOldData()
    {
        GpsData::deleteAll('from_unixtime(created_at) < now() - interval 1 week and user_id = 0');
        return ExitCode::OK;
    }
}
