<?php

class m140219_112841_users extends CDbMigration
{
	public function up()
	{
            $this->createTable('users', array(
                'id' => 'pk',
                'email' => 'string',
                'password' => 'string',
                'name' => 'string',
                'surname' => 'string',
                'salt' => 'string',
                'activation_string' => 'string',
                'banned' => 'boolean',
                'active' => 'boolean',
            ));
            Yii::app()->db->createCommand()->insert('users',array(
                'email' => 'admin@admin.ru',
                'password' => '$2a$10$7fac738888804824c6d91eRp3aMLg#hsXnr6MeYIgQFE6UPSlwTcK',//12345678
                'salt' => '7fac738888804824c6d91e',
                'activation_string' => '',//'$2a$10$7fac738888804824c6d91eB7Gf5wd5cU5HJnTsUOPnSgP0R7J3AeW',
                'active' => '1',
            ));
	}

	public function down()
	{
            $this->dropTable('user');
            echo "m140219_112841_create_user_table does not support migration down.\n";
            return false;
	}
}