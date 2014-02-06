<?php
/* @var $this RidesController */
/* @var $model Rides */

$this->breadcrumbs=array(
	'Rides'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Rides', 'url'=>array('index')),
	array('label'=>'Manage Rides', 'url'=>array('admin')),
);
?>

<h1>Create Rides</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>