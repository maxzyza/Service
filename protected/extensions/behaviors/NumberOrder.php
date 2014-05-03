<?php
class NumberOrder extends CActiveRecordBehavior
{
    public $nameAttribute = 'number';
    public function beforeSave($event)
    {

        if ($this->getOwner()->getIsNewRecord() && ($this->nameAttribute !== null))
        {
            $this->getOwner()->{$this->nameAttribute} = $this->getNumber();
        }

    }
    protected function getNumber()
    {
        $count = Yii::app()->db->createCommand()
                ->select('COUNT(id)')
                ->from('orders')
                ->queryScalar();
        $count += Rates::$number + 1;
        return $count;
    }
}
