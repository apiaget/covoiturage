<?php
/* @var $this RidesController */
/* @var $model Rides */

$this->breadcrumbs=array(
	'Rides'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Rides', 'url'=>array('index')),
	array('label'=>'Create Rides', 'url'=>array('create')),
	array('label'=>'Update Rides', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Rides', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Rides', 'url'=>array('admin')),
);
?>

<h1>View Rides #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'users_id',
		array(
			'name'=>'users_id',
			'value' => $model->users->cpnvId,
			),
		'towns_id',
		'towns_id1',
		'rides_id',
		'description',
		'departure',
		'arrival',
		'seats',
		'startDate',
		'endDate',
		'day',
	),
)); ?>
