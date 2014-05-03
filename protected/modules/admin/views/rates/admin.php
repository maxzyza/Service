<?php
$this->breadcrumbs=array(
        'Админка'=>array('/admin/panel/admin'),
	'Список тарифов',
);
$this->pageTitle = "Список тарифов";
$this->menu=array(
    array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Добавить тариф','url'=>array('create')),
);

 $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'id'=>'controllers-actions-grid',
	'dataProvider'=>$model->search(),
        //'type'=>'striped bordered condensed',
        'enableHistory'=>true,
	'filter'=>$model,
	'columns'=>array(
            'name',
            'price',
		array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{update} {delete}',
//                    'buttons'=>array
//                    (
//                        '' => array
//                        (
//                            'icon' => 'icon-list',
//                            'label' => '',
//                            'url'=>'Yii::app()->createUrl("", array("id"=>$data->id))',
//                        ),
//                    )
		),
	),
));