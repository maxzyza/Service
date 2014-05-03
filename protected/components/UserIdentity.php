<?php
class UserIdentity extends CUserIdentity
{
        const ERROR_BANNED = 4;
        const ERROR_ACTIVE = 3;
        private $_id;
        public function authenticate($doNotValidatePassword = false)
        {
            $username = strtolower($this->username);
            $user = User::model()->find('LOWER(email)=?', array($username));

            if (!$user)
            {
                $this->errorCode = self::ERROR_USERNAME_INVALID;
            }
            else
            {
                if ($user->banned)
                {
                    $this->errorCode = self::ERROR_BANNED;
                }
                elseif(!$user->active)
                {
                    $this->errorCode = self::ERROR_ACTIVE;
                }
                else
                {
                    if (!$doNotValidatePassword && !$user->validatePassword($this->password))
                    {
                        $this->errorCode = self::ERROR_PASSWORD_INVALID;
                    }
                    else
                    {
                        $this->_id = $user->id;
                        $this->setState('active_group', $user->getActiveGroup());
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
}
