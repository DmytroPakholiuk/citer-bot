<?php

namespace app\commands;

use aki\telegram\Telegram;
use app\models\Customer;
use app\models\Message;

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
        echo "running...\n";
        /**
         * @var Telegram $telegram
         */
        $telegram = \Yii::$app->get('telegram');
        $updates = $telegram->getUpdates();

        foreach ($updates['result'] as $update) {
            $from = $update['message']['from'];
            $customer = Customer::findOne(['telegram_user_id' => $from['id']]);

            if ($customer == null){
                $customer = new Customer();
                $customer->telegram_user_id = $from['id'];
                $customer->status = 0;
            }
            $customer->username = $from['username'] ?? null;
            $customer->save();


            if (isset($update['message']['text'])){
                $text = $update['message']['text'];
                if (strpos($text, '/') === 0){
                    switch ($text){
                        case '/cite':
                            break;
                        default:
                            $customer->status = Customer::STATUS_IDLE;
                            $customer->save();
                            $telegram->sendMessage([
                                'text' => 'Incorrect command.',
                                'chat_id' => $customer->telegram_user_id
                            ]);
                    }
                }
            }



        }
    }
}