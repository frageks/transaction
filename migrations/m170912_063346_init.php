<?php

use yii\db\Migration;
use app\models\User;

class m170912_063346_init extends Migration
{
	public function safeUp ()
	{
		$tableOptions = null;
		if ($this->db->driverName === 'mysql') {
			// http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
			$tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
		}

		$this->createTable('{{%user}}', [
			'id'       => $this->primaryKey(),
			'username' => $this->string()->notNull()->unique(),
			'auth_key' => $this->string(32)->notNull(),
			'balance'  => $this->float(2)->defaultValue(0.00),
		], $tableOptions);

		$this->createIndex("username_k", User::tableName(), "username", true);
	}

	public function safeDown ()
	{
		$this->dropTable('{{%user}}');
	}
}
