<?php

use yii\db\Migration;

class m170831_070537_create_rbac_tables extends Migration
{
    public function up()
    {
        $itemTable = '{{%auth_item}}';
        $itemChildTable = '{{%auth_item_child}}';
        $assignmentTable = '{{%auth_assignment}}';
        $ruleTable = '{{%auth_rule}}';

        $this->createTable($ruleTable, [
            'name' => $this->string(64)->notNull(),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
        ]);

        $this->createTable($itemTable, [
            'name' => $this->string(64)->notNull(),
            'type' => $this->smallInteger()->notNull(),
            'description' => $this->text(),
            'rule_name' => $this->string(64),
            'data' => $this->binary(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'PRIMARY KEY ([[name]])',
            'FOREIGN KEY ([[rule_name]]) REFERENCES ' . $ruleTable . ' ([[name]])'.
            $this->buildFkClause('ON DELETE SET NULL', 'ON UPDATE CASCADE')
        ]);
        $this->createIndex('idx-auth_item-type', $itemTable, 'type');

        $this->createTable($itemChildTable, [
            'parent' => $this->string(64)->notNull(),
            'child' => $this->string(64)->notNull(),
            'PRIMARY KEY ([[parent]], [[child]])',
            'FOREIGN KEY ([[parent]]) REFERENCES ' . $itemTable . ' ([[name]])'.
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[child]]) REFERENCES ' . $itemTable . ' ([[name]])'.
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);

        $this->createTable($assignmentTable, [
            'item_name' => $this->string(64)->notNull(),
            'user_id' => $this->integer()->notNull(),
            'created_at' => $this->integer(),
            'PRIMARY KEY ([[item_name]], [[user_id]])',
            'FOREIGN KEY ([[item_name]]) REFERENCES ' . $itemTable . ' ([[name]])' .
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
            'FOREIGN KEY ([[user_id]]) REFERENCES {{%user}} ([[id]])' .
            $this->buildFkClause('ON DELETE CASCADE', 'ON UPDATE CASCADE'),
        ]);
    }

    public function down()
    {
        $itemTable = '{{%auth_item}}';
        $itemChildTable = '{{%auth_item_child}}';
        $assignmentTable = '{{%auth_assignment}}';
        $ruleTable = '{{%auth_rule}}';

        $this->dropTable($assignmentTable);
        $this->dropTable($itemChildTable);
        $this->dropTable($itemTable);
        $this->dropTable($ruleTable);
    }

    protected function buildFkClause($delete = '', $update = '')
    {
        return implode(' ', ['', $delete, $update]);
    }
}
