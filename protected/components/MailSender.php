<?php
class MailSender implements IApplicationComponent
{

    private $_init = false;
    protected $mail;

    public function init(){
        Yii::import('ext.yii-mail.*');
        $this->mail = $this->initMail();
        $this->_init = true;
    }

    public function getIsInitialized(){
        return $this->_init;
    }

    protected function initMail(){
        Yii::app()->setComponents(array(
            'mail' => array(
                'class' => 'YiiMail',
                'transportType' => 'smtp',
                'transportOptions'=>array(
                     'host'=>'smtp.yandex.ru',
                     'port'=>25,
                     'username'=>'',
                     'password'=>''
                ),
                'viewPath' => 'application.views.mail',
                'logging' => true,
            ),
        ));
        return Yii::app()->mail;
    }
    public function sendEmailWithActivationCode(User $user) {

        $view = 'registration';
        $params = array('code'=>$user->activation_string,'name'=>$user->username);
        $to = $user->email;
        $from = $this->getAdminEmail();
        $subject = 'Регистрация на сайте';
        return  $this->send($view,$to,$from,$subject,$params);
    }
    public function sendEmailWithRecoveryCode(User $user) {
        $view = 'recovery';
        $params = array('code'=>$user->activation_string,'name'=>$user->username);
        $to = $user->email;
        $from = $this->getAdminEmail();
        $subject = 'Восстановление пароля на сайте';
        return  $this->send($view,$to,$from,$subject,$params);
    }
    public function send($view,$to,$from,$subject,array $params = array()){

        $message = new YiiMailMessage;
        $message->view = $view;
        $message->setSubject($subject);
        $message->setBody($params, 'text/html');
        $message->addTo($to);
        $message->from = $from;

        return Yii::app()->mail->send($message);
    }
    public function getAdminEmail()
    {
        return 'admin@admin.ru';
    }

}
