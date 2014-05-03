<?php
class Groups extends CActiveRecord
{
    public function tableName()
    {
        return 'groups';
    }
    public function rules()
    {
        return array(
                array('name', 'length', 'max'=>255),
                array('id, name', 'safe', 'on'=>'search'),
        );
    }
    public function relations()
    {
        return array(
        );
    }
    public function attributeLabels()
    {
        return array(
                'id' => 'ID',
                'name' => 'Имя группы',
        );
    }
    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('name',$this->name,true);

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
    public function CreateGroup()
    {
        $result = false;
        if($this->save())
        {
            $relarion_group_user = new RelationGroupUser();
            $relarion_group_user->group_id = $this->id;
            $relarion_group_user->user_id = Yii::app()->user->id;
            $relarion_group_user->type = 'admin';
            if($relarion_group_user->save())
            {
                $result = true;
            }
        }
        return $result;
    }
    public static function getGroupsUser()
    {
        $result = array();
        $user_id = Yii::app()->user->id;
        $relations_group_user = RelationGroupUser::model()->findAllByAttributes(array('user_id' => $user_id));
        if($relations_group_user !== array())
        {
            foreach($relations_group_user as $relation_group_user)
            {
                $group = Groups::model()->findByPk($relation_group_user->group_id);
                if($group)
                {
                    $result[] = array('label' => $group->name, 'url' => array('/site/selectGroup', 'id' => $group->id), 'active' => $relation_group_user->active);
                }
            }
        }
        $result[] = '---';
        $result[] = array('label' => 'Создать группу', 'url' => array('/site/createGroup'));
        return $result;
    }
    public function CreateDefault($user_id)
    {
        $result = false;
        $group = new Groups();
        $group->name = 'Default';
        if($group->save())
        {
            $relation_group_user = new RelationGroupUser();
            $relation_group_user->user_id = $user_id;
            $relation_group_user->group_id = $group->id;
            $relation_group_user->active = 1;
            $relation_group_user->type = 'admin';
            if($relation_group_user->save())
            {
                $result = $relation_group_user->id;
            }
        }
        return $result;
    }
    public function isAdminGroup()
    {
        $result = false;
        $user_id = Yii::app()->user->id;
        $group_id = Yii::app()->user->active_group;
        $type = Yii::app()->db->createCommand()
                ->select('type')
                ->from('relation_group_user')
                ->where(array('AND', "user_id = '{$user_id}'", "group_id = '{$group_id}'"))
                ->queryScalar();
        if($type == 'admin')
        {
            $result = true;
        }
        return $result;
    }
}
