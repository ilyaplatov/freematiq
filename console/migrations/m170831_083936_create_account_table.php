<?php

use yii\db\Migration;

class m170831_083936_create_account_table extends Migration
{
    public function up()
    {
        $this->createTable('{{%account}}', [
            'id' => $this->integer()->notNull(),
            'amount' => $this->decimal(9, 2)->notNull(),
            'version' => $this->bigInteger()->notNull()->defaultValue(0),
        ]);

        $this->addPrimaryKey('{{%pk-account}}', '{{%account}}', 'id');
        $this->addForeignKey('{{%fk-account-id}}', '{{%account}}', 'id', '{{%user}}', 'id', 'CASCADE');

        $this->execute('INSERT INTO {{%account}} (id, amount, version) SELECT u.id, 0, 0 FROM {{%user}} u');
    }

    public function down()
    {
        $this->dropTable('{{%account}}');
    }
}
