<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;

//formulaire de recherche : faire à la main
$form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	'enableAjaxValidation'=>false,
));


$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
    'model'=>$towns,
    'attribute'=>'name',
    'source'=>$this->createUrl('towns/search'),
	'htmlOptions'=>array('placeholder'=>'Rechercher une ville'),
    // additional javascript options for the autocomplete plugin
    'options'=>array(
        'showAnim'=>'fold',
		'select' => 'js:function(event, ui){
			remove($(\'#Town_name\').val());
		}',

    ),
    'cssFile'=>'jquery-ui.css',
));

//la fonction actionSearch se trouve dans le controller town
//echo $form->hiddenField($towns,'name',array()); 


 ?>

 <br>
  <br>
   <br>
    <br>
<?php $this->endWidget(); ?>
 
<?php
//affichage des rides auquel l'utilisateur courant est inscrit
foreach($registrations as $registration)
{
    $v[$registration->id]=0;
}
foreach($rides as $ride)
{
    $r[$ride->id]=0;
}

//si aucune registration n'est disponible, afficher un message, sinon, afficher les registrations
if(count($registrations)==0){
	echo "Vous ne participez à aucun trajet.";
}
else{
	echo "<table>";
	echo "<tr><th>Conducteur</th><th>Places</th><th>Départ</th><th>Arrivée</th><th>Jour</th></tr>";
	$i=0;

	$date=$datetime = date('Y-m-d 00:00:00', time());

		while($i<20 && array_sum($v)<count($registrations) && array_sum($r)<count($rides))
		{
			//tel jour
			foreach($registrations as $registration){
				//Ride::model()->find('id=:id', array(':id'=>$registration->ride_fk));
				//SI $registration correspond au jour
				//if($ride->startDate<=$date && $ride->endDate>=$date && $ride->day==date('N',strtotime($date)) && $r[$ride->id]==0 && $ride->showDuringHolidays($date)){
				$regRide = Ride::model()->find('id=:id', array(':id'=>$registration->ride_fk));
				/*var_dump($regRide->day);
				echo $regRide->day==date('N',strtotime($date));
					die(date('N',strtotime($date)));*/
				if($registration->startDate<=$date && $registration->endDate>=$date && $regRide->day==date('N',strtotime($date)) && $v[$registration->id]==0 && $ride->showDuringHolidays($date)){
					//prendre le ride qui correspond et l'afficher
					$daydate = date("d-m-Y",strtotime($date));
					echo "<tr onclick=";
					echo "\"document.location='/covoiturage/covoiturage/rides/".$regRide->id."?date=".$daydate."';";
					echo "\" onmouseover='tablein(this);' onmouseout='tableout(this);'>";

						echo "<td>".$regRide->driver->cpnvId."</td>";
						echo "<td>"."0/".$regRide->seats."</td>";
						echo "<td>".$regRide->departuretown->name." à ".substr($regRide->departure, 0, 5)."</td>";
						echo "<td>".$regRide->arrivaltown->name." vers ".substr($regRide->arrival, 0, 5)."</td>";
					
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
					}
					
			}
		
		
		//affichage des rides pour lesquels l'utilisateur courant est conducteur
		foreach($rides as $ride){
			//Ride::model()->find('id=:id', array(':id'=>$registration->ride_fk));
			//SI $registration correspond au jour
			//if($ride->startDate<=$date && $ride->endDate>=$date && $ride->day==date('N',strtotime($date)) && $r[$ride->id]==0 && $ride->showDuringHolidays($date)){
			$driver = User::model()->currentUser();

			/*var_dump($regRide->day);
			echo $regRide->day==date('N',strtotime($date));
				die(date('N',strtotime($date)));*/
			if($ride->startDate<=$date && $ride->endDate>=$date && $ride->day==date('N',strtotime($date)) && $r[$ride->id]==0 && $ride->driver_fk==$driver->id && $ride->showDuringHolidays($date)){
				//prendre le ride qui correspond et l'afficher
				$daydate = date("d-m-Y",strtotime($date));
				echo "<tr onclick=";
				echo "\"document.location='/covoiturage/covoiturage/rides/".$ride->id."?date=".$daydate."';";
				echo "\" onmouseover='tablein(this);' onmouseout='tableout(this);'>";

					echo "<td><img src='/covoiturage/covoiturage/images/driver.png' width='8%'/>".$ride->driver->cpnvId."</td>";
					echo "<td>"."0/".$ride->seats."</td>";
					echo "<td>".$ride->departuretown->name." à ".substr($ride->departure, 0, 5)."</td>";
					echo "<td>".$ride->arrivaltown->name." vers ".substr($ride->arrival, 0, 5)."</td>";
				
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
			if($ride->endDate==$date){
					$r[$ride->id]=1;
				}
				
		}
		
		$date=date('Y-m-d 00:00:00', strtotime($date.' +1 day'));
	}
}
    
echo "</table>";
?>

<hr/>

<?php
echo CHtml::link("Créer un nouveau trajet", array('rides/create'));
?>

<hr/>


<?php
//affichage des 20 prochains rides disponibles
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
	echo "<tr><th>Conducteur</th><th>Places</th><th>Départ</th><th>Arrivée</th><th>Jour</th></tr>";
	$i=0;
	$date=$datetime = date('Y-m-d 00:00:00', time());
	while($i<20 && array_sum($r)<count($rides))
	{
		foreach ($rides as $ride) {

				if($ride->visibility==1 && $ride->startDate<=$date && $ride->endDate>=$date && $ride->day==date('N',strtotime($date)) && $r[$ride->id]==0 && $ride->showDuringHolidays($date)){
					$daydate = date("d-m-Y",strtotime($date));
					echo "<tr onclick=";
					echo "\"document.location='/covoiturage/covoiturage/rides/".$ride->id."?date=".$daydate."';";
					echo "\" onmouseover='tablein(this);' onmouseout='tableout(this);'>";

						echo "<td>".$ride->driver->cpnvId."</td>";
						echo "<td>"."0/".$ride->seats."</td>";
						echo "<td>".$ride->departuretown->name." à ".substr($ride->departure, 0, 5)."</td>";
						echo "<td>".$ride->arrivaltown->name." vers ".substr($ride->arrival, 0, 5)."</td>";
					
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
/*
//afficher 20 trajets en comptant les récurrences

i=0 //dès qu'une occurence de trajet est affichée, augmente de 1
j=date //commence à aujourd'hui, à chaque tour, augmente d'un jour
$r[0]=0 //tableau ayant comme index l'id du ride et comme valeur 0 s'il n'a pas affiché toutes ses occurences et 1 si il les a toutes affichées
        //sert à ne pas boucler à l'infini si on a moins de 20 occurences à afficher

pour tous les rides en tant que ride
    $r[$ride->id]=0;
end pour tout les


while i<=20 && la somme de $r < le nombre de ride
    pour tout les rides en tant que ride    //on parcours tous les rides transmis par le controlleur
        $ride->visibility==1 && $ride->startDate<=$date && $ride->endDate>=$date            && $ride->day==date('N',strtotime($date)) && $r[$ride->id]==0 && $ride->showDuringHolidays($date)
        si le ride n'est pas supprimé && la date de début du ride<=j && la date de fin du ride>=j
           && ride.joursemaine=j.joursemaine && //afin de n'afficher que les rides voulus
            affiche le ride
        end si
    end pour tout les
    i++
    j=j+1 jour
endwhile
*/

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
</script>
<script>
	//suppression des lignes inutiles
	function remove(douda){
		$('td').show();
		$('table').find('tr:not(:contains('+ douda +'))').children('td').hide();
	}
</script>
