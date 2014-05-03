<?php
class Orders extends CActiveRecord
{
//    public function defaultScope()
//    {
//        return array(
//            'condition'=>"group_id='".Yii::app()->user->active_group."'",
//        );
//    }
    public static $status = array(
        'not_paid' => 'Не оплачен',
        'paid' => 'Оплачен',
        'pending' => 'В ожидании',
        'denied' => 'Откланен',
    );
    public function tableName()
    {
            return 'orders';
    }
    public function rules()
    {
            return array(
                    array('id, number, amount, status, date_create, description, user_id, rate_id, group_id', 'required'),
                    array('id, number, user_id, rate_id, group_id', 'numerical', 'integerOnly'=>true),
                    array('amount', 'length', 'max'=>10),
                    array('status', 'length', 'max'=>8),
                    array('id, number, amount, status, date_create, date_pay, description, system_record, user_id, rate_id, group_id', 'safe', 'on'=>'search'),
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
                    'number' => 'Number',
                    'amount' => 'Amount',
                    'status' => 'Status',
                    'user_id' => 'User Id',
                    'rate_id' => 'Rate Id',
                    'group_id' => 'Group Id',
                    'date_create' => 'Date Create',
                    'date_pay' => 'Date Pay',
                    'description' => 'Description',
                    'system_record' => 'System Record',
            );
    }
    public function search()
    {
            $criteria=new CDbCriteria;
            $criteria->compare('id',$this->id);
            $criteria->compare('number',$this->number);
            $criteria->compare('amount',$this->amount,true);
            $criteria->compare('status',$this->status,true);
            $criteria->compare('user_id',$this->user_id);
            $criteria->compare('rate_id',$this->rate_id);
            $criteria->compare('group_id',$this->group_id);
            $criteria->compare('date_create',$this->date_create,true);
            $criteria->compare('date_pay',$this->date_pay,true);
            $criteria->compare('description',$this->description,true);
            $criteria->compare('system_record',$this->system_record,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    public function behaviors()
    {
        return array(
            'NumberOrder' => array(
                'class' => 'ext.behaviors.NumberOrder',
            ),
            'SetUserId' => array(
                'class' => 'ext.behaviors.SetUserId',
            ),
            'DateCreate' => array(
                'class' => 'ext.behaviors.DateCreate',
            ),
        );
    }
}
