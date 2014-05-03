<?php

class m140328_115555_relation_group_user extends CDbMigration
{
	public function up()
	{
            $this->createTable('relation_group_user', array(
                'id' => 'pk',
                'group_id' => 'integer',
                'user_id' => 'integer',
                'active' => 'boolean',
                'type' => 'string',
            ));
	}

	public function down()
	{
            $this->dropTable('relation_group_user');
            echo "m140328_115555_relation_group_user does not support migration down.\n";
            return false;
	}
}