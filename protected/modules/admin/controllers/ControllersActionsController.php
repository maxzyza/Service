<?php

class ControllersActionsController extends Controller
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
        $model = new ControllersActions('search');
        if(isset($_REQUEST['ControllersActions']))
        {
            $model->attributes = $_REQUEST['ControllersActions'];
        }
        $this->render('admin',array(
            'model' => $model
                ));
    }
    public function actionUpdateTable()
    {
        $model = new ControllersActions();
        $model->updateTable();
        $this->redirect(array('admin'));
    }
    public function actionAddRates($id)
    {
        $model = new RelationControllersActionsRates('search');
        if(isset($_REQUEST['RelationControllersActionsRates']))
        {
            $model->attributes = $_REQUEST['RelationControllersActionsRates'];
        }
        if(isset($_REQUEST['rate_id']))
        {
            $relation = RelationControllersActionsRates::model()->findByAttributes(array('controller_action_id' => $id, 'rate_id' => $_REQUEST['rate_id']));
            if(!$relation)
            {
                $relation = new RelationControllersActionsRates();
                $relation->controller_action_id = $id;
                $relation->rate_id = $_REQUEST['rate_id'];
                $relation->save();
            }
        }
        $model->controller_action_id = $id;
        $this->render('add_rates',array(
            'model' => $model,
        ));
    }
    public function actionDelete($id)
    {
        $model = ControllersActions::model()->findByPk($id);
        if($model)
        {
            $model->delete();
        }
    }
    public function actionDeleteRelation($id)
    {
        $model = RelationControllersActionsRates::model()->findByPk($id);
        if($model)
        {
            $model->delete();
        }
    }
}