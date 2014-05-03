<?php

class m140323_162605_groups extends CDbMigration
{
	public function up()
	{
            $this->createTable('groups', array(
                'id' => 'pk',
                'name' => 'string',
            ));
	}

	public function down()
	{
            $this->dropTable('groups');
            echo "m140219_112841_create_user_table does not support migration down.\n";
            return false;
	}
}