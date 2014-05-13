<?php
class SiteController extends CController
{
    public $layout = '//layouts/column1';
    public function actionIndex()
    {
        $rate = new Rates();
        $rate->setRulesByGroup(1,1);die;
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
                $this->redirect(array('index'));
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
            $model->setScenario('register');
            if($model->validate() && $model->createUser())
            {
                $this->redirect(array('registerEnd'));
            }
        }
        $this->render('register', array(
            'model' => $model,
        ));
    }
    public function actionRegisterEnd()
    {
        $this->render('register_end');
    }
    public function actionActivate($code)
    {
        $user = User::model()->find('activation_string = :code',array(':code'=>$code));
        if($user)
        {
            if($user->setActive() && $user->login(true))
            {
                 $this->redirect(array('index'));
            }
        }
        else
        {
            throw new CHttpException(404, 'Page not found');
        }
    }
    public function actionCreateGroup()
    {
        $model = new Groups();
        $model->unsetAttributes();
        if(isset($_POST['Groups']))
        {
            $model->attributes = $_POST['Groups'];
            if($model->validate() && $model->CreateGroup())
            {
                $this->redirect(array('index'));
            }
        }
        $this->render('create_group', array(
            'model' => $model,
        ));
    }
    public function actionLogout()
    {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }
    public function actionRates()
    {
        $rates = Rates::model()->findAll();
        $this->render('rates',array(
            'rates' => $rates,
        ));
    }
    public function actionBuy($id) 
    {
        $rate = Rates::model()->findByPk($id);
        $user = User::model()->findByPk(Yii::app()->user->id);
        if($rate && $user) 
        {
            $order = new Orders();
            $order->amount = $rate->price;
            $order->status = 'pending';
            $order->description = 'Оплата по тарифу '.$rate->name;
            $order->rate_id = $rate->id;
            $order->group_id = Yii::app()->user->active_group;
            if($order->save())
            {
                Yii::app()->robokassa->pay(
                    $order->amount,
                    $order->id,
                    $order->description,
                    $user->email
                );
            }
        }
    }
    public function actionResult() 
    {
        $rc = Yii::app()->robokassa;
        $rc->onSuccess = function($event)
        {
            $transaction = Yii::app()->db->beginTransaction();
            $order_id = Yii::app()->request->getParam('InvId');
            $order = Orders::model()->findByPk($order_id);
            $order->date_pay = date('Y-m-d H:i:s');
            $order->status = 'paid';
            if ($order->save()) 
            {
                Rates::setRulesByGroup($order->group_id, $order->rate_id);
            }
            else
            {
                $transaction->rollback();
            }
            $transaction->commit();
        };
        $rc->onFail = function($event)
        {
            $order_id = Yii::app()->request->getParam('InvId');
            $order = Orders::model()->findByPk($order_id);
            $order->delete();
        };
        $rc->result();
    }
    public function actionFailure() 
    {
        Yii::app()->user->setFlash('error', 'Отказ от оплаты. Если вы столкнулись с трудностями при оплате тарифа, свяжитесь с нашей технической поддержкой.');
        $this->redirect(array('index'));
    }
    public function actionSuccess()
    {
        $order_id = Yii::app()->request->getParam('InvId');
        $order = Orders::model()->findByPk($order_id);
        if ($order) 
        {
            if ($order->date_pay) 
            {
                Yii::app()->user->setFlash('success', 'Тариф оплачен и будет активирован в течение нескольких минут. Спасибо.');
            } 
            else
            {
                Yii::app()->user->setFlash('warning', 'Ваш платеж принят. Тариф будет активирован в течение нескольких минут. Спасибо.');
            }
        }
        $this->redirect(array('index'));
    }
    public function actionUsersGroup()
    {
        $group = Groups::model()->findByPk(Yii::app()->user->active_group);
        $relations = new RelationGroupUser();
        $relations->group_id = $group->id;
        if(isset($_REQUEST['email']) && isset($_REQUEST['group_id']))
        {
            $relation = new RelationGroupUser();
            $relation->email = $_REQUEST['email'];
            $relation->group_id = $_REQUEST['group_id'];
            if($relation->sendInvite())
            {
                Yii::app()->user->setFlash('success', 'Запрос отправлен.');
            }
        }
        $this->render('users_group',array(
            'relations' => $relations,
            'group' => $group,
        ));
    }
    public function actionInvite($key)
    {
        Yii::app()->user->setState('key_invite_to_group', $key);
        if(Yii::app()->user->isGuest())
        {
            $this->redirect(array('login'));
        }
        else
        {
           $user = User::model()->findByPk(Yii::app()->user->id);
           $user->setAssignedRule();
           $this->redirect(array('index'));
        }
    }
}