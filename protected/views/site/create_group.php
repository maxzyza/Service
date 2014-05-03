<?php
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm',array(
        'id' => 'verticalForm',
        'htmlOptions' => array('class' => 'well'),
    )
);
echo $form->textFieldRow($model, 'name', array('class' => 'span3'));
?>
<div class="form-actions">
<?php 
$this->widget('bootstrap.widgets.TbButton',array(
    'buttonType' => 'submit', 
    'label' => 'Создать',
    'type' => 'primary',)
);
 
$this->endWidget();
unset($form);?>
</div>