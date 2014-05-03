<?php
$this->breadcrumbs=array(
	'Админка' => array('/admin/panel/admin'),
	'Список тарифов' => array('/admin/Rates/admin'),
        'Создание тарифа',
);
$this->pageTitle = 'Создание тарифа';
$this->menu=array(
    array('label'=>'Операции', 'itemOptions'=>array('class'=>'nav-header')),
	array('label'=>'Список тарифов','url'=>array('/admin/Rates/admin')),
);
?>
<?php echo $this->renderPartial('_form',array('model'=>$model)); ?>
