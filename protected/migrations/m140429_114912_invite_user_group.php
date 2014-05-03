<?php
class m140429_114912_invite_user_group extends CDbMigration
{
	public function up()
	{
             $this->createTable('invite_user_group', array(
                'id' => 'pk',
                'email' => 'string',
                'group_id' => 'integer',
                'key' => 'string',
            ));
	}

	public function down()
	{
            $this->dropTable('invite_user_group');
            echo "m140429_114912_invite_user_group does not support migration down.\n";
            return false;
	}
}