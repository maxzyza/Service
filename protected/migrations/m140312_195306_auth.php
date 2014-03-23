<?php

class m140312_195306_auth extends CDbMigration
{
	public function up()
	{
            Yii::app()->db->createCommand("
                drop table if exists `AuthAssignment`;
                drop table if exists `AuthItemChild`;
                drop table if exists `AuthItem`;")->query();
            Yii::app()->db->createCommand("
                create table `AuthItem`
                (
                   `name`                 varchar(64) not null,
                   `type`                 integer not null,
                   `description`          text,
                   `bizrule`              text,
                   `data`                 text,
                   primary key (`name`)
                ) engine InnoDB;")->query();
            Yii::app()->db->createCommand("
                create table `AuthItemChild`
                (
                   `parent`               varchar(64) not null,
                   `child`                varchar(64) not null,
                   primary key (`parent`,`child`),
                   foreign key (`parent`) references `AuthItem` (`name`) on delete cascade on update cascade,
                   foreign key (`child`) references `AuthItem` (`name`) on delete cascade on update cascade
                ) engine InnoDB;")->query();
            Yii::app()->db->createCommand("
                create table `AuthAssignment`
                (
                   `itemname`             varchar(64) not null,
                   `userid`               varchar(64) not null,
                   `bizrule`              text,
                   `data`                 text,
                   primary key (`itemname`,`userid`),
                   foreign key (`itemname`) references `AuthItem` (`name`) on delete cascade on update cascade
                ) engine InnoDB;")->query();
            $auth = Yii::app()->authManager;
            $auth->createRole('admin', 'Администратор');
            $auth->createRole('admin_group', 'Администратор группы');
            $auth->createRole('user_group', 'Пользователь группы');
            $auth->createRole('prepaid_rate', 'Оплаченный тариф');
            $auth->assign('admin','1');
	}

	public function down()
	{
            $this->dropTable('AuthAssignment');
            $this->dropTable('AuthItemChild');
            $this->dropTable('AuthItem');
            echo "m140312_195306_auth does not support migration down.\n";
            return false;
	}
}