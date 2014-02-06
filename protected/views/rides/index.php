<?php
/* @var $this RidesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Rides',
);

$this->menu=array(
	array('label'=>'Create Rides', 'url'=>array('create')),
	array('label'=>'Manage Rides', 'url'=>array('admin')),
);
?>

<h1>Rides</h1>

<?php /* $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); */?>



<?php 
	$model = rides::model();

$this->widget('zii.widgets.CDetailView', array(
	/*'dataProvider'=>$dataProvider,*/
	'data'=>rides::model(),
	'attributes'=>array(
		'id',
		'users_id',
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
