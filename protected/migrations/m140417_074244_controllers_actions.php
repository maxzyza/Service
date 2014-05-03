<?php

class m140417_074244_controllers_actions extends CDbMigration
{
	public function up()
	{
             $this->createTable('controllers_actions', array(
                'id' => 'pk',
                'controller' => 'string',
                'action' => 'string',
            ));
	}

	public function down()
	{
                $this->dropTable('controllers_actions');
		echo "m140417_074244_controllers_actions does not support migration down.\n";
		return false;
	}
}