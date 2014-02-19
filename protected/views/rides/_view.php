<?php
/* @var $this RidesController */
/* @var $data Ride */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('driver')); ?>:</b>
	<?php echo CHtml::encode($data->driver); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('departuretown')); ?>:</b>
	<?php echo CHtml::encode($data->departuretown); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('arrivaltown')); ?>:</b>
	<?php echo CHtml::encode($data->arrivaltown); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('bindedride')); ?>:</b>
	<?php echo CHtml::encode($data->bindedride); ?>
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