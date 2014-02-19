<?php
/* @var $this RidesController */
/* @var $model Ride */
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
		<?php echo $form->label($model,'driver'); ?>
		<?php echo $form->textField($model,'driver'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'departuretown'); ?>
		<?php echo $form->textField($model,'departuretown'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'arrivaltown'); ?>
		<?php echo $form->textField($model,'arrivaltown'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'bindedride'); ?>
		<?php echo $form->textField($model,'bindedride'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'departure'); ?>
		<?php echo $form->textField($model,'departure'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'arrival'); ?>
		<?php echo $form->textField($model,'arrival'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'seats'); ?>
		<?php echo $form->textField($model,'seats'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'startDate'); ?>
		<?php echo $form->textField($model,'startDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'endDate'); ?>
		<?php echo $form->textField($model,'endDate'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'day'); ?>
		<?php echo $form->textField($model,'day'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->