<?php

class m140417_073024_orders extends CDbMigration
{
	public function up()
	{
            Yii::app()->db->createCommand("CREATE TABLE IF NOT EXISTS `orders` (
                `id` int(11) NOT NULL,
                `number` int(11) NOT NULL,
                `amount` decimal(10,0) NOT NULL,
                `status` enum('not_paid','paid','pending','denied') NOT NULL,
                `user_id` int(11) NOT NULL,
                `rate_id` int(11) NOT NULL,
                `group_id` int(11) NOT NULL,
                `date_create` datetime NOT NULL,
                `date_pay` datetime NOT NULL,
                `description` text NOT NULL,
                `system_record` text NOT NULL
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8;")->query();
	}

	public function down()
	{
                $this->dropTable('orders');
		echo "m140417_073024_orders does not support migration down.\n";
		return false;
	}
}