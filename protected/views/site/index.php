<?php

/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

//recherche par ville
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
));


$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    'model'=>Town::model(),
    'attribute'=>'name',
    'source'=>$this->createUrl('towns/search'),
	'htmlOptions'=>array('placeholder'=>'Rechercher une ville'),
    // additional javascript options for the autocomplete plugin
    'options'=>array(
        'showAnim'=>'fold',
		'select' => 'js:function(event, ui){
			remove(ui.item.value);
		}',

    ),
    'cssFile'=>'jquery-ui.css',
));

 ?>
<?php $this->endWidget(); ?>
<style>
	#creerRide{
		
		display:block;
		width: 200px;
		height: 50px;
		background-image : url(<?php echo Yii::app()->request->baseUrl."/images/bouton.png" ?>);
	}
	#creerRide:hover{
		background-image : url(<?php echo Yii::app()->request->baseUrl."/images/boutonon.png" ?>);
	}
	#createRideBackground{
		width: 100%;

padding-top: 4px;
padding-bottom: 4px;
margin-top: 5px;
		margin-bottom: 26px;
	}
</style>


<?php
//affichage des rides auquel l'utilisateur courant est inscrit
/*if(count($registrations)!=0){
	foreach($registrations as $registration)
	{
		$v[$registration->id]=0;
	}
	$v[0]=1;
}else{
	$v[0]=-1;
}
if(count($ridesCurrent)!=0){
	foreach($ridesCurrent as $rideCurrent)
	{
		$r[$rideCurrent->id]=0;
	}
	$r[0]=1;
}else{
	$r[0]=-1;
}*/
if(count($registrations)!=0){
	foreach($registrations as $registration)
	{
		$v[$registration->id]=0;
	}
	$v[0]=1;
}else{
	$v[0]=1;
}
if(count($ridesCurrent)!=0){
	foreach($ridesCurrent as $rideCurrent)
	{
		$r[$rideCurrent->id]=0;
	}
	$r[0]=1;
}else{
	$r[0]=1;
}
//si aucune registration n'est disponible, afficher un message, sinon, afficher les registrations
/*if($v[0]==-1 && $r[0]==-1){
	echo "Vous ne participez à aucun trajet.";
}*/
if(count($registrations)==0 && count($ridesCurrent)==0)
{
	echo "Vous ne participez à aucun trajet.";
}

else
{
	echo "<table>";
	//echo "<tr><th>Conducteur</th><th>Places</th><th>Départ</th><th>Arrivée</th><th>Jour</th></tr>";
	echo "<tr><th>Conducteur</th><th>Départ</th><th>Arrivée</th><th>Jour</th></tr>";
	$i=0;

	$date=$datetime = date('Y-m-d 00:00:00', time());
	while($i<20 && (array_sum($v)<=count($registrations) || array_sum($r)<=count($ridesCurrent))) // 1 1 0 / 1 0 1 / 1 1 1 
	{
		foreach($registrations as $registration){
			$regRide = $registration->rideFk;
			if($regRide->visibility==1 && $registration->startDate<=$date && $registration->endDate>=$date && $regRide->day==date('N',strtotime($date)) && $v[$registration->id]==0 && $regRide->showDuringHolidays($date)){
				//prendre le ride qui correspond et l'afficher
				$daydate = date("d-m-Y",strtotime($date));
				echo "<tr onclick=";
				echo "\"document.location='".Yii::app()->createUrl('rides/view', array('id' => $regRide->id))."?date=".$daydate."';";
				echo "\" onmouseover='tablein(this);' onmouseout='tableout(this);'>";
					echo "<td>".$regRide->driver->prenom." ".$regRide->driver->nom."</td>";
					//echo "<td>"."0/".$regRide->seats."</td>";
					echo "<td>".$regRide->departuretown->name." à ".$regRide->departure."</td>";
					echo "<td>".$regRide->arrivaltown->name." vers ".$regRide->arrival."</td>";
				
					switch ($regRide->day) {
							case '1':
								$day = "Lundi";
								break;
							case '2':
								$day = "Mardi";
								break;
							case '3':
								$day = "Mercredi";
								break;
							case '4':
								$day = "Jeudi";
								break;
							case '5':
								$day = "Vendredi";
								break;
							case '6':
								$day = "Samedi";
								break;
							case '7':
								$day = "Dimanche";
								break;
							default:
								$day = "?";
								break;
						}
					echo "<td>".$day." ".$daydate."</td>";
				echo "</tr>";
				$i++;
				
			}
			if($registration->endDate==$date){
				$v[$registration->id]=1;
				/*var_dump($registrations);
				var_dump($v);
				var_dump($ridesCurrent);
				var_dump($r);
				die("salut");*/
			}
				
		}
	
	
		//affichage des rides pour lesquels l'utilisateur courant est conducteur
		foreach($ridesCurrent as $rideCurrent){
			$driver = User::model()->currentUser();

			if($rideCurrent->startDate<=$date && $rideCurrent->endDate>=$date && $rideCurrent->day==date('N',strtotime($date)) && $r[$rideCurrent->id]==0 && $rideCurrent->showDuringHolidays($date)){
				//prendre le ride qui correspond et l'afficher
				$daydate = date("d-m-Y",strtotime($date));
				echo "<tr onclick=";
				echo "\"document.location='".Yii::app()->createUrl('rides/view', array('id' => $rideCurrent->id))."?date=".$daydate."';";
				echo "\" onmouseover='tablein(this);' onmouseout='tableout(this);'>";
					echo "<td><img src='".Yii::app()->request->baseUrl."/images/driver.png' width='6%'/> ".$rideCurrent->driver->prenom." ".$rideCurrent->driver->nom."</td>";
					//echo "<td>"."0/".$rideCurrent->seats."</td>";
					echo "<td>".$rideCurrent->departuretown->name." à ".$rideCurrent->departure."</td>";
					echo "<td>".$rideCurrent->arrivaltown->name." vers ".$rideCurrent->arrival."</td>";
					switch ($rideCurrent->day) {
							case '1':
								$day = "Lundi";
								break;
							case '2':
								$day = "Mardi";
								break;
							case '3':
								$day = "Mercredi";
								break;
							case '4':
								$day = "Jeudi";
								break;
							case '5':
								$day = "Vendredi";
								break;
							case '6':
								$day = "Samedi";
								break;
							case '7':
								$day = "Dimanche";
								break;
							default:
								$day = "?";
								break;
						}
					echo "<td>".$day." ".$daydate."</td>";
				echo "</tr>";
				$i++;
				
			}
			if($rideCurrent->endDate==$date){
					$r[$rideCurrent->id]=1;
			}
				
		}
	
		$date=date('Y-m-d 00:00:00', strtotime($date.' +1 day'));
	}
}
    
echo "</table>";
?>


<center>
	<div id="createRideBackground">
<?php
echo "<a id='creerRide' href='".Yii::app()->createUrl('rides/create')."'></a>";
?>
</div>
</center>



<?php
//affichage des 20 prochains rides disponibles
unset($r);
$r[0]=0;
foreach($rides as $ride)
{
    $r[$ride->id]=0;
}
//si aucun ride n'est disponible, afficher un message, sinon, afficher les rides
if(count($rides)==0){
	echo "Aucun trajet n'est disponible.";
}
else{
	echo "<table>";
	//echo "<tr><th>Conducteur</th><th>Places</th><th>Départ</th><th>Arrivée</th><th>Jour</th></tr>";
	echo "<tr><th>Conducteur</th><th>Départ</th><th>Arrivée</th><th>Jour</th></tr>";
	$i=0;
	$date=$datetime = date('Y-m-d 00:00:00', time());
	while($i<80 && array_sum($r)<count($rides))
	{
		foreach ($rides as $ride) {

				if($ride->startDate<=$date && $ride->endDate>=$date && $ride->day==date('N',strtotime($date)) && $r[$ride->id]==0 && $ride->showDuringHolidays($date)){
					$daydate = date("d-m-Y",strtotime($date));
					echo "<tr onclick=";
					echo "\"document.location='".Yii::app()->createUrl('rides/view', array('id' => $ride->id))."?date=".$daydate."';";
					echo "\" onmouseover='tablein(this);' onmouseout='tableout(this);'>";

						echo "<td>".$ride->driver->prenom." ".$ride->driver->nom."</td>";
						//echo "<td>"."0/".$ride->seats."</td>";
						echo "<td>".$ride->departuretown->name." à ".$ride->departure."</td>";
						echo "<td>".$ride->arrivaltown->name." vers ".$ride->arrival."</td>";
					
						switch ($ride->day) {
								case '1':
									$day = "Lundi";
									break;
								case '2':
									$day = "Mardi";
									break;
								case '3':
									$day = "Mercredi";
									break;
								case '4':
									$day = "Jeudi";
									break;
								case '5':
									$day = "Vendredi";
									break;
								case '6':
									$day = "Samedi";
									break;
								case '7':
									$day = "Dimanche";
									break;
								default:
									$day = "?";
									break;
							}
						echo "<td>".$day." ".$daydate."</td>";
					echo "</tr>";
					$i++;
				}
				if($ride->endDate<$date)
				{
					$r[$ride->id]=1;
				}

		}
		$date=date('Y-m-d 00:00:00', strtotime($date.' +1 day'));
	}
	echo "</table>";
}
if(Yii::app()->params['ExecutionTime']=="yes")
{
	echo Yii::getLogger()->getExecutionTime();
}

?>
<script type="text/javascript">
function tablein(that){
    that.style.backgroundColor="#E5F1F4";
    that.style.cursor="pointer";
}
function tableout(that){
    that.style.backgroundColor="white";
    that.style.cursor="auto";
}
function remove(keep){
	$('td').show();
	$('table').find('tr:not(:contains('+ keep +'))').children('td').hide();
}
</script>
