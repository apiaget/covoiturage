<?php
/* @var $this TownsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Towns',
);

$this->menu=array(
	array('label'=>'Create Town', 'url'=>array('create')),
	array('label'=>'Manage Town', 'url'=>array('admin')),
);
?>

<h1>Towns</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
