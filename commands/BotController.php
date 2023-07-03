<?php

namespace app\commands;

use aki\telegram\Telegram;
use app\models\CommandParser;
use app\models\Customer;
use app\models\Message;
use Telegram\Bot\Api;

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
        $commandParser = new CommandParser();

//        var_dump($updates); die();

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

            $message = Message::find()->where(['telegram_message_id' => $update['message']['message_id']])
                ->andWhere(['customer_id' => $customer->id])->one();
            if (is_null($message)){
                $message = new Message();
                $message->customer_id = $customer->id;
                $message->customer_status = $customer->status;
                $message->type = isset($update['message']['text']) ? 'text' : 'other';
                $message->text = $update['message']['text'] ?? null;
                $message->telegram_message_id = $update['message']['message_id'];
                $message->telegram_date_sent = $update['message']['date'];
                $message->save();


                if (isset($update['message']['text'])){
                    $text = $update['message']['text'];
                    if (strpos($text, '/') === 0){
                        $commandParser->parseCommand($text, $customer, $telegram);
                        continue;
                    }
                    $commandParser->parseTextContent($text, $customer, $telegram);
                }
            }
        }
    }
}