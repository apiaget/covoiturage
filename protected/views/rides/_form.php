<?php
/* @var $this RidesController */
/* @var $model Ride */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ride-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'driver'); ?>
		<?php echo $form->textField($model,'driver'); ?>
		<?php echo $form->error($model,'driver'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'departuretown'); ?>
		<?php echo $form->textField($model,'departuretown'); ?>
		<?php echo $form->error($model,'departuretown'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'arrivaltown'); ?>
		<?php echo $form->textField($model,'arrivaltown'); ?>
		<?php echo $form->error($model,'arrivaltown'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'bindedride'); ?>
		<?php echo $form->textField($model,'bindedride'); ?>
		<?php echo $form->error($model,'bindedride'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'departure'); ?>
		<?php echo $form->textField($model,'departure'); ?>
		<?php echo $form->error($model,'departure'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'arrival'); ?>
		<?php echo $form->textField($model,'arrival'); ?>
		<?php echo $form->error($model,'arrival'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'seats'); ?>
		<?php echo $form->textField($model,'seats'); ?>
		<?php echo $form->error($model,'seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startDate'); ?>
		<?php echo $form->textField($model,'startDate'); ?>
		<?php echo $form->error($model,'startDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endDate'); ?>
		<?php echo $form->textField($model,'endDate'); ?>
		<?php echo $form->error($model,'endDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'day'); ?>
		<?php echo $form->textField($model,'day'); ?>
		<?php echo $form->error($model,'day'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->