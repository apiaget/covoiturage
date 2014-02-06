<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'users-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'cpnvId'); ?>
		<?php echo $form->textField($model,'cpnvId',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'cpnvId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hideEmail'); ?>
		<?php echo $form->textField($model,'hideEmail'); ?>
		<?php echo $form->error($model,'hideEmail'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'telephone'); ?>
		<?php echo $form->textField($model,'telephone',array('size'=>45,'maxlength'=>45)); ?>
		<?php echo $form->error($model,'telephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'hideTelephone'); ?>
		<?php echo $form->textField($model,'hideTelephone'); ?>
		<?php echo $form->error($model,'hideTelephone'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notifInscription'); ?>
		<?php echo $form->textField($model,'notifInscription'); ?>
		<?php echo $form->error($model,'notifInscription'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notifComment'); ?>
		<?php echo $form->textField($model,'notifComment'); ?>
		<?php echo $form->error($model,'notifComment'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notifUnsuscribe'); ?>
		<?php echo $form->textField($model,'notifUnsuscribe'); ?>
		<?php echo $form->error($model,'notifUnsuscribe'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notifDeleteRide'); ?>
		<?php echo $form->textField($model,'notifDeleteRide'); ?>
		<?php echo $form->error($model,'notifDeleteRide'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'notifModification'); ?>
		<?php echo $form->textField($model,'notifModification'); ?>
		<?php echo $form->error($model,'notifModification'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'blacklisted'); ?>
		<?php echo $form->textField($model,'blacklisted'); ?>
		<?php echo $form->error($model,'blacklisted'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'admin'); ?>
		<?php echo $form->textField($model,'admin'); ?>
		<?php echo $form->error($model,'admin'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->