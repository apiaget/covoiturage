<?php
/* @var $this RidesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rides',
);

$this->menu=array(
	array('label'=>'Create Ride', 'url'=>array('create')),
	array('label'=>'Manage Ride', 'url'=>array('admin')),
);
?>

<h1>Rides</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
