<?php

use yii\db\Migration;

class m170831_072258_insert_roles extends Migration
{
    public function safeUp()
    {
        $this->batchInsert('{{%auth_item}}', ['type', 'name', 'description'], [
            [1, 'admin', 'Administrator'],
            [1, 'user', 'User'],
        ]);

        $this->batchInsert('{{%auth_item_child}}', ['parent', 'child'], [
            ['admin', 'user'],
        ]);
    }

    public function safeDown()
    {
        $this->delete('{{%auth_item_child}}');
        $this->delete('{{%auth_item}}');
    }
}
