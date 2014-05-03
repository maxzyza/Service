<?php
class ControllersActions extends CActiveRecord
{
    public function tableName()
    {
            return 'controllers_actions';
    }
    public function rules()
    {
            return array(
                    array('controller, action', 'length', 'max'=>255),
                    array('id, controller, action', 'safe', 'on'=>'search'),
            );
    }
    public function relations()
    {
            return array(
                //'Relation' => array(),
            );
    }
    public function attributeLabels()
    {
            return array(
                    'id' => 'ID',
                    'controller' => 'Контроллер',
                    'action' => 'Действие',
            );
    }
    public function search()
    {
            $criteria=new CDbCriteria;

            $criteria->compare('id',$this->id);
            $criteria->compare('controller',$this->controller,true);
            $criteria->compare('action',$this->action,true);

            return new CActiveDataProvider($this, array(
                    'criteria'=>$criteria,
            ));
    }

    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    public function updateTable()
    {
        $result = false;
        $path = Yii::app()->basePath.DIRECTORY_SEPARATOR.'controllers'.DIRECTORY_SEPARATOR;
        $dir = dir($path);
        while (($entry = $dir->read()) !== false) 
        {
            if (is_file($path . $entry)) 
            {
                $controller = current(explode('.', $entry));
                $contents = file_get_contents($path.$entry);
                preg_match_all('/function action([a-zA-Z0-9_]+)/', $contents, $matches);
                foreach($matches[1] as $action)
                {
                    $criteria = new CDbCriteria();
                    $criteria->compare('controller', $controller);
                    $criteria->compare('action', $action);
                    $controller_action = ControllersActions::model()->find($criteria);
                    if(!$controller_action)
                    {
                        $controller_action = new ControllersActions();
                        $controller_action->controller = strtolower($controller);
                        $controller_action->action = strtolower($action);
                        $controller_action->save();
                    }
                    unset($criteria);
                    unset($controller_action);
                }
                unset($matches);
            }
        }
        unset($dir);
        return $result;
    }
}
