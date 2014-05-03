<?php
class RelationControllersActionsRates extends CActiveRecord
{
	public function tableName()
	{
		return 'relation_controllers_actions_rates';
	}

	public function rules()
	{
		return array(
			array('controller_action_id, rate_id', 'numerical', 'integerOnly'=>true),
			array('id, controller_action_id, rate_id', 'safe', 'on'=>'search'),
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
			'controller_action_id' => 'Controller Action',
			'rate_id' => 'Тариф',
		);
	}

	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('controller_action_id',$this->controller_action_id);
		$criteria->compare('rate_id',$this->rate_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
