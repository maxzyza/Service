<?php
$this->breadcrumbs=array(
        'Админка'=>array('/admin/panel/admin'),
	'Список контроллеров и действий'=>array('/admin/ControllersActions/admin'),
        'Добавление тарифов',
);
$this->pageTitle = "Добавление тарифов";
$this->menu=array(
    array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Список контроллеров и действий','url'=>array('/admin/ControllersActions/admin')),
);


$form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'inlineForm',
        'type' => 'inline',
    )
);
echo CHtml::dropDownList('rate_id','', Rates::getListRate(), array('class' => 'input-xxlarge'));
$this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'label' => 'Добавить тариф')
);
$this->endWidget();

 $this->widget('bootstrap.widgets.TbExtendedGridView',array(
	'id'=>'controllers-actions-grid',
	'dataProvider'=>$model->search(),
        //'type'=>'striped bordered condensed',
        'enableHistory'=>true,
	'filter'=>$model,
	'columns'=>array(
            array(
                'name' => 'rate_id',
                'value' => function($data, $row)
                {
                    return $data->rate_id;
                },
            ),
            array(
                'class'=>'bootstrap.widgets.TbButtonColumn',
                'template'=>'{delete}',
                'buttons'=>array
                (
                    'delete' => array
                    (
                        'url'=>'Yii::app()->createUrl("/admin/controllersActions/deleteRelation", array("id"=>$data->id))',
                    ),
                )
            ),
	),
));