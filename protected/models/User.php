<?php
class User extends CActiveRecord
{
    private $_identity;
    private $algo = '$2a';
    private $cost = '$10';
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
    public function tableName()
    {
        return 'users';
    }
    public function rules()
    {
        return array(
            array('email, password', 'required', 'on' => 'login'),
            array('email, password, name', 'required', 'on' => 'register'),
            array('email, password, name, surname, activation_string, salt', 'type'),
            array('active, banned','boolean','allowEmpty' => true),
            array('email, password, name, surname, activation_string, salt, banned, active', 'safe', 'on' => 'search'),
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
            'password' => 'Пароль',
            'name' => 'Имя',
            'surname' => 'Фамилия',
            'activation_string' => 'Строка активации',
            'salt' => 'Соль',
            'banned' => 'Забанен',
            'active' => 'Активирован',
        );
    }
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('email', $this->email);
        $criteria->compare('password', $this->password);
        $criteria->compare('name', $this->name);
        $criteria->compare('surname', $this->surname);
        $criteria->compare('activation_string', $this->activation_string);
        $criteria->compare('salt', $this->salt);
        $criteria->compare('banned', $this->banned);
        $criteria->compare('active', $this->active);

        return new CActiveDataProvider($this,array(
            'criteria' => $criteria,
            ));
    }
    public function getActivate_url()
    {
        if ($this->activation_string)
            return Yii::app()->request->getBaseUrl(true).'/activate'.Yii::app()->urlManager->urlSuffix.'?code='.$this->activation_string;

        return false;
    }
    public function getRecover_url()
    {
        if ($this->activation_string)
            return Yii::app()->request->getBaseUrl(true).'/activate'.Yii::app()->urlManager->urlSuffix.'?code='.$this->activation_string.'&recover=1';

        return false;
    }
    public function login($doNotValidatePassword = false)
    {
        if ($this->_identity === null)
        {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate($doNotValidatePassword);

        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
        {
            $duration = 3600 * 24 * 30;
            Yii::app()->user->login($this->_identity, $duration);
            $this->setAssignedRule();
            return true;
        }
        else
        {
            switch ($this->_identity->errorCode)
            {
                case UserIdentity::ERROR_PASSWORD_INVALID:
                    $this->addError('password', 'Неверный пароль.');
                    break;
                case UserIdentity::ERROR_USERNAME_INVALID:
                    $this->addError('email', 'Неверный email.');
                    break;
                case UserIdentity::ERROR_ACTIVE:
                    $this->addError('email', 'Вы не активировали ваш аккаунт - проверьте почту.');
                    break;
                case UserIdentity::ERROR_BANNED:
                    $this->addError('email', 'Ваш аккаунт заблокирован - обратитесь к администратору.');
                    break;
            }
        }
        return false;
    }
    public function validatePassword($password)
    {
        return $this->check_password($this->password, $password);
    }
    public function hashSalt() 
    {
        return substr(sha1(mt_rand()),0,22);
    }
    public function hashPassword($password) 
    {
        $string = crypt($password,
                    $this->algo .
                    $this->cost .
                    '$' . $this->salt);
        $string = str_replace('/', '#', $string);
        return $string;
    }
    public function check_password($hash, $password) 
    {
        $full_salt = substr($hash, 0, 29);

        $new_hash = crypt($password, $full_salt);
        $new_hash = str_replace('/', '#', $new_hash);
        return ($hash == $new_hash);

    }
    public function createUser()
    {
        $result = false;
        $user = new User();
        $user->setScenario('register');
        $user->name = $this->name;
        $user->email = $this->email;
        $user->salt = $this->hashSalt();
        $user->password = $user->hashPassword($this->password);
        $user->activation_string = $user->hashPassword($user->password);
        if($user->save())
        {
            $group = new Groups();
            if(($relation_id = $group->CreateDefault($user->id)) !== false)
            {
                $user->setRulesByRelation($relation_id);
            }
            $result = true;
        }
        return $result;
    }
    public static function updateUser(User $user)
    {
        $user->salt = $user->hashSalt($user->email);
        $user->password = $user->hashPassword($user->password);
        $user->activation_string = $user->hashPassword($user->password);
        return $user;
    }
    public function setRulesByRelation($relation_id)
    {
        $auth = Yii::app()->authManager;
        $role = 'admin_group_'.$relation_id;
        $auth->createRole($role, 'Администратор группы');
        $auth->assign($role, $this->id);
    }
    public function setActive()
    {
        $result = false;
        $this->active = 1;
        $this->activation_string = '';
        $this->validate();
        if($this->save())
        {
            $result = true;
        }
        return $result;
    }
    public function getActiveGroup()
    {
        $group_id = false;
        $relation = RelationGroupUser::model()->findByAttributes(array('user_id' => $this->id, 'active' => 1));
        if($relation)
        {
            $group_id = $relation->group_id;
        }
        return $group_id;
    }
    public static function getMenu()
    {
        $result = array();
        if(!Yii::app()->user->isGuest)
        {
            $result[] = array(
                'class' => 'bootstrap.widgets.TbMenu',
                'items' => array(
                    array('label' => 'Админка', 'url' => array('/admin/panel/admin')),
                    array('label' => 'Тарифы', 'url' => array('/site/rates')),
                    array('label' => 'Пользователи группы', 'url' => array('/site/usersGroup'), 'visible' => Groups::isAdminGroup()),
                    array('label' => 'Платный функционал', 'url' => array('/anything/index')),
                    array(
                        'label' => 'Группы',
                        'url' => '#',
                        'items' => Groups::getGroupsUser(),
                    ),
                ),
            );
        }
        $result[] = array(
            'class' => 'bootstrap.widgets.TbMenu',
            'htmlOptions' => array('class' => 'pull-right'),
            'items' => array(
                array('label' => 'Регистрация', 'url' => array('/site/register')),
                array('label' => 'Вход', 'url' => array('/site/login'), 'visible'=>Yii::app()->user->isGuest),
                array('label' => 'Выход ('.Yii::app()->user->name.')', 'url' => array('/site/logout'), 'visible'=>!Yii::app()->user->isGuest),
            ),
        );
        return $result;
    }
    public function setAssignedRule()
    {
        $key = Yii::app()->user->getState('key_invite_to_group');
        if($key)
        {
            $invite = InviteUserGroup::model()->findByAttributes(array('key' => $key));
            if($invite)
            {
                Rates::addRulesByUser($invite->group_id, Yii::app()->user->id);
                $invite->delete();
            }
        }
    }
    public function getPayRate($group_id)
    {
        $result = false;
        $pay_rate_id = Yii::app()->db->createCommand()
                ->select('rate_id')
                ->from('orders o')
                ->join('rates r', 'r.id = o.rate_id')
                ->where(array('AND', "o.user_id = '{$this->id}'", "o.group_id = '{$group_id}'", "CURDATE() BETWEEN o.date_pay AND DATE_ADD(o.date_pay, INTERVAL r.month MONTH)"))
                ->queryScalar();
        if($pay_rate_id)
        {
            $rate = Rates::model()->findByPk($pay_rate_id);
            if($rate)
            {
                $result = $rate;
            }
        }
        return $result;
    }
}