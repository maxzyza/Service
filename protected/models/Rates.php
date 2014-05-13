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
        $result = false;
        if(($name_program = $this->getRateNameProgram($CInlineAction)) !== false)
        {
            $access = $name_program.'_'.Yii::app()->user->active_group;
            if(Yii::app()->user->checkAccess($access))
            {
                $result = true;
            }
        }
        return $result;
    }
    public function getRateNameProgram($CInlineAction)
    {
        $result = false;
        $controller = $CInlineAction->getController()->getId().'controller';
        $action = $CInlineAction->getId();
        $rate_data = Yii::app()->db->createCommand()
                ->select('r.id, r.name_program')
                ->from('rates r')
                ->join('relation_controllers_actions_rates rcar', 'r.id = rcar.rate_id')
                ->join('controllers_actions ca', 'rcar.controller_action_id = ca.id')
                ->where(array('AND', "ca.controller = '{$controller}'", "ca.action = '{$action}'"))
                ->queryRow();
        if($rate_data)
        {
            $rate = Rates::model()->findByPk($rate_data['id']);
            if($rate && $rate->CheckPaid())
            {
                $result = $rate_data['name_program'];
            }
        }
        return $result;
    }
    public static function setRulesByGroup($group_id, $rate_id)
    {
        //найти пользователей группы
        //проставить права согласно тарифу
        $auth = Yii::app()->authManager;
        $group = Groups::model()->findByPk($group_id);
        $rate = Rates::model()->findByPk($rate_id);
        if($group && $rate)
        {
            $roles = $auth->getRoles();
            $rule = $rate->name_program.'_'.$group->id;
            if(!array_key_exists($rule, $roles))
            {
                $auth->createRole($rule, $rate->name.'_'.$group->id);
            }
            $users = $group->getUsersGroup();
            if($users !== false)
            {
                foreach($users as $user_id)
                {
                    if(!$auth->isAssigned($rule, $user_id)) 
                    {
                        $auth->assign($rule, $user_id);
                    }
                }
            }
        }
    }
    public static function addRulesByUser($group_id, $user_id)
    {
        //поиск админа группы
        //проверка какой у админа оплаченный тариф
        //проставить права согласно тарифу
        $group = Groups::model()->findByPk($group_id);
        $admin = $group->getAdmin();
        if($admin)
        {
            $rate = $admin->getPayRate($group_id);
            if($rate !== false)
            {
                $auth = Yii::app()->authManager;
                $roles = $auth->getRoles();
                $rule = $rate->name_program.'_'.$group_id;
                if(!array_key_exists($rule, $roles))
                {
                    $auth->createRole($rule, $rate->name.'_'.$group_id);
                }
                if(!$auth->isAssigned($rule, $user_id)) 
                {
                    $auth->assign($rule, $user_id);
                }
            }
        }
    }
    public static function deleteRules($group_id, $rate_id)
    {
        $auth = Yii::app()->authManager;
        $users_id = Yii::app()->db->createCommand()
                ->select('id')
                ->from('users')
                ->queryColumn();
        $rate = Rates::model()->findByPk($rate_id);
        if($users_id && $rate)
        {
            $rule = $rate->name_program.'_'.$group_id;
            foreach($users_id as $user_id)
            {
                $auth->revoke($rule, $user_id);
            }
        }
    }
    public function CheckPaid()
    {
        $result = false;
        $group_id = Yii::app()->user->active_group;
        $user_id = Yii::app()->user->id;
        if($group_id)
        {
            $pay_rate_id = Yii::app()->db->createCommand()
                ->select('rate_id')
                ->from('orders o')
                ->join('rates r', 'r.id = o.rate_id')
                ->where(array('AND', "o.user_id = '{$user_id}'", "o.rate_id = '{$this->rate_id}'", "o.group_id = '{$group_id}'", "CURDATE() BETWEEN o.date_pay AND DATE_ADD(o.date_pay, INTERVAL r.month MONTH)"))
                ->queryScalar();
            if($pay_rate_id)
            {
                $result = true;
            }
            else
            {
                Rates::deleteRules($group_id, $this->id);
            }
        }
        return $result;
    }
}
