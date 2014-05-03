<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<div class="span-19">
	<div id="content">
            <?php if (Yii::app()->user->hasFlash('warning')): ?>
                <div class="alert">
                <button type="button" class="close" data-dismiss="alert">×</button>
                    <?php echo Yii::app()->user->getFlash('warning'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('error')): ?>
                <div class="alert alert-error">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>Ошибка</h4>
                    <?php echo Yii::app()->user->getFlash('error'); ?>
                </div>
            <?php endif; ?>
            <?php if (Yii::app()->user->hasFlash('success')): ?>
                <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">×</button>
                <h4>Поздравляем</h4>
                    <?php echo Yii::app()->user->getFlash('success'); ?>
                </div>
            <?php endif; ?>
            
		<?php echo $content; ?>
	</div><!-- content -->
</div>
<div class="span-5 last">
	<div id="sidebar">
	<?php
		$this->beginWidget('zii.widgets.CPortlet', array(
			'title'=>'Operations',
		));
		$this->widget('zii.widgets.CMenu', array(
			'items'=>$this->menu,
			'htmlOptions'=>array('class'=>'operations'),
		));
		$this->endWidget();
	?>
	</div><!-- sidebar -->
</div>
<?php $this->endContent(); ?>