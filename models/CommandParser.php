<?php

namespace app\models;

use aki\telegram\Telegram;
use yii\base\Model;

class CommandParser extends Model
{
    public function parseCommand(string $command, Customer $customer, Telegram $telegram)
    {
        switch ($command){
            case '/cite':
                $customer->status = Customer::STATUS_CHOOSING_PROF;
                $customer->save(false);
                $telegram->sendMessage([
                    'text' => "Chose a professor. Write their full name in Ukrainian like \n\n last_name first_name father_name",
                    'chat_id' => $customer->telegram_user_id
                ]);

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

    public function parseTextContent(string $text, Customer $customer, Telegram $telegram)
    {
        switch ($customer->status)
        {
            case Customer::STATUS_IDLE:
                $telegram->sendMessage([
                    'text' => 'Please enter a command instead',
                    'chat_id' => $customer->telegram_user_id
                ]);
                break;
            case Customer::STATUS_CHOOSING_PROF:
                $professors = Professor::findProfessor($text);
                if (sizeof($professors) == 0){
                    $telegram->sendMessage([
                        'text' => "No professor found, try entering their name as: \n\n last_name first_name father_name",
                        'chat_id' => $customer->telegram_user_id
                    ]);
                }
                if (sizeof($professors) == 1){
                    $customer->status = Customer::STATUS_CONFIRMING_PROF;
                    $customer->save(false);

                    $chosenParam = CustomerParam::find()->where(['key' => 'chosen_professor'])
                        ->andWhere(['customer_id' => $customer->id])->one() ?? new CustomerParam();
                    $chosenParam->key = 'chosen_professor';
                    $chosenParam->value = $professors[0]->id;
                    $chosenParam->save();

                    $telegram->sendMessage([
                        'text' => "Found only prof {$professors[0]->getFullName()}. Choose it? (yes/no)",
                        'chat_id' => $customer->telegram_user_id
                    ]);
                }
                if (sizeof($professors) > 1){
                    $returnText = "Found professors: \n";
                    foreach ($professors as $professor){
                        $returnText .= "{$professor->getFullName()} \n";
                    }
                    $returnText .= "please enter more specified prof name";

                    $telegram->sendMessage([
                        'text' => $returnText,
                        'chat_id' => $customer->telegram_user_id
                    ]);
                }

                break;
            case Customer::STATUS_CONFIRMING_PROF:
                if (strtolower($text) == 'yes' || strtolower($text) == 'y'){
                    $customer->status = Customer::STATUS_WRITING_CONTENT;
                    $customer->save();

                    $telegram->sendMessage([
                        'text' => 'Please enter citation contents',
                        'chat_id' => $customer->telegram_user_id
                    ]);
                    break;
                }
                if (strtolower($text) == 'no' || strtolower($text) == 'n'){
                    $customer->status = Customer::STATUS_CHOOSING_PROF;
                    $customer->save(false);
                    $telegram->sendMessage([
                        'text' => "Chose a professor. Write their full name in Ukrainian like \n\n last_name first_name father_name",
                        'chat_id' => $customer->telegram_user_id
                    ]);
                    break;
                }
                $telegram->sendMessage([
                        'text' => "Just answer yes or no",
                        'chat_id' => $customer->telegram_user_id
                    ]);

                break;
            case Customer::STATUS_WRITING_CONTENT:


                break;
        }
    }
}