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
            array('email, password', 'required'),
            array('email,password,activation_string,salt,banned,active','type'),
            array('email, password', 'safe', 'on' => 'search'),
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
            'email' => 'Email',
            'password' => 'Пароль',
        );
    }
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id, true);

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
    public static function createUser(User $user)
    {
        $create_user = new User();
        $create_user->username = $user->username;
        $create_user->email = $user->email;
        $create_user->salt = $create_user->hashSalt();
        $create_user->password = $create_user->hashPassword($user->password);
        $create_user->activation_string = $create_user->hashPassword($create_user->password);
        return $create_user->save();
    }
    public static function updateUser(User $user)
    {
        $user->salt = $user->hashSalt($user->email);
        $user->password = $user->hashPassword($user->password, $user->salt);
        $user->activation_string = $user->hashPassword($user->password, $user->salt);
        return $user;
    }
}