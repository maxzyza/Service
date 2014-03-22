<?php
class PanelController extends CController
{
    public function filters() {
        return array(
                'accessControl',
                );
    }

    public function accessRules() {
        return array(
            array('allow',
                'roles'=>array('admin'),
                ),
            array('allow',
                'actions'=>array('login'),
                'users'=>array('?'),
            ),
            array('deny',
                'users'=>array('*'),
                ),
                );
    }
    public function actionAdmin()
    {
        echo 'dafs';die;
    }
}