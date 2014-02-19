<?php
/* @var $this RidesController */
/* @var $model Ride */

$this->breadcrumbs=array(
	'Rides'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Ride', 'url'=>array('index')),
	array('label'=>'Create Ride', 'url'=>array('create')),
	array('label'=>'Update Ride', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Ride', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ride', 'url'=>array('admin')),
);
?>

<h1>View Ride #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'driver',
		'departuretown',
		'arrivaltown',
		'bindedride',
		'description',
		'departure',
		'arrival',
		'seats',
		'startDate',
		'endDate',
		'day',
	),
)); ?>
