<?php
/* @var $this RidesController */
/* @var $ride Ride */

/*$this->breadcrumbs=array(
	'Rides'=>array('index'),
	$ride->id,
);*/

/*$this->menu=array(
	array('label'=>'List Ride', 'url'=>array('index')),
	array('label'=>'Create Ride', 'url'=>array('create')),
	array('label'=>'Update Ride', 'url'=>array('update', 'id'=>$ride->id)),
	array('label'=>'Delete Ride', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$ride->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Ride', 'url'=>array('admin')),
);*/
?>

<?php 
/*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$ride,
	'attributes'=>array(
		'id',
		'driver_fk',
		'departuretown_fk',
		'arrivaltown_fk',
		'bindedride',
		'description',
		'departure',
		'arrival',
		'seats',
		'startDate',
		'endDate',
		'day',
	),
));*/ 


//	voit paramètre du ride
//	Si driver
//		voit personnes inscrites, validée ou non avec leur réputation et leur téléphone
//		possibilité de valider les utilisateurs
//		possibilité d'expulser des utilisateurs
//		voit bouton "editer", "supprimer"
//		voit commentaires, possibilités d'en supprimer et de répondre
//	Si passager
//		voit natel du conducteur
//		voit les autres utilisateurs inscrits
//		possibilité de se désinscrire
//		voit commentaires avec possibilité de répondre
//	Sinon
//		case contenant la date remplie si get la contient
//		case à cocher récurrence / aller-retour
//		bouton "s'inscrire"
//		voit commentaires avec possibilité de répondre
//	fin Si
?>
