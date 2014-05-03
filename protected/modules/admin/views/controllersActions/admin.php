<?php
$this->breadcrumbs=array(
        'Админка'=>array('/admin/panel/admin'),
	'Список контроллеров и действий',
);
$this->pageTitle = "Список контроллеров и действий";
$this->menu=array(
    array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Обновить таблицу','url'=>array('updateTable')),
);

 $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'id'=>'controllers-actions-grid',
	'dataProvider'=>$model->search(),
        //'type'=>'striped bordered condensed',
        'enableHistory'=>true,
	'filter'=>$model,
	'columns'=>array(
            'controller',
            'action',
		array(
                    'class'=>'bootstrap.widgets.TbButtonColumn',
                    'template'=>'{update} {delete} {add}',
                    'buttons'=>array
                    (
                        'add' => array
                        (
                            'icon' => 'icon-list',
                            'label' => 'Добавить тариф',
                            'url'=>'Yii::app()->createUrl("admin/ControllersActions/addRates", array("id"=>$data->id))',
                        ),
                    )
		),
	),
));