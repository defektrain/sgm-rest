<?php

use yii\db\Schema;
use yii\db\Migration;

class m151227_142920_init_task_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'project_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull(),
            'date_end' => $this->dateTime(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('user_id', '{{%task}}', 'user_id');
        $this->addForeignKey('user_ibfk_3', '{{%task}}', 'user_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');

        $this->createIndex('executor_id', '{{%task}}', 'executor_id');
        $this->addForeignKey('executor_ibfk_1', '{{%task}}', 'executor_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');

        $this->createIndex('project_id', '{{%task}}', 'project_id');
        $this->addForeignKey('project_ibfk_1', '{{%task}}', 'project_id', '{{%project}}', 'id', 'NO ACTION', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%task}}');
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
