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
//            $user = $model->updateUser($model);
//            $user->save();
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
            if(User::createUser($model) === true)
            {
                
            }
        }
        $this->render('register');
    }
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
}