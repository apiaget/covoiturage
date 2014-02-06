<?php
/* @var $this RidesController */
/* @var $model Rides */

$this->breadcrumbs=array(
	'Rides'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Rides', 'url'=>array('index')),
	array('label'=>'Create Rides', 'url'=>array('create')),
	array('label'=>'View Rides', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Rides', 'url'=>array('admin')),
);
?>

<h1>Update Rides <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>