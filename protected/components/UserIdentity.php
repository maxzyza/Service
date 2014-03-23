<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
        const ERROR_BANNED = 4;
        const ERROR_ACTIVE = 3;
        private $_id;
        private $_group;
        public function authenticate()
        {
            $username = strtolower($this->username);
            $user = User::model()->find('LOWER(email)=?', array($username));

            if ($user === null)
            {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            }
            else
            {
                if ($user->banned === '1')
                {
                    $this->errorCode = self::ERROR_BANNED;
                }
                elseif($user->active === '0')
                {
                    $this->errorCode = self::ERROR_ACTIVE;
                }
                else
                {
                    if (!$user->validatePassword($this->password))
                    {
                        $this->errorCode = self::ERROR_PASSWORD_INVALID;
                    }
                    else
                    {
                        $this->_id = $user->id;
                        $this->_group = $user->group;
                        $this->errorCode = self::ERROR_NONE;
                    }
                }
            }
            return $this->errorCode;
        }
        public function getId()
        {
            return $this->_id;
        }
        public function getGroup()
        {
            return $this->_group;
        }
}