<?php
class Rates extends CActiveRecord
{
    public static $number = 1564658;
    public function tableName()
    {
            return 'rates';
    }
    public function rules()
    {
            return array(
                    array('price', 'numerical', 'integerOnly'=>true),
                    array('name, name_program', 'length', 'max'=>255),
                    array('id, name, price, name_program', 'safe', 'on'=>'search'),
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
                    'name' => 'Название',
                    'price' => 'Цена',
                    'name_program' => 'Name Program',
            );
    }

    public function search()
    {
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('name',$this->name,true);
            $criteria->compare('price',$this->price);
            $criteria->compare('name_program',$this->name_program,true);

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
            'NextRateBehavior' => array(
                'class' => 'ext.behaviors.NextRateBehavior',
            ),
        );
    }
    public static function getListRate()
    {
        $result = array();
        $rates = Rates::model()->findAll();
        if($rates !== array())
        {
            $result = CHtml::listData($rates, 'id', 'name');
        }
        return $result;
    }
    public function CheckAccess($CInlineAction)
    {
        $result = true;
        if(($name_program = $this->getRateNameProgram($CInlineAction)) !== false)
        {
            $access = $name_program.'_'.Yii::app()->user->active_group;
            if(Yii::app()->user->checkAccess($access))
            {
                $result = false;
            }
        }
        return $result;
    }
    public function getRateNameProgram($CInlineAction)
    {
        $result = false;
        $controller = $CInlineAction->getController()->getId().'controller';
        $action = $CInlineAction->getId();
        $name_program = Yii::app()->db->createCommand()
                ->select('r.name_program')
                ->from('rates r')
                ->join('relation_controllers_actions_rates rcar', 'r.id = rcar.rate_id')
                ->join('controllers_actions ca', 'rcar.controller_action_id = ca.id')
                ->where(array('AND', "ca.controller = '{$controller}'", "ca.action = '{$action}'"))
                ->queryScalar();
        if($name_program)
        {
            $result = $name_program;
        }
        return $result;
    }
    public static function setRulesByGroup($group_id, $rate_id)
    {
        $order = new Orders();
        $auth = Yii::app()->authManager;
        $auth->createRole($rule, $this->name.'_'.$order->user_id);
        $auth->getRoles();
        if (!$auth->isAssigned($rule, $order->user_id)) 
        {
            $auth->assign($rule, $order->user_id);
        }
    }
    public static function addRulesByUser($group_id, $user_id)
    {
        
    }
    public static function deleteRules($group_id, $rate_id)
    {
//        $rate = Rates::model()->findByPk($this->rate_id);
//        $rule = $rate->name_program.''.$this->group_id;
//        $auth = Yii::app()->authManager;
//        $auth->revoke($rule, $this->user_id);
    }
}
