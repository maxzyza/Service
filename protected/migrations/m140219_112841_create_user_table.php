<?php

class m140219_112841_create_user_table extends CDbMigration
{
	public function up()
	{
            $this->createTable('users', array(
                'id' => 'pk',
                'title' => 'string',
                'content' => 'text',
            ));
	}

	public function down()
	{
		echo "m140219_112841_create_user_table does not support migration down.\n";
		return false;
	}
}