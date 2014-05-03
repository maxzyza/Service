<?php
class InviteUserGroup extends CActiveRecord
{
	public function tableName()
	{
		return 'invite_user_group';
	}
	public function rules()
	{
		return array(
			array('group_id', 'numerical', 'integerOnly'=>true),
			array('email, key', 'length', 'max'=>255),
			array('id, email, group_id, key', 'safe', 'on'=>'search'),
		);
	}
	public function relations()
	{
		return array(
		);
	}
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'email' => 'Email',
			'group_id' => 'Group',
			'key' => 'Key',
		);
	}
	public function search()
	{
		$criteria=new CDbCriteria;
		$criteria->compare('id',$this->id);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('group_id',$this->group_id);
		$criteria->compare('key',$this->key,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
