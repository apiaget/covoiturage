<?php
/* @var $this UsersController */
/* @var $data Users */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('cpnvId')); ?>:</b>
	<?php echo CHtml::encode($data->cpnvId); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hideEmail')); ?>:</b>
	<?php echo CHtml::encode($data->hideEmail); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('telephone')); ?>:</b>
	<?php echo CHtml::encode($data->telephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('hideTelephone')); ?>:</b>
	<?php echo CHtml::encode($data->hideTelephone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notifInscription')); ?>:</b>
	<?php echo CHtml::encode($data->notifInscription); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('notifComment')); ?>:</b>
	<?php echo CHtml::encode($data->notifComment); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notifUnsuscribe')); ?>:</b>
	<?php echo CHtml::encode($data->notifUnsuscribe); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notifDeleteRide')); ?>:</b>
	<?php echo CHtml::encode($data->notifDeleteRide); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('notifModification')); ?>:</b>
	<?php echo CHtml::encode($data->notifModification); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('blacklisted')); ?>:</b>
	<?php echo CHtml::encode($data->blacklisted); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('admin')); ?>:</b>
	<?php echo CHtml::encode($data->admin); ?>
	<br />

	*/ ?>

</div>