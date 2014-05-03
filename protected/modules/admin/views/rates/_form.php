<?php
 $form=$this->beginWidget('ext.bootstrap.widgets.TbActiveForm',array(
	'id'=>'product-form',
	'enableAjaxValidation'=>false,
)); ?>
<?php
 echo $form->errorSummary($model);

echo $form->textFieldRow($model,'name');
echo $form->textFieldRow($model,'price');
?>
<div class="form-actions">
        <?php $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType'=>'submit',
                'type'=>'primary',
                'label'=>$model->isNewRecord ? 'Создать' : 'Сохранить',
        )); ?>
</div>
<?php $this->endWidget(); ?>

