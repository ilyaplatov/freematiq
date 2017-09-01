<?php

use yii\db\Migration;

class m170831_084804_create_payment_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'from_account_id' => $this->integer(),
            'to_account_id' => $this->integer()->notNull(),
            'amount' => $this->decimal(9, 2)->notNull(),
        ]);

        $this->addForeignKey('{{%fk-payment-user}}', '{{%payment}}', 'user_id', '{{%user}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-payment-from_account}}', '{{%payment}}', 'from_account_id', '{{%account}}', 'id', 'CASCADE');
        $this->addForeignKey('{{%fk-payment-to_account}}', '{{%payment}}', 'to_account_id', '{{%account}}', 'id', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%payment}}');
    }
}
