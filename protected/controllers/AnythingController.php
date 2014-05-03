<?php
class AnythingController extends CController
{
    public $layout = '//layouts/column1';
    public function filters() {
        return array(
                'accessControl',
                );
    }
    public function accessRules() {
        return array(
            array('allow',
                'users'=>array('@'),
                ),
            array('deny',
                'users'=>array('*'),
                ),
                );
    }
    protected function beforeAction($action)
    {
        $rates = new Rates();
        if($rates->CheckAccess($action))
        {
            $this->redirect(array('/paid/access'));
        }
        return parent::beforeAction($action);
    }
    public function actionIndex()
    {
        $this->render('index');
    }
}