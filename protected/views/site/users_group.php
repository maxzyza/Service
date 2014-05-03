<h3><?php echo 'Группа '.$group->name;?></h3>
<?php
$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'inlineForm',
        'type' => 'inline',
        'action' => array('addAnswer'),
    )
);
echo CHtml::hiddenField('group_id', $group->id);
?>
<div>
    <label>Email</label>
</div>
<?php
echo CHtml::textField('email','');
$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Добавить пользователя')
);
$this->endWidget();

 $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'id'=>'users-group-grid',
	'dataProvider'=>$relations->search(),
        'type'=>'striped bordered condensed',
        'enableHistory'=>true,
	//'filter'=>$relations,
	'columns'=>array(
            'group_id',
            'user_id',
//            array(
//                'class'=>'bootstrap.widgets.TbButtonColumn',
//                'template'=>'{delete}',
//                'buttons'=>array
//                (
//                    'delete' => array(
//                        'url'=>'Yii::app()->createUrl("", array("id"=>$data->id))',
//                    ),
//                )
//            ),
	),
));