<?php

use yii\db\Schema;
use yii\db\Migration;

class m151227_142909_init_project_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%project}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'executor_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'text' => $this->text()->notNull(),
            'date_create' => $this->dateTime(),
            'date_end' => $this->dateTime(),
            'status' => $this->smallInteger()->notNull(),

            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createIndex('executor_id', '{{%project}}', 'executor_id');
        $this->addForeignKey('executor_ibfk_2', '{{%project}}', 'executor_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');

        $this->createIndex('user_id', '{{%project}}', 'user_id');
        $this->addForeignKey('user_ibfk_2', '{{%project}}', 'user_id', '{{%user}}', 'id', 'NO ACTION', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('{{%project}}');
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
