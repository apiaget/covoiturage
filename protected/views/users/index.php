<?php
/* @var $this UsersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Users',
);

$this->menu=array(
	array('label'=>'Create Users', 'url'=>array('create')),
	array('label'=>'Manage Users', 'url'=>array('admin')),
);
?>

<h1>Users</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>


<?php 
$dataProvider=new CActiveDataProvider('Rides');

$this->widget('zii.widgets.CDetailView', array(
	//'dataProvider'=>$dataProvider,
	'data'=>rides::model(),
	'attributes'=>array(
		'id',
		'users_id',
		/*array(
			'name'=>'users_id',
			'value' => rides::relations()->users->telephone,
			),*/
	array(
			'name'=>'users_id',
			//'value' => rides::model()>users->cpnvId,
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