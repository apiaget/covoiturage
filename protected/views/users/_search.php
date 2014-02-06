<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'cpnvId'); ?>
		<?php echo $form->textField($model,'cpnvId',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hideEmail'); ?>
		<?php echo $form->textField($model,'hideEmail'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>45,'maxlength'=>45)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'hideTelephone'); ?>
		<?php echo $form->textField($model,'hideTelephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notifInscription'); ?>
		<?php echo $form->textField($model,'notifInscription'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notifComment'); ?>
		<?php echo $form->textField($model,'notifComment'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notifUnsuscribe'); ?>
		<?php echo $form->textField($model,'notifUnsuscribe'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notifDeleteRide'); ?>
		<?php echo $form->textField($model,'notifDeleteRide'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'notifModification'); ?>
		<?php echo $form->textField($model,'notifModification'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'blacklisted'); ?>
		<?php echo $form->textField($model,'blacklisted'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'admin'); ?>
		<?php echo $form->textField($model,'admin'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->