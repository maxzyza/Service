<?php
class NextRateBehavior extends CActiveRecordBehavior
{
    public $nameAttribute = 'name_program';
    public function beforeSave($event)
    {

        if ($this->getOwner()->getIsNewRecord() && ($this->nameAttribute !== null))
        {
            $this->getOwner()->{$this->nameAttribute} = 'rate_'.$this->getNextRateNumber();
        }

    }
    protected function getNextRateNumber()
    {
        $count = Yii::app()->db->createCommand()
                ->select('COUNT(id)')
                ->from('rates')
                ->queryScalar();
        $count++;
        return $count;
    }

}
