<?php

class m140312_195306_auth extends CDbMigration
{
	public function up()
	{
            /** @var CDbAuthManager $auth */
            $auth = Yii::app()->authManager;
            $auth->createRole('admin', 'Администратор');
            $auth->createRole('guest', 'Гость');
            $auth->assign('admin','user');
	}

	public function down()
	{
            /** @var CDbAuthManager $auth */
            $auth = Yii::app()->authManager;
            $auth->removeAuthItem('admin');
            $auth->removeAuthItem('guest');

		echo "m140312_195306_auth does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}