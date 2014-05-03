<?php
class RelationGroupUser extends CActiveRecord
{
    public $email; 
    public function tableName()
    {
        return 'relation_group_user';
    }
    public function rules()
    {
        return array(
                array('group_id, user_id', 'numerical', 'integerOnly'=>true),
                array('id, group_id, user_id, active, type', 'safe', 'on'=>'search'),
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
                'group_id' => 'Group',
                'user_id' => 'User',
                'active' => 'active',
                'type' => 'Type',
        );
    }
    public function search()
    {
        $criteria=new CDbCriteria;
        $criteria->compare('id',$this->id);
        $criteria->compare('group_id',$this->group_id);
        $criteria->compare('user_id',$this->user_id);
        $criteria->compare('active',$this->active);
        $criteria->compare('type',$this->type);
        $criteria->addCondition("type <> 'admin'");

        return new CActiveDataProvider($this, array(
                'criteria'=>$criteria,
        ));
    }
    public static function model($className=__CLASS__)
    {
            return parent::model($className);
    }
    public function sendInvite()
    {
        $result = false;
        $invite  = new InviteUserGroup();
        $invite->email = $this->email;
        $invite->group_id = $this->group_id;
        $invite->key = md5($this->group_id.$this->email);
        if($invite->save())
        {
            Yii::app()->mail->sendEmailWithInviteCode($invite);
            $result = true;
        }
        return $result;
    }
}
