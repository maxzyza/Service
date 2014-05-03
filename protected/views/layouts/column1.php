<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
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
<?php $this->endContent(); ?>