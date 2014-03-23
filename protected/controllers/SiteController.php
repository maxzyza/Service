<?php
class SiteController extends CController
{
    public function actionIndex()
    {
        $this->render('index');
    }
    public function actionLogin()
    {
        $model = new User();
        if (isset($_REQUEST['User']))
        {
            $model->setAttributes($_REQUEST['User']);
            $model->setScenario('login');
            if ($model->validate() && $model->login())
            {
                $this->redirect(Yii::app()->user->loginUrl);
            }
        }
        $this->render('login', array(
            'model' => $model,
        ));
    }
    public function actionRegister()
    {
        $model = new User;
        if(isset($_REQUEST['User']))
        {
            $model->attributes = $_REQUEST['User'];
            if($model->createUser() === true)
            {
                
            }
        }
        $this->render('register', array(
            'model'=>$model,
        ));
    }
    public function actionActivate($code)
    {
        $user = User::model()->find('activation_string = :code',array(':code'=>$code));
        if ($user)
        {
            $user->active = 1;
            $user->activation_string = '';
            if ($user->save())
            {
                if($user->login(true))
                {
                     $this->redirect(Yii::app()->user->loginUrl);
                }
            }
        }
        else
        {
            throw new CHttpException(404, 'Page not found');
        }
    }
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}