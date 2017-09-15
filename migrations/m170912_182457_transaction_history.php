<?php

use yii\db\Migration;

class m170912_182457_transaction_history extends Migration
{
	public $table_name = "{{%transaction_history}}";

	public function safeUp ()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}
		$this->createTable($this->table_name, [
			'id'       => $this->primaryKey(),
			'sender'   => $this->integer()->notNull(),
			'receiver' => $this->integer()->notNull(),
			'sum'      => $this->float(2)->notNull(),
			'when'     => $this->integer(),
		], $tableOptions);

		$this->createIndex("{{%sender-receiver-idx}}", $this->table_name, ['sender', 'receiver']);

		$this->addForeignKey("{{%history-sender-user-id-fk}}",
			$this->table_name, "sender",
			'{{%user}}', 'id',
			'CASCADE', 'CASCADE');

		$this->addForeignKey("{{%history-receiver-user-id-fk}}",
			$this->table_name, "receiver",
			'{{%user}}', 'id',
			'CASCADE', 'CASCADE');
	}

	public function safeDown ()
	{
		$this->dropForeignKey("{{%history-receiver-user-id-fk}}", $this->table_name);
		$this->dropForeignKey("{{%history-sender-user-id-fk}}", $this->table_name);
		$this->dropIndex("{{%sender-receiver-idx}}", $this->table_name);
		$this->dropTable($this->table_name);
	}
}
