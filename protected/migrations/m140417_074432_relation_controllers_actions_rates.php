<?php

class m140417_074432_relation_controllers_actions_rates extends CDbMigration
{
	public function up()
	{
             $this->createTable('relation_controllers_actions_rates', array(
                'id' => 'pk',
                'controller_action_id' => 'integer',
                'rate_id' => 'integer',
            ));
	}

	public function down()
	{
                $this->dropTable('relation_controllers_actions_rates');
		echo "m140417_074432_relation_controllers_actions_rates does not support migration down.\n";
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