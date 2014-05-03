<?php
$this->breadcrumbs=array(
	'Админка' => array('/admin/panel/admin'),
	'Список тарифов' => array('/admin/Rates/admin'),
        'Редактирование тарифа',
);
$this->pageTitle = 'Редактирование тарифа';
$this->menu=array(
    array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
    array('label'=>'Список тарифов','url'=>array('/admin/Rates/admin')),
    array('label'=>'Создать тариф','url'=>array('/admin/Rates/create')),
);
?>
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
