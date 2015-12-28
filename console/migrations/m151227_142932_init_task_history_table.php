<?php

use yii\db\Schema;
use yii\db\Migration;

class m151227_142932_init_task_history_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%task_history}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'task_id' => $this->integer()->notNull(),
            'last_executor_id' => $this->integer()->notNull(),
            'new_executor_id' => $this->integer()->notNull(),
            'comment' => $this->text()->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%task_history}}', 'user_id');
        $this->addForeignKey('user_ibfk_4', '{{%task_history}}', 'user_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');

        $this->createIndex('last_executor_id', '{{%task_history}}', 'last_executor_id');
        $this->addForeignKey('last_executor_ibfk_1', '{{%task_history}}', 'last_executor_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');

        $this->createIndex('new_executor_id', '{{%task_history}}', 'new_executor_id');
        $this->addForeignKey('new_executor_ibfk_1', '{{%task_history}}', 'new_executor_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');

        $this->createIndex('task_id', '{{%task_history}}', 'task_id');
        $this->addForeignKey('task_ibfk_1', '{{%task_history}}', 'task_id', '{{%task}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%task_history}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
