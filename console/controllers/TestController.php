<?php

namespace console\controllers;

use common\models\Account;
use common\models\Payment;
use common\models\User;
use Faker\Factory;
use yii\console\Controller;
use yii\helpers\Console;

class TestController extends Controller
{
    public function actionGenerate($limit = 1000, $perOne = 1000): void
    {
        $faker = Factory::create();

        $this->stdout('Clear' . PHP_EOL, Console::FG_GREY);
        
        Payment::deleteAll();
        Account::deleteAll(['id' => 1]);
        User::deleteAll(['>', 'id', 1]);

        $this->stdout('Generate' . PHP_EOL, Console::FG_GREY);
        
        $users = [];
        $aasignments = [];
        $accounts = [
            1 => [
                'id' => 1,
                'amount' => 0,
            ]
        ];
        $payments = [];

        for ($i = 2; $i <= $limit; $i++) {
            $users[$i] = [
                'id' => $i,
                'email' => $faker->email,
                'password_hash' => $faker->sha1,
                'auth_key' => \Yii::$app->getSecurity()->generateRandomString(),
                'status' => 10,
                'created_at' => time(),
                'updated_at' => time(),
            ];

            $amount = random_int(100, 10000);
            
            $payments[] = [
                'created_at' => time(),
                'user_id' => 1,
                'from_account_id' => null,
                'to_account_id' => $i,
                'amount' => $amount,
            ];

            $accounts[$i] = [
                'id' => $i,
                'amount' => $amount,
            ];
        }
        
        for ($i = 1; $i <= $limit; $i++) {
            $count = random_int(1, $perOne * 2);
            for ($j = 1; $j <= $count; $j++) {

                $fromId = ($j % 10 == 1) ? 1 : array_rand($users);
                $toId = ($j % 10 == 5) ? 1 : array_rand($users);

                if ($fromId == $toId) {
                    continue;
                }

                $amount = random_int(50, 150);

                if ($accounts[$fromId]['amount'] < $amount) {
                    continue;
                }

                $accounts[$fromId]['amount'] -= $amount;
                $accounts[$toId]['amount'] += $amount;

                $payments[] = [
                    'created_at' => time(),
                    'user_id' => random_int(0, 10) <= 2 ? 1 : $fromId,
                    'from_account_id' => $fromId,
                    'to_account_id' => $toId,
                    'amount' => $amount,
                ];
            }            
        }

        $this->stdout('Insert' . PHP_EOL, Console::FG_GREY);

        foreach (array_chunk($users, 10) as $chunk) {
            \Yii::$app->db->createCommand()
                ->batchInsert(User::tableName(), array_keys(reset($chunk)), $chunk)
                ->execute();
        }

        foreach (array_chunk($accounts, 10) as $chunk) {
            \Yii::$app->db->createCommand()
                ->batchInsert(Account::tableName(), array_keys(reset($chunk)), $chunk)
                ->execute();
        }

        foreach (array_chunk($payments, 10) as $chunk) {
            \Yii::$app->db->createCommand()
                ->batchInsert(Payment::tableName(), array_keys(reset($chunk)), $chunk)
                ->execute();
        }

        $firstUser = reset($users);

        \Yii::$app->db->createCommand()
            ->insert('{{%auth_assignment}}', [
                'item_name' => 'admin',
                'user_id' => $firstUser['id'],
                'created_at' => time(),
            ])
            ->execute();

        $this->stdout('Done!' . PHP_EOL, Console::FG_GREEN);
    }
}
