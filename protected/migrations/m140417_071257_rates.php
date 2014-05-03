<?php

class m140417_071257_rates extends CDbMigration
{
	public function up()
	{
            $this->createTable('rates', array(
                'id' => 'pk',
                'name' => 'string',
                'price' => 'integer',
                'name_program' => 'string',
            ));
	}

	public function down()
	{
                $this->dropTable('rates');
		echo "m140417_071257_rates does not support migration down.\n";
		return false;
	}
}