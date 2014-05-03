<?php
class SetUserId extends CActiveRecordBehavior
{
    public $nameAttribute = 'user_id';
    public function beforeSave($event)
    {

        if ($this->getOwner()->getIsNewRecord() && ($this->nameAttribute !== null))
        {
            $this->getOwner()->{$this->nameAttribute} = Yii::app()->user->id;
        }

    }
}
