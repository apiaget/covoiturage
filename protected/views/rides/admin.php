<?php
/* @var $this RidesController */
/* @var $model Rides */

$this->breadcrumbs=array(
	'Rides'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List Rides', 'url'=>array('index')),
	array('label'=>'Create Rides', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#rides-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Rides</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'rides-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'users_id',
		'towns_id',
		'towns_id1',
		'rides_id',
		'description',
		/*
		'departure',
		'arrival',
		'seats',
		'startDate',
		'endDate',
		'day',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
