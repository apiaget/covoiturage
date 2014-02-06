<?php
/* @var $this RidesController */
/* @var $data Rides */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('users_id')); ?>:</b>
	<?php echo CHtml::encode($data->users_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('towns_id')); ?>:</b>
	<?php echo CHtml::encode($data->towns_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('towns_id1')); ?>:</b>
	<?php echo CHtml::encode($data->towns_id1); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('rides_id')); ?>:</b>
	<?php echo CHtml::encode($data->rides_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('description')); ?>:</b>
	<?php echo CHtml::encode($data->description); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departure')); ?>:</b>
	<?php echo CHtml::encode($data->departure); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('arrival')); ?>:</b>
	<?php echo CHtml::encode($data->arrival); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('seats')); ?>:</b>
	<?php echo CHtml::encode($data->seats); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('startDate')); ?>:</b>
	<?php echo CHtml::encode($data->startDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('endDate')); ?>:</b>
	<?php echo CHtml::encode($data->endDate); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('day')); ?>:</b>
	<?php echo CHtml::encode($data->day); ?>
	<br />

	*/ ?>

</div>