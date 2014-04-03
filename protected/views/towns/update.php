<?php
/* @var $this TownsController */
/* @var $model Town */

$this->breadcrumbs=array(
	'Towns'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Town', 'url'=>array('index')),
	array('label'=>'Create Town', 'url'=>array('create')),
	array('label'=>'View Town', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Town', 'url'=>array('admin')),
);
?>

<h1>Update Town <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>