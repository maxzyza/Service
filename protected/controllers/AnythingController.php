<?php
class AnythingController extends CController
{
    public function filters() {
        return array(
                'accessControl',
                );
    }

    public function accessRules() {
        return array(
            array('allow',
                'roles'=>array('prepaid_rate'),
                ),
            array('deny',
                'users'=>array('*'),
                ),
                );
    }
    public function actionIndex()
    {
        $this->render('index');
    }
}