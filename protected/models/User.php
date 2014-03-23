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
            array('email, password, name, surname, activation_string, salt, banned, active, group','type'),
            array('email, password, name, surname, activation_string, salt, banned, active, group', 'safe', 'on' => 'search'),
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
            'group' => 'Группа',
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
        $criteria->compare('group', $this->group);


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
    public function login()
    {
        if ($this->_identity === null)
        {
            $this->_identity = new UserIdentity($this->email, $this->password);
            $this->_identity->authenticate();

        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE)
        {
            $duration = 3600 * 24 * 30; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
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
        return crypt($password,
                    $this->algo .
                    $this->cost .
                    '$' . $this->salt);
    }
    public function check_password($hash, $password) 
    {
        $full_salt = substr($hash, 0, 29);

        $new_hash = crypt($password, $full_salt);

        return ($hash == $new_hash);

    }
    public function createUser()
    {
        $user = new User();
        $user->name = $this->name;
        $user->email = $this->email;
        $user->salt = $this->hashSalt();
        $user->password = $user->hashPassword($this->password);
        $user->activation_string = $user->hashPassword($user->password);
        MyDebug::pre($user->attributes);die;
        return $user->save();
    }
    public static function updateUser(User $user)
    {
        $user->salt = $user->hashSalt($user->email);
        $user->password = $user->hashPassword($user->password);
        $user->activation_string = $user->hashPassword($user->password);
        return $user;
    }
}