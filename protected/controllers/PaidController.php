<?php
class PaidController extends CController
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
    public function actionAccess()
    {
        $this->render('access');
    }
}