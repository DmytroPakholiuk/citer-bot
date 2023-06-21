<?php

namespace app\commands;

class BotController extends \yii\console\Controller
{


    public function actionGetUpdates()
    {
        echo "running...\n";
        $telegram = \Yii::$app->get('telegram');
        var_dump($telegram->getUpdates());
    }

    public function actionManualRun()
    {

    }
}