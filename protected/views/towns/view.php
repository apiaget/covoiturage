<?php
/* @var $this TownsController */
/* @var $model Town */

$this->breadcrumbs=array(
	'Towns'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Town', 'url'=>array('index')),
	array('label'=>'Create Town', 'url'=>array('create')),
	array('label'=>'Update Town', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Town', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Town', 'url'=>array('admin')),
);
?>

<h1>View Town #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
	),
)); ?>
