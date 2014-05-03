<?php
class DateCreate extends CActiveRecordBehavior
{
    public $nameAttribute = 'date_create';
    public function beforeSave($event)
    {

        if ($this->getOwner()->getIsNewRecord() && ($this->nameAttribute !== null))
        {
            $this->getOwner()->{$this->nameAttribute} = date('Y-m-d H:i:s');
        }

    }
}
