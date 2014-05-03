<?php
class SetGroupId extends CActiveRecordBehavior
{
    public $nameAttribute = 'group_id';
    public function beforeSave($event)
    {

        if ($this->getOwner()->getIsNewRecord() && ($this->nameAttribute !== null))
        {
            $this->getOwner()->{$this->nameAttribute} = Yii::app()->user->active_group;
        }

    }
}
