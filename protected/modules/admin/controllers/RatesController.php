<?php
class RatesController extends Controller
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
            array('deny',
                'users'=>array('*'),
                ),
                );
    }
    public function actionAdmin()
    {
        $model = new Rates('search');
        if(isset($_REQUEST['Rates']))
        {
            $model->attributes = $_REQUEST['Rates'];
        }
        $this->render('admin',array(
            'model' => $model
                ));
    }
    public function actionCreate()
    {
        $model = new Rates();
        if(isset($_REQUEST['Rates']))
        {
            $model->attributes = $_REQUEST['Rates'];
            if($model->save())
            {
                $this->redirect(array('admin'));
            }
        }
        $this->render('create',array(
            'model' => $model,
        ));
    }
    public function actionUpdate($id)
    {
        $model = Rates::model()->findByPk($id);
        if(isset($_REQUEST['Rates']))
        {
            $model->attributes = $_REQUEST['Rates'];
            if($model->save())
            {
                $this->redirect(array('admin'));
            }
        }
        $this->render('update',array(
            'model' => $model,
        ));
    }
    public function actionDelete($id)
    {
        $model = Rates::model()->findByPk($id);
        if($model)
        {
            $model->delete();
        }
    }
}